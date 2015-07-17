<?php 
error_reporting(E_ALL);
header('Content-Type: application/json');
include_once '../bootstrap.php';

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;
$good_count = $db->good_count;

$good_count->ensureIndex(array(
	'component_id' => 1,
	'login_type' => 1,
	'userid' => 1
));

$data = $good_count->findOne(array(
	'component_id' => $_POST['id'],
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
));

if ($data) {
	echo json_encode(array("result"=> false, 'message' => 'you are already clicked a good button.'));
	exit;
}

$document = array(
	'component_id' => $_POST['id'],
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
);

$good_count->insert($document);

if ($document['_id']) {
	$components->update(array(
		'_id' => new MongoId($_POST['id'])
	), array(
		'$inc' => array("good" => 1)
	));

	$row = $components->findOne(array(
		'_id' => new MongoId($_POST['id'])
	));

	echo json_encode(array('id' => $id, 'good' => $row['good'], 'result' => true));

}
?>
