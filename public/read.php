<?php 

include_once '../bootstrap.php';

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$document = array( '_id' => new MongoId($_GET['id']) );

$component = $components->findOne($document);

header('Content-Type: application/json');

if ($component) {
	unset($component['_id']);
	echo json_encode($component);
} else {
	echo json_encode(array());
}

?>
