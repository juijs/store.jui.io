<?php

// Less Preprocessor 

header('Content-Type: text/css');

if (!hasCache($file)) {
	$parser = new Less_Parser(array('compress' => false));
	$parser->parseFile( $file, $url_root );

	generateCache($file, $parser->getCss());
}

outputCache($file);

