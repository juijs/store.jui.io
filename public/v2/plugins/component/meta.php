<?php
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
	if ($page_id != 'generate.ui') {
		$metaList[] = 	'<link href="//store.jui.io/jui-all/jui/dist/ui.min.css" rel="stylesheet" />';
		$metaList[] = 	'<link href="//store.jui.io/jui-all/jui-grid/dist/grid.min.css" rel="stylesheet" />';
		$metaList[] = 	'<link href="//store.jui.io/jui-all/jui/dist/ui-jennifer.min.css" rel="stylesheet" />';
		$metaList[] = 	'<link href="//store.jui.io/jui-all/jui-grid/dist/grid-jennifer.min.css" rel="stylesheet" />';
	} 

	$metaList[] = 	'<script type="text/javascript" src="//store.jui.io/jui-all/jui-core/dist/core.min.js"></script>';
	$metaList[] = 	'<script type="text/javascript" src="//store.jui.io/jui-all/jui/dist/ui.min.js"></script>';
	$metaList[] = 	'<script type="text/javascript" src="//store.jui.io/jui-all/jui-grid/dist/grid.min.js"></script>';
	$metaList[] = 	'<script type="text/javascript" src="//store.jui.io/jui-all/jui-chart/dist/chart.js"></script>';
}


if ($page_id == 'editor') {

	$metaList[] = 	'<link href="'.V2_PLUGIN_URL.'/component/resource/editor.css" rel="stylesheet" />';


	$metaList[] = 	'<link href="/bower_components/codemirror/lib/codemirror.css" rel="stylesheet" />';
	$metaList[] = 	'<script type="text/javascript" src="/bower_components/codemirror/lib/codemirror.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/coffeescript/coffeescript.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/javascript/javascript.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/htmlmixed/htmlmixed.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/markdown/markdown.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/jade/jade.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/haml/haml.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/xml/xml.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/css/css.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/sass/sass.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/mode/stylus/stylus.js"></script>';

	$metaList[] = 	'<script src="/bower_components/codemirror/addon/dialog/dialog.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/addon/search/search.js"></script>';
	$metaList[] = 	'<script src="/bower_components/codemirror/addon/search/searchcursor.js"></script>';

	$metaList[] = 	'<link href="/bower_components/codemirror/addon/dialog/dialog.css" rel="stylesheet" />';
	$metaList[] = 	'	<script src="/bower_components/html.sortable/dist/html.sortable.js"></script>';

}

?>