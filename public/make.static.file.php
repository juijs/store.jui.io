<?php 
// must have $data 

// this file has only result view

ob_start();

$title = $data['title'];
//$id = (string)$data['_id'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];

if (!$data['type']) $data['type'] = 'component';

include_once "include/genenate.meta.php";

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

include_once "header.static.php";


include_once "include/preprocessor.php";

$type = $data['type'];
$first = $type_text[$type];

$color = $type_colors[$first];



?>
<style type="text/css">
html, body {
	background:white;
}

.CodeMirror {
	height: 100%;
}

.nav-btn {
	position: relative;
    display: inline-block;
    vertical-align: middle;
    cursor: pointer;
    text-decoration: none;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    padding: 0 12px;
    font-size: 12px;
    height: 28px;
    line-height: 28px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
	width:70px;
	text-align:center;
}

.nav-btn.nav-edit {
	width:inherit;
}

.nav-btn.large {

}
</style>
<div id="content-container">
	<div class='nav-container result-only' >
        <div id='embedResult' class='nav-content active' style="overflow:hidden" >
			<?php
			$type = $data['type'];
			$sample_type = $data['sample_type'];

			if ($type == 'style') {

				if (!$sample_type) {
					$sample_type = 'buttons';
				}
				?>
					<div style="padding:10px">
						<?php include __DIR__."/sample/ui/{$sample_type}.html" ?>
					</div>
			<?php
			} else if ($type == 'component' || $type == 'map') {
						
				echo $data['html_code'];
			}
			?>		
		
		</div>
	</div>

</div>

<?php if ($data['type'] == 'style') { ?>
<link rel="stylesheet" href="/generate.css.php?id=<?php echo $id ?>" />

<?php } else if ($data['type'] == 'map') { 

$map_link = "/generate.js.php?id=".$id;
$sample_code = str_replace("@path", "'".$map_link."'", $data['sample_code']) ;

?>
<script type="text/javascript">
<?php echo $sample_code ?>
</script>

<?php  } else { ?>
<style type="text/css">
<?php echo $data['css_code'] ?>
</style>
<script type="text/javascript">
<?php echo $data['component_code'] ?>
</script>
<script type="text/javascript">
<?php echo $data['sample_code'] ?>
</script>
<script type="text/javascript">

jui.ready(function() { 

	// 테마 설정 
	var theme = '<?php echo $data['name'] ?>';
	if ('<?php echo $data['type'] ?>' == 'theme') {
		var obj = $("#embedResult")[0].jui;
		if (obj) {
			obj.setTheme(theme);
		}
	}
});

</script>

<?php } ?>
<script type="text/javascript">
$(function() {

	setTimeout(function() {
		if (parent && parent.setContentHeight)
		{
			parent.setContentHeight($("#embedResult")[0].scrollHeight);
		}
	}, 100);
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
