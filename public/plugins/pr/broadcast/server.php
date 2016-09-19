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
	<script type="text/javascript" src="//store.jui.io:3000/socket.io/socket.io.js"></script>
	<?php echo $meta ?>	
	<style type="text/css">
	.broadcast {
		position:absolute;
		left:0px;
		right:0px;
		bottom:0px;
		top:0px;
	}

	.broadcast .menu {
		position: absolute;
		left:0px;
		top:0px;
		width:500px;
		bottom:0px;
		background-color:rgba(0, 0, 0, .4);
	}

	.broadcast .content {
		position: absolute;
		left:500px;
		top:0px;
		right:0px;
		bottom:0px;
	}
	</style>
</head>
<body>


<div class="broadcast">

	<div class="menu"></div>
	<div class="content">
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
	</div>

</div>




<script type="text/javascript">
Reveal.initialize(<?php echo  $pr_settings ?>);

  var socket = io('//store.jui.io:3000/pr');

  socket.on('connect', function (s) {
		socket.emit('join room', '<?php echo $_GET['id'] ?>');

		socket.on('message', function (data) {
				Reveal.slide(data.indexh, data.indexv, data.indexf);
		});
  });

 var notifyServer = function(event){
	var data = {
	  userid : '<?php echo $_SESSION['userid'] ?>',
	  username : '<?php echo $_SESSION['username'] ?>',
	  indexv : Reveal.getIndices().v,
	  indexh : Reveal.getIndices().h,
	  indexf : Reveal.getIndices().f || 0
	}

	socket.emit("message" , data);
  }

  Reveal.addEventListener("slidechanged", notifyServer);

  Reveal.addEventListener("fragmentshown", notifyServer);

  Reveal.addEventListener("fragmenthidden", notifyServer);

</script>
</body>
</html>