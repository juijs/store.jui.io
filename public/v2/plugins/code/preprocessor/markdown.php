<?php

/**
 * $file : file full path 
 * $dir :  root directory 
 * $id : repository id 
 * $cache_file : cache file path 
 * $url_root : url based root path 
 * $relative_path : relative path of file 
 * 
 */

header('Content-Type: text/html');

if (!hasCache($file) || $is_new) {
	generateCache($file, HtmlPreprocessor(file_get_contents($file), 'markdown'));
}

outputCache($file);
