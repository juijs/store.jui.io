<?php $page_id = "generate"; ?>
<?php 

header('X-XSS-Protection: 0');

$data = $_POST;

include_once "include/generate.meta.php";

$metaList[] = "<script>define.amd=true;</script>";
$meta = implode(PHP_EOL, $metaList);


include_once "header.php";

include_once "include/preprocessor.php";

?>
<?php include_once "include/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
	height:100%;
}

#embedResult {
    position:absolute;
    width:100%;
    height:100%;
    
}
</style>

<?php if ($data['type'] == 'component') { ?>
<?php echo $data['html_code'] ?>

<?php } else {?>
<div id="embedResult"></div>
<?php } ?>
<style type="text/css">
<?php echo $data['css_code'] ?>
</style>
<script type="text/javascript">
/** component - start */
<?php echo $data['component_code'] ?>
/** component - end */

/** sample - start */
<?php echo $data['sample_code'] ?>
/** sample - end */

jui.ready(function() { 

	// 테마 설정 
	var theme = '<?php echo $data['name'] ?>';
	if ('<?php echo $data['type'] ?>' == 'theme') {
		var obj = $("#embedResult")[0].jui;
		if (obj) {
			obj.setTheme(theme);
		}
	}
});

</script>


</body>
</html>
