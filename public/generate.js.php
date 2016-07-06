<?php 
include_once '../bootstrap.php';

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$document = array( '_id' => new MongoId($_GET['id']) );

$component = $components->findOne($document);

if ($component['type'] == 'map') {
	header("Content-Type: image/svg+xml");
}

if ($_GET['code'] == 'sample') {
	echo $component['sample_code'];
} else if ($_GET['code'] == 'html') {
	echo $component['html_code'];
} else if ($_GET['code'] == 'css') {
	header("Content-Type: text/css");
	$data = $component;
	include "include/preprocessor.php";
	echo $data['css_code'];
} else {
	echo $component['component_code'];
}

?>