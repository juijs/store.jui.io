<?php

//if( !array_key_exists('HTTP_REFERER', $_SERVER) ) exit('No direct script access allowed');
include_once "../../../../bootstrap.php";
include_once "common.php"; 

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
$file = realpath(rawurldecode($root .'/'.$file ));

//var_dump($file);

if (!file_exists($file)) {
	header("HTTP/1.0 404 Not Found");
	exit('No direct script access allowed');
}

// preprocessor 
$contentType = get_mime_type($file);
header('Content-Type: '.$contentType);
readfile($file);
