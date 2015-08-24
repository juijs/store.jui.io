<?php
$arr = explode(",", $data['resources']);
$metaList = array();
foreach($arr as $val) {

	$path = "frameworks/{$val}.php";
	if (file_exists($path)) {
		$metaList[] = file_get_contents($path);
		continue;
	} 
	$ext = strtolower(array_pop(explode(".", $val)));

	if ($ext == 'css') {
		$metaList[] = "<link rel='stylesheet' href='".$val."' />";
	} else {
		$metaList[] = "<script type='text/javascript' src='".$val."'></script>";
	}
}

?>