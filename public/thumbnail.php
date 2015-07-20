<?php 
include_once '../bootstrap.php';

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$row = $components->findOne(array(
	'_id' => new MongoId($_GET['id'])
));

if ($row['access'] == 'private') {
	header("HTTP/1.0 404 Not Found");
	exit;
}

header("Content-Type: image/png");

if ($row['sample']) {
	echo base64_decode(str_replace("data:image/png;base64,", "", $row['sample']));
} else {
	readfile("images/chart-sample.jpg");
}
?>