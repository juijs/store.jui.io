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

if ($_GET['ext'] == 'css' && $component['type'] == 'style') {
	$id = uniqid("temp".rand(0, 100).rand(0,100));
	$code = $component['component_code'];
	$filename = "sample/ui/theme/".$id.".less";

	file_put_contents(__DIR__."/".$filename, $code);

	try {
		$parser = new Less_Parser();
		$parser->parseFile( __DIR__."/".$filename );
		$result = $parser->getCss();

		$result = str_replace("../img", "img", $result);
		$result = str_replace("../widget/img", "img", $result);

	} catch (Exception $ex) {

		echo $ex->getMessage();
	}

	unlink($filename);

	$component['type'] = 'css';
	$component['component_code'] = $result;
}

$filename = rawurlencode($component['name']);

if ($_GET['code'] == 'sample') {
	$filename = "sample-".$filename.".js";
} else {
	$filename .= ".".$ext[$component['type']];
}

header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Transfer-Encoding: binary");

if ($_GET['code'] == 'sample') {

	$sample_code = $component['sample_code'];

	if ($component['type'] == 'map') {
		$map_link = "/generate.js.php?id=".$_GET['id'];
		$sample_code = str_replace("@path", "'".$map_link."'", $sample_code) ;
	}

	header("Content-Length: ".strlen($sample_code));
	echo $sample_code;
} else {
	header("Content-Length: ".strlen($component['component_code']));
	echo $component['component_code'];
}

?>
