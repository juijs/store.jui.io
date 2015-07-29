<?php 
error_reporting(E_ALL);
include_once '../bootstrap.php';

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}

header('Content-Type: application/json');

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$_POST = array_filter($_POST, 'strip_tags');

// must be saved 
$document = array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid'],
	'username' => $_SESSION['username'],
	'avatar' => $_SESSION['avatar'],
	'type' => $_POST['type'],
	'access' => $_POST['access'],
	'title' => $_POST['title'],
	'name' => $_POST['name'],
	'description' => $_POST['description'],
	'component_code' => $_POST['component_code'],
	'sample_code' => $_POST['sample_code'],
	'sample_type' => $_POST['sample_type'],
	'sample' => $_POST['sample'],
	'data_type' => $_POST['data_type'],
	'license' => $_POST['license'],
	'update_time' => time()
);


if ($_POST['id']) {
	$result = $components->update(array(
		'_id' => new MongoId($_POST['id']),
		'login_type' => $_SESSION['login_type'],
		'userid' => $_SESSION['userid']
	), array('$set' => $document), array('upsert' => true) );
} else {

	$prevData = $components->findOne(array(
		'name' => $_POST['name']
	));

	if ($prevData['_id']) {
		echo json_encode(array('result' => false, 'message' => 'Name is already exists.'));
		exit;
	}

	$components->insert($document);

	$result  = array(
		'ok' => $document['_id'] ? true : false 	
	);
}

if ($result['ok']) {
	$id = $_POST['id'] ? $_POST['id'] : (string)$document['_id'];

	echo json_encode(array('id' => $id, 'result' => true));
} else {
	echo json_encode(array('result' => false));
}

?>
