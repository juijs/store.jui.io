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

$filename = str_replace("/$id/", '', $_POST['filename']);

// create a new file in repo
$file_path = $repo->getRepositoryPath().DIRECTORY_SEPARATOR . $filename;

if (!file_exists($file_path)) {
	echo json_encode(array('result' => false, 'message' => 'file is not exists.'));
	return;
}

setlocale(LC_CTYPE, "ko_KR.UTF-8");
//$repo->removeFile($filename);
$repo->setConfigExt('core.quotepath false');
//var_dump($filename);
$repo->removeFile($filename);
echo json_encode(array('result' => true, 'message' => 'delete is success'));
