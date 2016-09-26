<?php 
error_reporting(E_ALL);
include_once '../../../../bootstrap.php';
include_once "common.php";
use Cz\Git\GitRepository;

header('Content-Type: application/json');
if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}


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
$content = $_POST['content'];

if (is_image_type($real_path)) {
	$content = explode(",", $content);
	$type = array_shift($content);
	$content = base64_decode($content[0]);
}
file_put_contents($real_path, $content);

echo json_encode(array('result' => true));
