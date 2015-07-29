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

?>
