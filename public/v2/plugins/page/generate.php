<?php $page_id = "generate"; ?>
<?php 

include_once "../../../../bootstrap.php";

header('X-XSS-Protection: 0');

$data = $_POST;

include_once V2_PLUGIN."/page/meta.php";

$meta = implode(PHP_EOL, $metaList);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>Embeded - Store for JUI</title>
	<link rel="shortcut icon" href="//store.jui.io/favicon.ico" type="image/x-icon">
	<link rel="icon" href="//store.jui.io/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="//store.jui.io/bower_components/jquery/dist/jquery.min.js"></script>
	<link href="//store.jui.io/css/embed.css" rel="stylesheet" />
	<?php echo $meta ?>
</head>
<body class="jui">
<?php

include_once INC."/preprocessor.php";

?>
<?php include_once INC."/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

</style>
<?php echo $data['html_code'] ?>

<style type="text/css">
<?php echo $data['css_code'] ?>
</style>
<script type="text/javascript">

/** sample - start */
(function() { 
<?php echo $data['sample_code'] ?>
})();
/** sample - end */
</script>


</body>
</html>
