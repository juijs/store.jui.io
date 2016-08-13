<?php $page_id = "generate"; ?>
<?php 

include_once "../../../bootstrap.php";

header('X-XSS-Protection: 0');

$data = $_POST;

include_once "meta.php";

$meta = implode(PHP_EOL, $metaList);
include_once ABSPATH."/header.php";

include_once INC."/preprocessor.php";

?>
<?php include_once INC."/generate.error.check.php" ?>
<style type="text/css">
html,body { 
    background:white;
    margin:0px;
    padding:0px;
}

</style>
<?php echo $data['html_code'] ?>

<style type="text/css">
<?php echo $data['css_code'] ?>
</style>
<script type="text/javascript">

/** sample - start */
(function() { 
<?php echo $data['sample_code'] ?>
})();
/** sample - end */
</script>


</body>
</html>
