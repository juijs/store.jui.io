<?php 
//error_reporting(E_ALL);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$page_id = "generate.ui"; 

$backgroundColor = 'white';

if ($_POST['theme_name'] == 'dark') {
	$backgroundColor = 'black';
}

$metaList[] = '<link rel="stylesheet" href="/jui-all/jui/dist/ui.min.css" />';
$metaList[] = '<link rel="stylesheet" href="/jui-all/jui/dist/ui-'.$_POST['theme_name'].'.min.css" />';
$metaList[] = '<script type="text/javascript" src="/jui-all/jui-core/dist/core.min.js"></script>';
$metaList[] = '<script type="text/javascript" src="/jui-all/jui/dist/ui.min.js"></script>';
$meta = implode(PHP_EOL, $metaList);

?>

<?php include_once "header.php" ?>

<style type="text/css">
html,body { 
    background:<?php echo $backgroundColor ?>;
    margin:0px;
    padding:0px;
}

#result {
    position:absolute;
    width:100%; 
    height:100%;
    
}
</style>
<?php

$sample_type = $_POST['sample_type'];

if (!$sample_type) {
	$sample_type = "buttons";
}

$id = uniqid("temp".rand(0, 100));
$code = $_POST['component_code'];

$defaultTheme = "jui/less/theme/jennifer.less";
$filename = "jui/less/theme/".$id.".less";

$realCode = str_replace('@import "../theme.less";
', '', file_get_contents($defaultTheme)).PHP_EOL.$code;

file_put_contents(__DIR__."/".$filename, $realCode);

try {
	$parser = new Less_Parser();
	$parser->parseFile( __DIR__."/".$filename );
	$result = $parser->getCss();

    $result = str_replace("../img", "/img", $result);
    $result = str_replace("../widget/img", "/img", $result);

} catch (Exception $ex) {

	echo $ex->getMessage();
}

unlink($filename);
?>
<style type="text/css">
<?php echo $result ?> 
</style>
<div id="result">
	<div style="padding:10px" class="jui-style <?php echo $_POST['theme_name'] ?>">
		<?php include_once "sample/ui/{$sample_type}.html" ?>
	</div>
</div>


</body>
</html>
