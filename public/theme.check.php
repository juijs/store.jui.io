<?php $page_id = "generate"; ?>
<?php 

include_once "header.php" 

?>
<script>define.amd = true;</script>

<script type="text/javascript">
<?php echo $_POST['component_code'] ?>

jui.ready(function() { 
	// 테마 설정 
	var obj = lastTheme();

	for(var key in obj) {
		var arr = key.split(/[A-Z]+/);

		obj[key] = { value : obj[key], group : arr[0] };
	}

	parent.setThemeObject(obj);
});

</script>


<?php include_once "footer.php" ?>
