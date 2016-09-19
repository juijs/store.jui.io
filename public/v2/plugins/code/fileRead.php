<?php

//if( !array_key_exists('HTTP_REFERER', $_SERVER) ) exit('No direct script access allowed');
include_once "../../../../bootstrap.php";
include_once "common.php"; 

$is_preprocessor = ($_REQUEST['preprocessor'] == 'true');
$file = $_REQUEST['file'];

if (!$file) {
	header("HTTP/1.0 404 Not Found");
	exit('No direct script access allowed');
}

$arr = explode('/', $file);
$id = array_shift($arr);
$filename = implode("/", $arr);

//var_dump($arr, $id, $filename);
$root = REPOSITORY;
$file = (isset($file) ? $file : null );
$file = rawurldecode($root .'/'.$file );

//var_dump($file);

if (!file_exists($file)) {
	header("HTTP/1.0 404 Not Found");
	exit('No direct script access allowed');
}

// preprocessor 
if ($is_preprocessor) {
	code_preprocessor($id, $file, '/v2/code/'.$id.'/');
} else {
	header('Content-Type: text/plain');
	readfile($file);
}
