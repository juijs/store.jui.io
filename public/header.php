<?php include_once '../bootstrap.php' ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>JENNIFER UI: Store</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>

	<?php if ($type_text[$page_id]) { ?>
	<link href="/css/edit.css" rel="stylesheet" />
	<?php } ?>


	<?php echo $meta ?>
	<?php if ($type_text[$page_id]) { ?>
	<link href="/bower_components/codemirror/lib/codemirror.css" rel="stylesheet" />
	<script type="text/javascript" src="/bower_components/codemirror/lib/codemirror.js"></script>
	<script src="/bower_components/codemirror/mode/coffeescript/coffeescript.js"></script>
	<script src="/bower_components/codemirror/mode/javascript/javascript.js"></script>
	<script src="/bower_components/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="/bower_components/codemirror/mode/markdown/markdown.js"></script>
	<script src="/bower_components/codemirror/mode/jade/jade.js"></script>
	<script src="/bower_components/codemirror/mode/haml/haml.js"></script>
	<script src="/bower_components/codemirror/mode/xml/xml.js"></script>
	<script src="/bower_components/codemirror/mode/css/css.js"></script>
	<script src="/bower_components/codemirror/mode/sass/sass.js"></script>
	<script src="/bower_components/codemirror/mode/stylus/stylus.js"></script>

	<script src="/bower_components/codemirror/addon/dialog/dialog.js"></script>
	<script src="/bower_components/codemirror/addon/search/search.js"></script>
	<script src="/bower_components/codemirror/addon/search/searchcursor.js"></script>

	<link href="/bower_components/codemirror/addon/dialog/dialog.css" rel="stylesheet" />
	<link href="/bower_components/codemirror/theme/twilight.css" rel="stylesheet" />
	<?php } ?>


	<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>
	
	
	<link href="/css/main.css" rel="stylesheet" />
	<?php if ($page_id != 'generate' && $page_id != 'generate.ui' && $page_id != 'view'  && $page_id != 'list' && $page_id != 'mylist'  ) { ?>
	<script src="/bower_components/tinyColorPicker/colors.js"></script>
	<script src="/bower_components/tinyColorPicker/jqColorPicker.js"></script>
	<link href="/bower_components/ionrangeslider/css/ion.rangeSlider.css" rel="stylesheet" />
	<link href="/bower_components/ionrangeslider/css/ion.rangeSlider.skinNice.css" rel="stylesheet" />
	<script src="/bower_components/ionrangeslider/js/ion.rangeSlider.js"></script>
	<script src="/bower_components/html.sortable/dist/html.sortable.js"></script>
	<?php } else if ($page_id == 'list' || $page_id == 'mylist') { ?>
	<script src="/bower_components/masonry/dist/masonry.pkgd.js"></script>
	<?php } ?>

</head>
<body class="jui <?php echo $body_class ?>">
<?php if ($page_id != 'generate' && $page_id != 'generate.ui') { ?>
<?php include_once "nav.php" ?>
<?php } ?>
