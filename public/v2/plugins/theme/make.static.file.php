<?php 
// must have $data 

// this file has only result view

ob_start();

$title = $data['title'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];

include V2_PLUGIN."/theme/meta.php";

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

include V2_PLUGIN."/theme/header.static.php";

include INC."/preprocessor.php";

$sample_path = ABSPATH.'/sample/'.$data['sample_code'].".js";

if (file_exists($sample_path)) {
	$data['sample_code']  = file_get_contents($sample_path);
}

$data['sample_code'] = str_replace("#chart-content", "#embedResult", $data['sample_code']);
$data['sample_code'] = str_replace("#chart", "#embedResult", $data['sample_code']);

?>
<style type="text/css">
html, body {
	background:white;
}


#embedResult {
	left:0px;
	top:0px;
	width:100%;
	height:100%;
}

.maxArea {
	position:absolute;
	left:0px;
	top:0px;
	right:0px;
	bottom:0px;
}
</style>
<div class="maxArea">
	<div id="embedResult"></div>
</div>

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

jui.ready(function() { 

	// 테마 설정 
	var obj = $("#embedResult")[0].jui;
	if (obj) {
		obj.setTheme("CustomeTheme");
	}
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
