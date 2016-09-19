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

	<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>
	
	
	<link href="/css/flat.css" rel="stylesheet" />
</head>
<body class="jui <?php echo $body_class ?>">
<?php if ($page_id != 'generate' && $page_id != 'generate.ui') { ?>
<?php include_once "nav.php" ?>
<?php } ?>
