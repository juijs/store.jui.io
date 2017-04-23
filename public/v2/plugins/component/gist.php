<?php

include_once V2_PLUGIN."/component/common.php";

//var_dump($arr, $id, $filename);
$root = REPOSITORY.DIRECTORY_SEPARATOR.$id;
$file = (isset($filename) ? $filename: null );

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

echo "document.write('". convert_code("<link rel='stylesheet' href='{$V2_URL}/v2/css/gist.css' />"). "');";
echo PHP_EOL;

$ext = get_ext($file);
$content = ''; 

$arr = explode(',', $row['preprocessor']);


if ($filename == 'html') {
    $ext = $arr[0];
    $content = $row['html_code'];
} else if ($filename == 'module')  { 
    $content = $row['component_code'];
    $ext = 'js';
} else if ($filename == 'js') {
     $content = $row['sample_code'];
    $ext = $arr[1];
} else if ($filename == 'css') { 
    $content = $row['css_code'];
    $ext = $arr[2];
}

$hl = new Highlight\Highlighter();
$r = $hl->highlight($ext, $content);

echo "document.write('". convert_code("<link rel='stylesheet' href='//store.jui.io/bower_components/reveal-highlight-themes/styles/tomorrow.css' />"). "');";
echo PHP_EOL;
echo "document.write('".convert_code(gist_template($r->value))."')";

?>
