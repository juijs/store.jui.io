<?php 

$type_list = array(
	'code' => array('img' => 'code', 'name' => 'Code', 'link' => '/v2/create.php?type=code'),
	'pr' => array('img' => 'ppt', 'name' => 'Presentation'),
	'page' => array('img' => 'page', 'name' => 'Page'),
	'component' => array('img' => 'module', 'name' => 'Module'),
	'theme' => array('img' => 'chart', 'name' => 'Chart Theme'),
	'style' => array('img' => 'ui', 'name' => 'UI Theme'),
	'map' => array('img' => 'map', 'name' => 'Map'),
);

$theme = $_GET['theme'] ? $_GET['theme'] : 'flat';

function get_svg_image($name) {
	return file_get_contents(ABSPATH.'/v2/images/main/'.$name.'.svg');
}

function get_good_rate ($good_count, $r = 75) {
	if ($good_count <= 10) {
			$good_type = 1; 
			$good_max = 10;

			$good_rate = ($good_count) / 100;

		} else if ($good_count <= 20) {
			$good_type = 2; 
			$good_max = 20;

			$good_rate = ($good_max - $good_count) / 100;

		} else if ($good_count <= 30) {
			$good_type = 3; 
			$good_max = 30;

			$good_rate = ($good_max - $good_count) / 100;

		} else if ($good_count <= 50) { 	
			$good_type = 4; 
			$good_max = 50;

			$good_rate = ($good_max - $good_count) / 100;

		} else if ($good_count <= 100) {
			$good_type = 5; 
			$good_max = 100;

			$good_rate = ($good_max - $good_count) / 100;

		} else {
			$good_type = 6; 
			$good_max = 200;

			$good_rate = ($good_max - $good_count) / 100;
		}

		$maxLength = $r * M_PI  * 2;
		$fill = $good_rate * $maxLength;
		$empty = $maxLength - $fill;
	
		return array('rate' => $good_rate, 'fill' => $fill, 'empty' => $empty ); 
}
?>