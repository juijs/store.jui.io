<?php 
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
$repo->setConfig($_SESSION['username']);
$repo->setConfigExt('core.quotepath false');

if ($is_dir) {
	mkdir($filename, 0777, true);
	$repo->addFile($filename);
	echo json_encode(array('result' => true, 'message' => 'directory is success'));
	exit; 
} else {


	if (!is_support_type($filename)) {
		echo json_encode(array('result' => false, 'message' => 'file type is not supported type' ));
		return;
	}

	mkdir(dirname($filename), 0777, true);

	file_put_contents($filename, "");

	$repo->addFile($filename);

	// 권한 넣기 
	chmod($filename, 0666);
    $repo->commit("add ".$filename, "-a");
	echo json_encode(array('result' => true, 'message' => 'file is success'));
	exit; 

}

?>
