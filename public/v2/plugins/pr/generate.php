<?php $page_id = "generate"; ?>
<?php 

include_once "../../../../bootstrap.php";

header('X-XSS-Protection: 0');

$data = $_POST;

$pr_obj = json_decode($_POST['pr_settings']);
$pr_settings = json_encode($pr_obj);

$pr_settings = str_replace('"Reveal.navigateRight"', 'Reveal.navigateRight', $pr_settings);
$pr_settings = str_replace('"Reveal.navigateNext"', 'Reveal.navigateNext', $pr_settings);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="//store.jui.io/bower_components/reveal.js/css/reveal.css" />
	<link rel="stylesheet" href="//store.jui.io/bower_components/reveal.js/css/theme/<?php echo $pr_obj->theme ?>.css" />
	<script src="//store.jui.io/bower_components/reveal.js/js/reveal.js" type="text/javascript"></script>
</head>
<body>


<div class="reveal">
	<div class="slides">
<?php $items = json_decode($_POST['slide_code']);

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

window.setSelectedNum = function (arr) {
	Reveal.slide.apply(Reveal,  arr)
}

Reveal.initialize(<?php echo  $pr_settings ?>);
Reveal.addEventListener( 'slidechanged', function( event ) {
	parent.setSelectedSlide(event.indexh, event.indexv);
} );
location.href="#/<?php echo $_POST['selected_num'] ?>";


</script>

</body>
</html>
