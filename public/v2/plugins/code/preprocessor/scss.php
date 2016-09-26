<?php

// SCSS Preprocessor 

header('Content-Type: text/css');

if (!hasCache($file) || $is_new) {
	$scss = new scssc();
	$scss->setFormatter("scss_formatter");
	$scss->setImportPaths($dir);

	generateCache($file, $scss->compile(file_get_contents($file)));
}

outputCache($file);



