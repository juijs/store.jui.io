<?php

header('Content-Type: text/javascript');

if (!hasCache($file)) {
	$cache_file = getCache($file);
	system("tsc --outFile $cache_file $file");
	touchCache($file);
}

outputCache($file);
