<?php

/**
 * $file : file full path 
 * $dir :  root directory 
 * $id : repository id 
 * $url_root : url based root path 
 * $relative_path : relative path of file 
 * 
 */

header('Content-Type: text/html');
echo HtmlPreprocessor(file_get_contents($file), 'markdown');

