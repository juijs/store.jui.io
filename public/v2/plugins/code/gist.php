<?php

include_once V2_PLUGIN."/code/common.php";

//var_dump($arr, $id, $filename);
$root = REPOSITORY.DIRECTORY_SEPARATOR.$id;
$file = (isset($filename) ? $filename: null );
$file = rawurldecode($root .'/'.$file );

if (!file_exists($file)) {
	header("HTTP/1.0 404 Not Found");
	exit('No direct script access allowed');
}

function convert_code($text) {
	$text = addslashes($text);
	$text = str_replace("\n", "\\n", $text);
	$text = str_replace("\r", "", $text);

	return $text;
}

function gist_template  ($content) {
	global $id, $filename;
	ob_start();
	include_once "gist_template.php";
	$text = ob_get_contents();
	ob_end_clean();

	return $text; 

}

function gist_template_for_image  () {
	global $id, $filename;
	ob_start();
	include_once "gist_template_for_image.php";
	$text = ob_get_contents();
	ob_end_clean();

	return $text; 

}

function gist_template_for_preprocess  () {
	global $id, $filename, $file;
	$ext = get_ext($file);
	ob_start();
	include_once V2_PLUGIN."/code/gist/{$ext}.php";
	$text = ob_get_contents();
	ob_end_clean();

	return $text; 

}

echo "document.write('". convert_code("<link rel='stylesheet' href='//store.jui.io/v2/css/gist.css' />"). "');";
echo PHP_EOL;

if (is_image_type($file)) {
	echo "document.write('".convert_code(gist_template_for_image())."')";
} else if (is_preprocess_type($file)) { 
	echo "document.write('".convert_code(gist_template_for_preprocess())."')";
} else {
	$ext = get_ext($file);

	$hl = new Highlight\Highlighter();
	$r = $hl->highlight($ext, file_get_contents($file));

	echo "document.write('". convert_code("<link rel='stylesheet' href='//store.jui.io/bower_components/reveal-highlight-themes/styles/tomorrow.css' />"). "');";
	echo PHP_EOL;
	echo "document.write('".convert_code(gist_template($r->value))."')";
}

?>