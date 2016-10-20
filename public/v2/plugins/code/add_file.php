<?php 
error_reporting(E_ALL);
include_once '../../../../bootstrap.php';
include_once 'common.php'; 

use Cz\Git\GitRepository;
header('Content-Type: application/json');

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}

$id = $_POST['id'] ? $_POST['id'] : (string)$document['_id'];

$dir = REPOSITORY.'/'.$id. '/';

$repo = new GitRepository($dir);

// create a new file in repo
$filename = $dir .$_POST['filename'];

if (file_exists($filename)) {
	echo json_encode(array('result' => false, 'message' => 'already file is exists'));
	return;
}

$arr = array_map('trim', explode("/", $filename));

$is_dir = (array_pop($arr) == '');

setlocale(LC_CTYPE, "ko_KR.UTF-8");
if ($is_dir) {
	mkdir($filename, 0777, true);
	$repo->setConfigExt('core.quotepath false');
	$repo->addFile($filename);
    
	echo json_encode(array('result' => true, 'message' => 'directory is success'));
	return; 
} else {


	if (!is_support_type($filename)) {
		echo json_encode(array('result' => false, 'message' => 'file type is not supported type' ));
		return;
	}

	mkdir(dirname($filename), 0777, true);

	file_put_contents($filename, "");

	$repo->setConfigExt('core.quotepath false');
	$repo->addFile($filename);

	// 권한 넣기 
	chmod($filename, 0777);
	echo json_encode(array('result' => true, 'message' => 'file is success'));
	return; 

}

?>
