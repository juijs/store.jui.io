<?php $page_id = "generate"; ?>
<?php 

header('X-XSS-Protection: 0');

$arr = explode(",", $_POST['resources']);
$metaList = array();
foreach($arr as $val) {
	$ext = strtolower(array_pop(explode(".", $val)));

	if ($ext == 'css') {
		$metaList[] = "<link rel='stylesheet' href='".$val."' />";
	} else {
		$metaList[] = "<script type='text/javascript' src='".$val."'></script>";
	}
}

$metaList[] = "<script>define.amd=true;</script>";
$meta = implode(PHP_EOL, $metaList);

include_once "header.php";

?>
<?php include_once "include/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

#embedResult {
    position:absolute;
    width:100%;
    height:100%;
    
}
</style>

<?php if ($_POST['type'] == 'component') { ?>
<?php echo $_POST['html_code'] ?>

<?php } else {?>
<div id="embedResult"></div>
<?php } ?>
<script type="text/javascript">
/** component - start */
<?php echo $_POST['component_code'] ?>
/** component - end */

/** sample - start */
<?php echo $_POST['sample_code'] ?>
/** sample - end */

jui.ready(function() { 

	// 테마 설정 
	var theme = '<?php echo $_POST['name'] ?>';
	if ('<?php echo $_POST['type'] ?>' == 'theme') {
		var obj = $("#embedResult")[0].jui;
		if (obj) {
			obj.setTheme(theme);
		}
	}
});

</script>


</body>
</html>
