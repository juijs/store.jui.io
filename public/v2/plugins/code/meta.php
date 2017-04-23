<?php

$has_toolbar = true; 
$has_statusbar = true; 

// 리소스가 있다면 
if (trim($data['resources']) != "") {

	$arr = explode(",", $data['resources']);
	$metaList = array();
	foreach($arr as $val) {

		$val = trim($val);
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
} else {
	// 리소스가 없을 때는 JUI 를 기본 
	$metaList[] = 	'<link href="'.$url_root.'/jui-all/jui/dist/ui.min.css" rel="stylesheet" />';
	$metaList[] = 	'<link href="'.$url_root.'/jui-all/jui-grid/dist/grid.min.css" rel="stylesheet" />';
	$metaList[] = 	'<link href="'.$url_root.'/jui-all/jui/dist/ui-jennifer.min.css" rel="stylesheet" />';
	$metaList[] = 	'<link href="'.$url_root.'/jui-all/jui-grid/dist/grid-jennifer.min.css" rel="stylesheet" />';

	$metaList[] = 	'<script type="text/javascript" src="'.$url_root.'/jui-all/jui-core/dist/core.js"></script>';
	$metaList[] = 	'<script type="text/javascript" src="'.$url_root.'/jui-all/jui/dist/ui.js"></script>';
	$metaList[] = 	'<script type="text/javascript" src="'.$url_root.'/jui-all/jui-grid/dist/grid.min.js"></script>';
	$metaList[] = 	'<script type="text/javascript" src="'.$url_root.'/jui-all/jui-chart/dist/chart.js"></script>';
}

if ($page_id == 'create') {

	$metaList[] = 	'<link href="'.V2_PLUGIN_URL.'/code/resource/main.css" rel="stylesheet" />';
}

if ($page_id == 'editor') {

	$metaList[] = 	'<link href="'.V2_PLUGIN_URL.'/code/resource/editor.css" rel="stylesheet" />';


	$metaList[] = 	'<link href="'.$url_root.'/bower_components/codemirror/lib/codemirror.css" rel="stylesheet" />';
	$metaList[] = 	'<script type="text/javascript" src="'.$url_root.'/bower_components/codemirror/lib/codemirror.js"></script>';
	$metaList[] = 	'<link href="'.$url_root.'/bower_components/codemirror-colorpicker/addon/colorpicker/colorpicker.css" rel="stylesheet" />';
	$metaList[] = 	'<script type="text/javascript" src="'.$url_root.'/bower_components/codemirror-colorpicker/addon/colorpicker/colorview.js"></script>';
	$metaList[] = 	'<script type="text/javascript" src="'.$url_root.'/bower_components/codemirror-colorpicker/addon/colorpicker/colorpicker.js"></script>';

	$metaList[] = 	'<script src="'.$url_root.'/bower_components/codemirror/addon/mode/loadmode.js"></script>';
	$metaList[] = 	'<script src="'.$url_root.'/bower_components/codemirror/mode/meta.js"></script>';

	$metaList[] = 	'<link href="'.$url_root.'/bower_components/jqueryfiletree/dist/jQueryFileTree.min.css" rel="stylesheet" />';
	$metaList[] = 	'<script src="'.$url_root.'/bower_components/jqueryfiletree/dist/jQueryFileTree.min.js"></script>';
	$metaList[] = 	'<script src="'.$url_root.'/bower_components/html.sortable/dist/html.sortable.js"></script>';
	$metaList[] = 	'<script src="'.$url_root.'/bower_components/caman/dist/caman.full.min.js"></script>';

}
?>
