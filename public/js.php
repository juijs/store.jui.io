<?php 
/**
 * js module load 
 *
 * 
 * 
 */

include_once '../bootstrap.php';



$id = $_GET['id'];

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$rows = $components->find(array(
	'name' => $_GET['id']
));

foreach($rows as $row) {
	echo print_comment($row);
	echo print_minified($row), "\r\n";
}

function print_minified($data) {
	
	$name = $data['name'];
	$minified = $_GET['minified'] ;//$data['minified'];

	$code = $data['component_code'];

	if ($minified) {
		// Basic (default) usage.
		$code = \JShrink\Minifier::minify($code);
	}

	return $code;
}

function print_comment($data) {
	$arr = array();

	$arr[] = $data['title'];
	$arr[] = "";
	$arr[] = $data['description'];
	$arr[] = "";
	$arr[] = "@author ".$data['username'];

	
	$all_string = implode("\r\n", $arr);

	$arr = explode("\n", $all_string);

	$result = "/**\r\n";
	foreach ($arr as $str) {
		$str = str_replace('\r', '', $str);
		$result .= " * ".$str."\r\n";
	}

	$result .= " */";


	return print_license($data['license'])."\r\n".$result."\r\n";
}

function print_license($license) {
	return $license;
}

?>
