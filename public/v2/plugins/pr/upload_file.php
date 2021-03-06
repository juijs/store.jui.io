<?php
include_once '../../../../bootstrap.php';
include_once 'common.php'; 

use Cz\Git\GitRepository;

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}

header('Content-Type: application/json');


$id = $_POST['id'];

if (!$id) {
	echo json_encode(array('result' => false, 'message' => 'Not found'));
	exit;
}

$m = new MongoClient();

// select a rowbase
$db = $m->store;

$components = $db->components;

$row = $components->findOne(array(
	'_id' => new MongoId($id)
));

$isMy = true;
if ($id && ($row['login_type'] != $_SESSION['login_type'] || $row['userid'] != $_SESSION['userid']) ) {
	echo json_encode(array('result' => false, 'message' => 'Access denined.'));
    exit;
}


// 파일 디렉토리 생성
$dir = REPOSITORY.'/'.$id. '/';
mkdir($dir, 0777, true);


if (!is_dir("$dir/.git")) {
	$repo = GitRepository::init($dir);

	$repo->setConfig($_SESSION['username']);
	$repo->setConfigExt('core.quotepath false');
	$repo->setConfigExt('core.precomposeunicode true');
} else {
    $repo = new GitRepository($dir);
}


// 파일별 unique 아이디를 만들어서 저장 
function file_numbering($name) {
	$actual_name = pathinfo($name, PATHINFO_FILENAME);
	$dir_name = pathinfo($name, PATHINFO_DIRNAME);
	$original_name = $actual_name;
	$extension = pathinfo($name, PATHINFO_EXTENSION);

	$i = 1;
	while(file_exists($dirname.'/'.$actual_name.".".$extension))
	{           
		$actual_name = (string)$original_name.$i;
		$name = $actual_name.".".$extension;
		$i++;
	}

	return $dirname.'/'.$name;
}

$total = count($_FILES['files']['tmp_name']);


setlocale(LC_CTYPE, "ko_KR.UTF-8");
$root = $repo->getRepositoryPath();

$result = array();
$file_names = array();
for($i = 0; $i < $total; $i++) {
	$name = $_FILES['files']['name'][$i];
	$tmp_name = $_FILES['files']['tmp_name'][$i];
	$error = $_FILES['files']['error'][$i];
	
	$filename = $root.'/'.$name;
	$filename = file_numbering($filename);
	$is_uploaded = false; 

	if ($error == UPLOAD_ERR_OK) {
		$is_uploaded = @move_uploaded_file($tmp_name, $filename);

		if($is_uploaded) {
			$name = end(explode('/', $filename));
			// git add 
			$repo->addFile($name);
			// 권한 넣기 
			chmod($name, 0666);
		} else {

		}
	}
	
	$result[$i] = array(
        'created_filename' => $filename,
		'name' => $name,
		'uploaded' => $is_uploaded,
		'error' => $error
	);

    $file_names[] = $name; 

}

// commit 하기 
$commit_message = "upload files : ".implode(",", $file_names);
$repo->commit($commit_message);


 die(json_encode(array('result'=> $result)));
?>
