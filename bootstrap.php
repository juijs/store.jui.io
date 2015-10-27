<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
session_start();

define("ROOT", __DIR__);
define("INC", ROOT."/public/inc");

require_once ROOT."/vendor/autoload.php";

$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');

require_once ROOT."/init.php";

$type_text = array(
	'component' => 'Module',
	'theme' => 'Chart Theme',
	'style' => 'UI Theme',
	'map' => 'Map',
	'data' => 'Data'
);

function HtmlPreprocessor($content, $type) {
	if ($type == 'markdown') {
		$Parsedown = new Parsedown();
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

?>
