<?php include_once '../bootstrap.php' ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Store for JUI</title>
	<?php if ($type_text[$page_id]) { ?>
	<script type="text/javascript" src="/bower_components/codemirror/lib/codemirror.js"></script>
	<link href="/bower_components/codemirror/lib/codemirror.css" rel="stylesheet" />
	<script src="/bower_components/codemirror/mode/javascript/javascript.js"></script>
	<script src="/bower_components/codemirror/mode/css/css.js"></script>
	<link href="/bower_components/codemirror/theme/twilight.css" rel="stylesheet" />
	<?php } ?>

    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>
	
	<?php if ($page_id != 'generate.ui') { ?>
	<link href="/bower_components/jui/jui.min.css" rel="stylesheet" />
	<?php } ?>
	<link href="/css/main.css" rel="stylesheet" />

	<?php if ($type_text[$page_id]) { ?>
	<link href="/css/edit.css" rel="stylesheet" />
	<?php } ?>

	<script type="text/javascript" src="/bower_components/jui/jui.js"></script>
<?php if ($page_id != 'generate' && $page_id != 'generate.ui') { ?>
	<script src="/bower_components/tinyColorPicker/colors.js"></script>
	<script src="/bower_components/tinyColorPicker/jqColorPicker.js"></script>
	<link href="/bower_components/ionrangeslider/css/ion.rangeSlider.css" rel="stylesheet" />
	<link href="/bower_components/ionrangeslider/css/ion.rangeSlider.skinNice.css" rel="stylesheet" />
	<script src="/bower_components/ionrangeslider/js/ion.rangeSlider.js"></script>
	<script src="/bower_components/masonry/dist/masonry.pkgd.js"></script>
<?php } ?>
	<?php echo $meta ?>
</head>
<body class="jui">
<?php if ($page_id != 'generate' && $page_id != 'generate.ui') { ?>
<?php include_once "nav.php" ?>
<?php } ?>
