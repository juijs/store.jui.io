<?php include_once '../bootstrap.php' ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>Embeded - Store for JUI</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<script type="text/javascript" src="/bower_components/codemirror/lib/codemirror.js"></script>
	<link href="/bower_components/codemirror/lib/codemirror.css" rel="stylesheet" />
	<script src="/bower_components/codemirror/mode/javascript/javascript.js"></script>

	<?php if (!$_GET['only']) { ?>
	<script src="/bower_components/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="/bower_components/codemirror/mode/xml/xml.js"></script>
	<script src="/bower_components/codemirror/mode/css/css.js"></script>
	<?php } ?>

	<link href="/bower_components/codemirror/theme/twilight.css" rel="stylesheet" />
    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
	<link href="/jui/dist/jui.min.css" rel="stylesheet" />
	<link href="/jui/dist/jennifer.theme.min.css" rel="stylesheet" />
	<link href="/css/embed.css" rel="stylesheet" />
	<script type="text/javascript" src="/jui/dist/jui.min.js"></script>
	<?php echo $meta ?>
</head>
<body class="jui">
