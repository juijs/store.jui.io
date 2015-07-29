<?php 

include_once '../bootstrap.php';

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

header('Content-Type: application/json');


$name = "data_".$_GET['componentId'];

$data = $db->{$name};

$temp = $data->findOne(array(
	'_id' => new MongoId($_GET['id'])
));

$temp['id'] = (string)$temp['_id'];
unset($temp['_id']);

echo json_encode($temp ? $temp : new stdClass());

?>