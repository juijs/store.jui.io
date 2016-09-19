<?php 

// Stylus Preprocessor 
//
header('Content-Type: text/css');
$stylus = new \Stylus\Stylus();
$stylus->setReadDir($dir);
$stylus->setImportDir($dir);
$content = $stylus->fromFile($relative_path)->toString();
echo $content;

