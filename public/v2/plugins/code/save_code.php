<?php 
error_reporting(E_ALL);
include_once '../../../../bootstrap.php';

use Cz\Git\GitRepository;

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}

header('Content-Type: application/json');

// user check 
// id check 
// path check 

$file = $_POST['file'];
$arr = explode("/", $file);
$id = $arr[1];

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

$real_path = realpath(REPOSITORY.$file);

//var_dump($real_path, $_POST);
file_put_contents($real_path, $_POST['content']);

echo json_encode(array('result' => true));
