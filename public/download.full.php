<?php 
error_reporting(E_ALL);
include_once '../bootstrap.php';

$ext = array(
	'component' => 'js',
	'theme' => 'js',
	'style' => 'less',
	'css' => 'css',
	'data' => '',
	'map' => 'svg'
);

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$document = array( '_id' => new MongoId($_GET['id']) );

$component = $components->findOne($document);

$filename = rawurlencode($component['name']);
$filename = "sample-".$filename.".html";

header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Transfer-Encoding: binary");

// create static file 
$data = $component;
$content = include_once "make.static.file.full.php";

header("Content-Length: ".strlen($content));
echo $content;


?>
