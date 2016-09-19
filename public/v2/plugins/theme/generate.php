<?php $page_id = "generate"; ?>
<?php 
error_reporting(E_ALL);

include_once "../../../../bootstrap.php";

header('X-XSS-Protection: 0');

$data = $_POST;

include_once V2_PLUGIN."/component/meta.php";

$meta = implode(PHP_EOL, $metaList);

$sample_path = ABSPATH.'/sample/'.$data['sample_code'].".js";
if (file_exists($sample_path)) {
	$data['sample_code']  = file_get_contents($sample_path);
}

$data['sample_code'] = str_replace("#chart-content", "#embedResult", $data['sample_code']);
$data['sample_code'] = str_replace("#chart", "#embedResult", $data['sample_code']);

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

include_once V2_INC."/preprocessor.php";

?>
<?php include_once V2_INC."/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

#embedResult {
	left:0px;
	top:0px;
	width:100%;
	height:100%;
}

.maxArea {
	position:absolute;
	left:0px;
	top:0px;
	right:0px;
	bottom:0px;
}

</style>
<div class="maxArea">
<div id="embedResult"></div>
</div>

<script type="text/javascript">

(function() {
/** component - start */
<?php echo $data['component_code'] ?>
/** component - end */
})();


(function() { 
/** sample - start */
<?php echo $data['sample_code'] ?>
/** sample - end */

})();

jui.ready(function() { 

	// 테마 설정 
	var obj = $("#embedResult")[0].jui;
	if (obj) {
		obj.setTheme("CustomeTheme");
	}
});

</script>


</body>
</html>
