<?php 
// must have $data 

// this file has only result view
$data = $row; 

$title = $data['title'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];
$pr_obj = json_decode($data['pr_settings']);
$pr_settings = json_encode($pr_obj);

$pr_settings = str_replace('"Reveal.navigateRight"', 'Reveal.navigateRight', $pr_settings);
$pr_settings = str_replace('"Reveal.navigateNext"', 'Reveal.navigateNext', $pr_settings);

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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $data['title'] ?></title>
	<link rel="stylesheet" href="//store.jui.io/bower_components/reveal.js/css/reveal.css" />
	<link rel="stylesheet" href="//store.jui.io/bower_components/reveal.js/css/theme/<?php echo $pr_obj->theme ?>.css" />
	<script src="//store.jui.io/bower_components/reveal.js/js/reveal.js" type="text/javascript"></script>
	<?php echo $meta ?>	
</head>
<body>


<div class="reveal">
	<div class="slides">
<?php $items = json_decode($data['slide_code']);

foreach($items as $index => $it) {


	$attrs = array();
	foreach($it->settings as $key => $value) {
		if ($value) $attrs[] = "data-{$key}='{$value}'";
	}

	$attr_string = implode(" ", $attrs);


	if (!$it->secondary && $items[$index+1]->secondary) {
?>
		<section>
<?php
	}

?>
	<section  <?php echo $attr_string ?>><?php echo HtmlPreprocessor($it->content, 'markdown');?>
	
		<?php if ($it->note) { ?> 
		<aside class="notes"><?php echo HtmlPreprocessor($it->note, 'markdown');?></aside>
		<?php } ?>
	</section>

<?php 
	if ($it->secondary && (!$items[$index+1]  || !$items[$index+1]->secondary)) {
?>
	</section>
<?php
	}
}

?>
	</div>
</div>

<script type="text/javascript">
Reveal.initialize(<?php echo  $pr_settings ?>);

/*
function toggleFullScreen() {
    if (!document.fullscreenElement && !document.msFullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
        if (document.body.requestFullscreen) {
            document.body.requestFullscreen();
        } else if (document.body.msRequestFullscreen) {
            document.body.msRequestFullscreen();
        }else if (document.body.mozRequestFullScreen) {
            document.body.mozRequestFullScreen();
        }else if (document.body.webkitRequestFullscreen) {
            document.body.webkitRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        }else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}
*/

location.href="#/<?php echo $_GET['selected_num']  ?>";
</script>
</body>
</html>
