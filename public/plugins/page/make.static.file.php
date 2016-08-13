<?php 
// must have $data 

// this file has only result view

ob_start();

$title = $data['title'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];

include PLUGIN."/page/meta.php";

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

include PLUGIN."/page/header.static.php";

include INC."/preprocessor.php";
?>
<style type="text/css">
html, body {
	background:white;
}
</style>
<div id='embedResult' class='active' >
	<?php  echo $data['html_code']; 	?>
</div>

<style type="text/css">
<?php echo $data['css_code'] ?>
</style>
<script type="text/javascript">
(function() {  <?php echo $data['sample_code'] ?> })();
</script>
<script type="text/javascript">
$(function() {

	setInterval(function() {
		if (parent && parent.setContentHeight)
		{
			parent.setContentHeight($("body")[0].scrollHeight);
		}
	}, 1000);
});
</script>
</body>
</html>
<?php 


$static = ob_get_contents();
ob_end_clean();

// generate static file 
$root = getcwd()."/static/".$id;
@mkdir($root, 0777, true);

file_put_contents($root."/embed.html", $static);
?>
