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
	'js',
	'json',

	// special extention 
	'isbn',
	'qrcode',
	'barcode',
	'color',
);

function get_ext($filename) {
	$file = basename($filename);
	$arr = explode(".", $file);
	$ext  = end($arr);

	return strtolower($ext);
}

function is_support_type($filename) {
	global $support_file_type; 

	$ext = get_ext($filename);
	$is_support  = in_array($ext, $support_file_type);

	return $is_support; 
}

function is_image_type($filename) {
	$ext = get_ext($filename);

	$is_image = ($ext == 'gif' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp');

	return $is_image;
}

function getCache($file) {
	return str_replace(REPOSITORY, CACHE, $file);
}

function outputCache($file) {
	readfile(getCache($file));
}

function hasCache($file) {
	$cache_file = getCache($file);

	// 캐쉬 파일이 없으면 
	if (!file_exists($cache_file)) return false; 

	// 캐쉬 파일이 있는데 수정시간이 다르면  
	if (filemtime($file) != filemtime($cache_file)) {
		return false; 
	}

	return true; 
}

function touchCache($file) {
	$cache_file = getCache($file);

	chmod($cache_file, 0666);
	touch($cache_file, filemtime($file));
}

function generateCache($file, $content) {
	$cache_file = getCache($file);

	$dir = dirname($cache_file);
	mkdir($dir, 0777, true);

	file_put_contents($cache_file, $content);
	touchCache($file);
}

function code_preprocessor($id, $file, $url_root) {
	$ext = get_ext($file);

	$dir = REPOSITORY.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
	$relative_path = str_replace($dir, "", $file);
	$is_new = isset($_GET['t']);

	include V2_PLUGIN."/code/preprocessor/$ext.php";
}

