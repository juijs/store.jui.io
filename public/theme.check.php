<?php $page_id = "generate"; ?>
<?php include_once "header.php" ?>

<script type="text/javascript">
<?php echo $_POST['component_code'] ?>

jui.ready(function() { 
	// �׸� ���� 
	parent.setThemeObject(lastTheme());
});

</script>


<?php include_once "footer.php" ?>
