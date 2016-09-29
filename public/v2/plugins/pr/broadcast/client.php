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
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.7.0/styles/tomorrow-night.min.css">
	<script src="//store.jui.io/bower_components/reveal.js/lib/js/head.min.js" type="text/javascript"></script>


	<script src="//store.jui.io/bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
	<script src="//store.jui.io/bower_components/reveal.js/js/reveal.js" type="text/javascript"></script>
	<script src="//store.jui.io/bower_components/moment/min/moment.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="//store.jui.io:3000/socket.io/socket.io.js"></script>
	<link rel="stylesheet" href="<?php echo V2_PLUGIN_URL ?>/pr/resource/broadcast.css" />
	<?php echo $meta ?>	
</head>
<body>


<div class="broadcast client <?php echo ($_GET['upcomming']) ? "upcomming" : "" ?>">
	<div class="chat">
		<div class="chat-message">
			
		</div>
		<div class="chat-input">
			<input type="text" placeholder="Type here" />
		</div>
	</div>

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
 var init_settings = <?php echo $pr_settings ?>;
  init_settings.dependencies = [
    // Syntax highlight for <code> elements
	{ 
		src: '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.7.0/highlight.min.js', async: true, callback: function() { 
			hljs.initHighlightingOnLoad();
		}
	}

 ];

  Reveal.initialize(init_settings);


<?php if ($_GET['upcomming']) { ?>

Reveal.configure({
	controls : false,
	progress : false,
	overview: false,
	keyboard: false,
	touch:false,
	previewLinks: true,
	transition: 'none',
	backgroundTransition: 'none',
	embedded : true,
	help: false 
});
Reveal.slide(1, 0, 0);
window.addEventListener( 'message', function( event ) {
    var data = JSON.parse( event.data );
    Reveal.slide(data.indexh+1, 0, 0);
} );

<?php } else { ?>
Reveal.configure({
	help: false 
});


  var socket = io('//store.jui.io:3000/pr');

  socket.on('connect', function (s) {
	socket.emit('join room', '<?php echo $_GET['id'] ?>');

	socket.on('message', function (data) {
			if (data.type == 'chat')	{
				view_chat_message(data);
			} else {
				Reveal.slide(data.indexh, data.indexv, data.indexf);
			}
	});
  });

  function view_chat_message(data) {
	var $item =  $("<div class='chat-item'></div>");

	var $avatar = $("<div class='avatar' ></div>").html("<span class='username'>" + (data.username || "nobody") + "</span><span class='time'>" + moment(data.time).format("HH:mm A") + "</span>");
	var $message = $("<div class='message' />").html(data.message);

	$item.html([$avatar[0], $message[0]]);

	$(".chat-message").append($item);
	$item[0].scrollIntoView(true);
  }
  view_chat_message({ message : 'Welcome to Real Presentation', username : 'System', time : +new Date() });
 var notifyServer = function(event){
	var data = {
	  userid : '<?php echo $_SESSION['userid'] ?>',
	  username : '<?php echo $_SESSION['username'] ?>',
	  indexv : Reveal.getIndices().v,
	  indexh : Reveal.getIndices().h,
	  indexf : Reveal.getIndices().f || 0
	}

	//socket.emit("message" , data);
  }

  Reveal.addEventListener("slidechanged", notifyServer);

  Reveal.addEventListener("fragmentshown", notifyServer);

  Reveal.addEventListener("fragmenthidden", notifyServer);

  
  $(".chat-input input").on('keyup', function (e) {
		if (e.keyCode == 13)
		{
			var data = {
			  type : 'chat', 
			  all: true, 
			  time : +new Date(),
			  userid : '<?php echo $_SESSION['userid'] ?>',
			  username : '<?php echo $_SESSION['username'] ?>',
			   message : $(this).val()
			}

			$(this).val('').focus().select();

			socket.emit("message" , data);
			return;
		}
  });
<?php } ?>
</script>
</body>
</html>
