<?php $page_id = "generate"; ?>
<?php 
error_reporting(E_ALL);

include_once "../../../../bootstrap.php";

header('X-XSS-Protection: 0');

$data = $_POST;

include_once V2_PLUGIN."/component/meta.php";

$meta = implode(PHP_EOL, $metaList);

$map_link = "data:image/svg+xml;base64,".base64_encode($_POST['component_code']);
$sample_code = str_replace("@path", "'".$map_link."'", $_POST['sample_code']) ;


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
<?php include_once V2_INC."/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

</style>
<?php echo $_POST['html_code'] ?>

<script type="text/javascript">
/** sample - start */
(function() {
	<?php echo $sample_code ?>
})();
/** sample - end */
</script>

</body>
</html>
