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

$root = $repo->getRepositoryPath();

$isChanged = $repo->hasChanges();

$repo->setConfigExt('core.quotepath false');
$arr = $repo->changedFiles();

$newFiles = array();
foreach($arr as $file) {
	if ($file['status'] == 'new') {
		$newFiles[$file['file']] = $file; 
	} else if ($file['status'] == 'modified') {
		if ($newFiles[$file['file']]) {
			$newFiles[$file['file']]['status'] = 'modified';
		} else {
			$newFiles[$file['file']] = $file;
		}
	}
}

$keys = array_keys($newFiles);

$temp = array();

foreach($keys as $key) {
	$temp[] = $newFiles[$key];
}



 die(json_encode(array('result'=> $isChanged, 'changed_files' => $temp)));
?>
