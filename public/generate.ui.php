<?php 
//error_reporting(E_ALL);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$page_id = "generate.ui"; 
?>

<?php include_once "header.php" ?>
<link rel="stylesheet" href="sample/ui/css/jui.css" />
<style type="text/css">
html,body { 
    background:white;
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
	$sample_type = "button";
}

$id = uniqid("temp".rand(0, 100));
$code = $_POST['component_code'];

$filename = "sample/ui/theme/".$id.".less";

file_put_contents(__DIR__."/".$filename, $code);

try {
	$parser = new Less_Parser();
	$parser->parseFile( __DIR__."/".$filename );
	$result = $parser->getCss();

    $result = str_replace("../img", "/img", $result);

} catch (Exception $ex) {

	echo $ex->getMessage();
}

unlink($filename);
?>
<style type="text/css">
<?php echo $result ?> 
</style>
<div id="result">
	<div style="padding:10px" class="jui-style">
		<?php include_once "sample/ui/implements/{$sample_type}.html" ?>
	</div>
</div>

<?php include_once "footer.php" ?>
