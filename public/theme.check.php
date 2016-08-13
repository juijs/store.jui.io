<?php $page_id = "generate"; ?>
<?php 

include_once "include/generate.meta.php";
$meta = implode(PHP_EOL, $metaList);
include_once "header.php";

?>
<script>if (define) { define.amd = true; }</script>

<script type="text/javascript">
<?php echo $_POST['component_code'] ?>

jui.ready(function() { 
	// 테마 설정 
	var obj = jui.include("chart.theme.<?php echo $_POST['theme_name'] ?>");

	for(var key in obj) {
		var arr = key.split(/[A-Z]+/);

		obj[key] = { value : obj[key], group : arr[0] };
	}

	parent.setThemeObject(obj);
});

</script>


<?php include_once "footer.php" ?>
