<?php

function get_ext($filename) {
	$file = basename($filename);
	$arr = explode(".", $file);

	if (sizeof($arr) > 1) {
		$ext  = end($arr);
	} else {
		$ext = 'default'; // none extension , default
	}

	return strtolower($ext);
}
