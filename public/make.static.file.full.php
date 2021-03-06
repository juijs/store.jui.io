<?php 
// must have $data 

// this file has only result view

ob_start();

$title = $data['title'];
//$id = (string)$data['_id'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];

if (!$data['type']) $data['type'] = 'component';

include_once "include/generate.meta.php";

$metaList[] =<<<EOD
	<!-- Facebook -->
	<meta property="og:title" content="{$title}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="http://store.jui.io/view.php?id={$id}"/>
	<meta property="og:description" content="{$description}"/>
	<meta property="og:image" content="http://store.jui.io/thumbnail.php?id={$id}"/>

	<!-- Twitter -->
	<meta name="twitter:card"           content="summary_large_image">
	<meta name="twitter:title"          content="{$title}">
	<meta name="twitter:site"           content="@easylogic">
	<meta name="twitter:creator"        content="@{$username}">
	<meta name="twitter:image"          content="http://store.jui.io/thumbnail.php?id={$id}">
	<meta name="twitter:description"    content="{$description}">
	 
	<!-- Google -->
	<meta itemprop="name" content="{$title}">
	<meta itemprop="description" content="{$description}">
	<meta itemprop="image" content="http://store.jui.io/thumbnail.php?id={$id}">
EOD;

$meta = implode(PHP_EOL, $metaList);


include_once "header.static.full.php";

include_once "include/preprocessor.php";

$type = $data['type'];

?>
<div id="content-container">
	<div class='nav-container result-only' >
        <div id='embedResult' class='nav-content active' style="overflow:hidden" >
			<?php
				echo $data['html_code'];
			?>		
		
		</div>
	</div>

</div>
<style type="text/css">
<?php echo $data['css_code'] ?>
</style>
<script type="text/javascript">
<?php echo $data['component_code'] ?>
</script>
<script type="text/javascript">
<?php echo $data['sample_code'] ?>
</script>
</body>
</html>
<?php 


$static = ob_get_contents();
ob_end_clean();

// generate static file 
return $static;
?>
