<?php $page_id = "generate"; ?>
<?php 

$meta = "<script>define.amd=true;</script>";
include_once "header.php";

?>
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
<?php echo $_POST['component_code'] ?>

<?php echo $_POST['sample_code'] ?>

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
