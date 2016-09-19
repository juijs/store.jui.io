<?php 
$data = $row; 

$title = $data['title'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];

include V2_PLUGIN."/component/meta.php";

$metaList[] =<<<EOD
	<!-- Facebook -->
	<meta property="og:title" content="{$title}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="//store.jui.io/view.php?id={$id}"/>
	<meta property="og:description" content="{$description}"/>
	<meta property="og:image" content="//store.jui.io/thumbnail.php?id={$id}"/>

	<!-- Twitter -->
	<meta name="twitter:card"           content="summary_large_image">
	<meta name="twitter:title"          content="{$title}">
	<meta name="twitter:site"           content="@easylogic">
	<meta name="twitter:creator"        content="@{$username}">
	<meta name="twitter:image"          content="//store.jui.io/thumbnail.php?id={$id}">
	<meta name="twitter:description"    content="{$description}">
	 
	<!-- Google -->
	<meta itemprop="name" content="{$title}">
	<meta itemprop="description" content="{$description}">
	<meta itemprop="image" content="//store.jui.io/thumbnail.php?id={$id}">
EOD;


$meta = implode(PHP_EOL, $metaList);

include V2_PLUGIN."/component/header.static.php";

include_once V2_INC."/preprocessor.php";

//var_dump($data);
?>
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

(function() {
/** component - start */
<?php echo $data['component_code'] ?>
/** component - end */
})();


(function() { 
/** sample - start */
<?php echo $data['sample_code'] ?>
/** sample - end */
})();

</script>
</body>
</html>
