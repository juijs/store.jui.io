<?php
error_reporting(E_ALL);
include_once '../../../../bootstrap.php';
include_once 'common.php'; 

use Cz\Git\GitRepository;

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

//var_dump($_POST);
setlocale(LC_CTYPE, "ko_KR.UTF-8");
$repo->setConfig($_SESSION['username']);

$commit_message = $_POST['commit_message'];
$ret = $repo->commit($commit_message, "-a");

die(json_encode(array('result'=> $ret ? true : false )));
?>
