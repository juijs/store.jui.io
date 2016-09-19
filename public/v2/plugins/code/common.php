<?php

$support_file_type = array(
	'txt', 
	'html', 
	'jade', 
	'markdown', 
	'md', 
	'css', 
	'scss', 
	'less', 
	'styl', 
	'coffee', 
	'dart', 
	'ts',
	'json',
	'isbn',
	'qrcode',
	'barcode'
);

function get_ext($filename) {
	$file = basename($filename);
	$arr = explode(".", $file);
	$ext  = array_pop($arr);

	return strtolower($ext);
}

function is_support_type($filename) {
	global $support_file_type; 

	$ext = get_ext($filename);
	$is_support  = in_array($ext, $support_file_type);

	return $is_support; 
}

function code_preprocessor($id, $file, $url_root) {
	$ext = get_ext($file);

	$dir = REPOSITORY.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
	$relative_path = str_replace($dir, "", $file);

	include V2_PLUGIN."/code/preprocessor/$ext.php";
}

