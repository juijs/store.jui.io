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

$repo = new GitRepository($dir);

$prev_file = str_replace("/$id", '', $_POST['prev_file']);

// create a new file in repo
$filename = $repo->getRepositoryPath() . $prev_file;

if (!file_exists($filename)) {
	echo json_encode(array('result' => false, 'message' => 'file is not exists.'));
	return;
}

$arr = array_map('trim', explode("/", $filename));
$is_dir = (array_pop($arr) == '');

setlocale(LC_CTYPE, "ko_KR.UTF-8");
if ($is_dir) {
	$arr = explode('/', $prev_file);

	$filename = explode("/", $_POST['filename']);
	$arr[count($arr)-2] = $filename[0];

	$repo->renameFile($prev_file, join("/", $arr));
	echo json_encode(array('result' => true, 'message' => 'directory is success'));
	return; 
} else {

	if (!is_support_type($filename)) {
		echo json_encode(array('result' => false, 'message' => 'file type is not supported type' ));
		return;
	}

	$arr = explode('/', $prev_file);

	$filename = explode("/", $_POST['filename']);
	$arr[count($arr)-1] = $filename[0];

	$repo->renameFile($prev_file, join("/", $arr));

	echo json_encode(array('result' => true, 'message' => 'file is success'));
	return; 

}
