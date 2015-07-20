<?php 
$page_id = "generate"; 

?>

<?php include_once "header.php" ?>
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
	$filename = "theme/".$id.".less";

	file_put_contents($filename, $code);

	//$less = new lessc;
	//$result = $less->compile($filename);

?>
<style type="text/css">
<?php echo $result ?>
</style>
<?


?>
<div id="result">
<?php include_once "implements/{$sample_type}.html" ?>
</div>

<?php include_once "footer.php" ?>
