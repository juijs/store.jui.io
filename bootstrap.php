<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
session_start();

define("STORE", "STORE");
define("ROOT", __DIR__);
define("REPOSITORY", ROOT."/repository");
define("CACHE", ROOT."/cache");
define("RESOURCES", ROOT."/resources");

define("ABSPATH", ROOT."/public");
define("INC", ABSPATH."/include");
define("PLUGIN", ABSPATH."/plugins");
define("PLUGIN_URL", "/plugins");

$_protocol = $_SERVER['SERVER_PROTOCOL'];
$_domain     = $_SERVER['HTTP_HOST'];
$_protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

//var_dump($_SERVER);
define("IS_HTTPS", $_protocol == 'https');

$url_root =  "//".$_domain;
define("URL_ROOT", $url_root);

define("V2", ROOT."/public/v2");
define("V2_INC", ROOT."/public/v2/include");

define("V2_URL", URL_ROOT."/v2");
define("V2_PLUGIN", ROOT."/public/v2/plugins");
define("V2_PLUGIN_URL", V2_URL."/plugins");

require_once ROOT."/vendor/autoload.php";

$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');

require_once ROOT."/init.php";

$type_text = array(
	//'static' => 'Static',
	'pr' => 'Presentation',
	'page' => 'Page',
	'component' => 'Module',
	'theme' => 'Chart',
	'style' => 'UI',
	'map' => 'Map',
	'data' => 'Data',
	'code' => 'Code'
);

function HtmlPreprocessor($content, $type) {
	if ($type == 'markdown') {
		$Parsedown = new ParsedownExtra();
		$content = $Parsedown->text($content);
	} else if ($type == 'jade') {
		$jade = new \Jade\Jade();
		$content = $jade->render($content);
	} else if ($type == 'haml') {


	} else if ($type == 'html') {

	}

	return $content;
}

function JavascriptPreprocessor($content, $type) {
	if ($type == 'javascript') {

	} else if ($type == 'coffeescript') {

	} else if ($type == 'typescript') {

	}

	return $content;
}

function CssPreprocessor($content, $type) {
	if ($type == 'css') {

	} else if ($type == 'less') {
		try {
			$parser = new Less_Parser();
			$parser->parse($content);
			$content = $parser->getCss();
		} catch (Exception $e)  {
			var_dump($e->getMessage());
		}

	} else if ($type == 'sass') {
		$scss = new scssc();
		$content = $scss->compile($content);
	} else if ($type == 'stylus') {
		$stylus = new Stylus();
		$content = $stylus->fromString($content)->toString();
	}

	return $content;
}

$detect = new Mobile_Detect;

require_once ROOT."/mime_types.php";
function get_mime_type($file) {

    global $mime_types;

    $ext = end(explode('.', $file));

    if(array_key_exists($ext, $mime_types)){ 
            return $mime_types[$ext][0]; 
    }else { 
            return 'application/octet-stream'; 
    }
}
?>
