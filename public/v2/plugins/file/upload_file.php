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




function file_numbering($name) {
	$actual_name = pathinfo($name, PATHINFO_FILENAME);
	$dir_name = pathinfo($name, PATHINFO_DIRNAME);
	$extension = pathinfo($name, PATHINFO_EXTENSION);
	$name = $original_name = $actual_name;
	$i = 1;
	while(file_exists($dir_name.'/'.$actual_name.".".$extension))
	{           
		$actual_name = (string)$original_name.'('.$i.')';
		$name = $actual_name.".".$extension;
		$i++;
	}

	return $dir_name.'/'.$name.".".$extension;
}

setlocale(LC_CTYPE, "ko_KR.UTF-8");
$repo->setConfig($_SESSION['username']);
$repo->setConfigExt('core.quotepath false');


$root = $repo->getRepositoryPath();

$result = array();
$file_list = array();

if ($_POST['url']) { // upload lin

    $url = $_POST['url'];
    $name = basename($url);       // link 의 마지막은 파일 이름이여야 함. 
    $filename = $root.'/'.$name;
    $filename = file_numbering($filename);
    $is_uploaded = false; 

    file_put_contents($filename, file_get_contents($url));
    $name = end(explode('/', $filename));
    // git add 
    $repo->addFile($name);
    $file_list[] = $name; 
    // 권한 넣기 
    chmod($name, 0666);

    $error = '';
    $is_uploaded = true;
    $result[] = array(
        'name' => $name,
        'uploaded' => $is_uploaded,
        'error' => $error
    );
    $commitPostfix = " - ".$url;


} else { // upload files 

    $total = count($_FILES['files']['tmp_name']);
    for($i = 0; $i < $total; $i++) {
        $name = $_FILES['files']['name'][$i];
        $tmp_name = $_FILES['files']['tmp_name'][$i];
        $error = $_FILES['files']['error'][$i];

        
        $filename = $root.'/'.$name;
        $filename = file_numbering($filename);
        $is_uploaded = false; 

        if ($error == UPLOAD_ERR_OK) {
            $is_uploaded = move_uploaded_file($tmp_name, $filename);
            if($is_uploaded) {
                $name = end(explode('/', $filename));
                // git add 
                $repo->addFile($name);
                $file_list[] = $name; 
                // 권한 넣기 
                chmod($name, 0666);
            } else {
                var_dump(error_get_last());
            }
        }
        
        $result[$i] = array(
            'name' => $name,
            'uploaded' => $is_uploaded,
            'error' => $error
        );

    }

}

$repo->commit("upload ".implode(",", $file_list)." ".$commitPostfix, "-a");

 die(json_encode(array('result'=> $result)));
?>
