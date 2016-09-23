<?php 

// Stylus Preprocessor 
//
header('Content-Type: text/css');

if (!hasCache($file)) {
	$stylus = new \Stylus\Stylus();
	$stylus->setReadDir($dir);
	$stylus->setImportDir($dir);
	$content = $stylus->fromFile($relative_path)->toString();

	generateCache($file, $content);
}

outputCache($file);


