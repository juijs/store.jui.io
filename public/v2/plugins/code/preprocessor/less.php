<?php

// Less Preprocessor 

header('Content-Type: text/css');
$parser = new Less_Parser(array(
	'cache_dir' => $dir.DIRECTORY_SEPARATOR.".less_cache"
));
$parser->parseFile( $file, $url_root );

echo $parser->getCss();