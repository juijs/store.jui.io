<?php


function directoryList($path, $omit = '') {
	$arr = scandir($path);
	
	$list = array();
	foreach($arr as $file) {

		if ($file == '.' || $file == '..') continue;

		$real_path = $path.DIRECTORY_SEPARATOR.$file;

		$real_name = str_replace($omit, "", $real_path);

		if (is_dir($real_path)) {
			$list[] = array(
				'is_dir' => true,
				'name' => str_replace(".php", "", $file),
				'list' => directoryList($real_path, $omit),
				'path' => $real_name
			);
		} else {
			$list[] = array(
				'is_dir' => false,
				'name' => str_replace(".php", "", $file),
				'path' => $real_name
			);
		}
	}


	usort($list, function($a, $b) { 
		if ($a['list'] && !$b['list']) {
			return -1;
		} else if (!$a['list'] && $b['list']) {
			return 1;
		}

		return $a['name'] < $b['name'] ? -1 : 1;
	}); 

	return $list;
}

echo json_encode(directoryList("frameworks", "frameworks/"));

?>
