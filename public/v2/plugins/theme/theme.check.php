<?php $page_id = "generate"; ?>
<?php 

include_once "../../../../bootstrap.php";

include_once V2_PLUGIN."/theme/meta.php";
$metaList= array();
$metaList[] = '    <script type="text/javascript" src="//store.jui.io/bower_components/jquery/dist/jquery.min.js"></script>';
$metaList[] = '<script type="text/javascript" src="//store.jui.io/jui-all/jui-core/dist/core.js"></script>';
$meta = implode(PHP_EOL, $metaList);

echo $meta; 
?>
<script type="text/javascript">
<?php echo $_POST['component_code'] ?>

jui.ready(function() { 
	// 테마 설정 
	var obj = jui.include("chart.theme.<?php echo $_POST['theme_name'] ?>");

	parent.setThemeObject(obj);
});

</script>
