<?php
error_reporting(E_ALL);
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


$dir = REPOSITORY.'/'.$id. '/';
$filepath = str_replace("/$id", '', $_POST['filepath']);
$repo = new GitRepository($dir.$filepath);



$total = count($_FILES['files']['tmp_name']);

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

setlocale(LC_CTYPE, "ko_KR.UTF-8");
$root = $repo->getRepositoryPath();
$result = array();
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
			$repo->setConfigExt('core.quotepath false');
			$repo->addFile($name);
			// 권한 넣기 
			chmod($name, 0666);
		} else {

		}
	}
	
	$result[$i] = array(
		'name' => $name,
		'uploaded' => $is_uploaded,
		'error' => $error
	);

}

/*
// create a new file in repo


if (!file_exists($filename)) {
	echo json_encode(array('result' => false, 'message' => 'file is not exists.'));
	return;
}
*/

 die(json_encode(array('result'=> $result)));
?>
