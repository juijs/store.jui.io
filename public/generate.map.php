<?php $page_id = "generate"; ?>
<?php 

include_once "include/generate.meta.php";

$metaList[] = "<script>if (define) define.amd=true;</script>";
$meta = implode(PHP_EOL, $metaList);
include_once "header.php";

$map_link = "data:image/svg+xml;base64,".base64_encode($_POST['component_code']);
$sample_code = str_replace("@path", "'".$map_link."'", $_POST['sample_code']) ;

?>
<?php include_once "include/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

</style>
<?php echo $_POST['html_code'] ?>

<script type="text/javascript">
/** sample - start */
<?php echo $sample_code ?>
/** sample - end */
</script>



</body>
</html>
