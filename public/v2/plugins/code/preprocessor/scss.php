<?php

// SCSS Preprocessor 

header('Content-Type: text/css');
$scss = new scssc();
$scss->setFormatter("scss_formatter");
$scss->setImportPaths($dir);

$server = new scss_server($dir, $dir.DIRECTORY_SEPARATOR.".scss_cache", $scss);
$_GET['p']  = $relative_path;
$server->serve();

