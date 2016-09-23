<?php

//if( !array_key_exists('HTTP_REFERER', $_SERVER) ) exit('No direct script access allowed');
include_once "../../../../bootstrap.php";
include_once "common.php"; 

$ext = $_REQUEST['ext'];

$dir = ABSPATH.'/v2/plugins/code/sample/'.$ext;

if (!$dir) {
	header("HTTP/1.0 404 Not Found");
	exit('No direct script access allowed');
}

if (!file_exists($dir)) {
	header("HTTP/1.0 404 Not Found");
	exit('No direct script access allowed');
}


$files		= preg_grep('/^([^.])/', scandir($dir));

natcasesort($files);

$temp = array();

foreach($files as $file) {
	$temp[] = $file;
}

echo json_encode($temp);
