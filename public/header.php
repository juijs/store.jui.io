<?php include_once '../bootstrap.php' ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Store for JUI Chart</title>
	<link href="/css/main.css" rel="stylesheet" />
	<link href="/css/edit.css" rel="stylesheet" />


	<?php if ($page_id == 'manager' || $page_id == 'view' || $page_id == 'theme' || $page_id == 'style') { ?>
	<script type="text/javascript" src="/bower_components/codemirror/lib/codemirror.js"></script>
	<link href="/bower_components/codemirror/lib/codemirror.css" rel="stylesheet" />
	<script src="/bower_components/codemirror/mode/javascript/javascript.js"></script>
	<link href="/bower_components/codemirror/theme/twilight.css" rel="stylesheet" />
	<?php } ?>

    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>
	<link href="/bower_components/jui/jui.min.css" rel="stylesheet" />
	<script type="text/javascript" src="/bower_components/jui/jui.min.js"></script>
	<script src="/bower_components/tinyColorPicker/colors.js"></script>
	<script src="/bower_components/tinyColorPicker/jqColorPicker.js"></script>
	<link href="/bower_components/ionrangeslider/css/ion.rangeSlider.css" rel="stylesheet" />
	<link href="/bower_components/ionrangeslider/css/ion.rangeSlider.skinNice.css" rel="stylesheet" />
	<script src="/bower_components/ionrangeslider/js/ion.rangeSlider.js"></script>
	<?php echo $meta ?>
</head>
<body class="jui">
<?php if ($page_id != 'generate') { ?>
<?php include_once "nav.php" ?>
<?php } ?>
