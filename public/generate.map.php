<?php $page_id = "generate"; ?>
<?php 

$meta = "<script>define.amd=true;</script>";
include_once "header.php";

$map_link = "data:image/svg+xml;base64,".base64_encode($_POST['component_code']);
$sample_code = str_replace("@path", "'".$map_link."'", $_POST['sample_code']) ;

?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

</style>
<?php echo $_POST['html_code'] ?>

<script type="text/javascript">
<?php echo $sample_code ?>
</script>



</body>
</html>
