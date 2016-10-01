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

if ($_POST['file'] == "/$id") {
	$_POST['file'] = "/$id/";
}

$filepath = str_replace("/$id/", '', $_POST['file']);

$filename = basename($filepath);
$dirname = dirname($filepath);
$repo = new GitRepository($dir);

$root = $repo->getRepositoryPath();

if ($_POST['isFile'] == 'true') {
	$search_file = $filepath;
}

$arr = $repo->logs($search_file);

$temp = array();
foreach($arr as $text) {
	$info = explode("||", $text);
	$temp[] = array( 'commitId' => $info[0],	 'author' => $info[1],	 'ago' => $info[2],	 'message' => $info[3], "commit_day" => date('Y-m-d', strtotime($info[4]))	);
}

 die(json_encode(array('result'=> true, 'logs' => $temp)));
