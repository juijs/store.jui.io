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


<div class="broadcast">

	<div class="menu">
		<div class="next-presentation">
			<iframe src="?upcomming=true&id=<?php echo $_GET['id'] ?>"></iframe>
		</div>
		<div class="clock">
			<div class="past-time-title">TIME</div>
			<div class="past-time"></div>
			<div class="current-time-title">CLICK TO RESET</div>
			<div class="current-time"></div>
		</div>
		<div class="menu-title">Note <span class='number'></span></div>
		<div class="note-content"></div>
	</div>

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

  function view_note () {
	var indices = Reveal.getIndices();

	var arr = [indices.h];

	if (indices.v > 0) {
		arr.push(indices.v);
	}
	$(".menu .menu-title .number").html(arr.join('-'))
	$(".menu .note-content").html(Reveal.getSlideNotes() || "");
  }

  function set_time_string() {
	$(".past-time").html(get_time_watch());
	$(".current-time").html(get_current_time());
  }

  function view_chat_message(data) {
	var $item =  $("<div class='chat-item'></div>");

	var $avatar = $("<div class='avatar' ></div>").html("<span class='username'>" + (data.username || "nobody") + "</span><span class='time'>" + moment(data.time).format("HH:mm A") + "</span>");
	var $message = $("<div class='message' />").html(data.message);

	$item.html([$avatar[0], $message[0]]);

	$(".chat-message").append($item);
	$item[0].scrollIntoView(true);
  }
  var init_settings = <?php echo $pr_settings ?>;
  init_settings.help = false; 
  init_settings.dependencies = [
    // Syntax highlight for <code> elements
	{ 
		src: '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.7.0/highlight.min.js', async: true, callback: function() { 
			hljs.initHighlightingOnLoad();
		}
	}

 ];

  Reveal.initialize(init_settings);

  view_note();
  view_chat_message({ message : 'Welcome to Real Presentation', username : 'System', time : +new Date() });

  var socket = io('//store.jui.io:3000/pr', { secure: true });

  socket.on('connect', function (s) {
		socket.emit('join room', '<?php echo $_GET['id'] ?>');

		socket.on('message', function (data) {

			if (data.type == 'chat')	{
				view_chat_message(data);
			} else {
				Reveal.slide(data.indexh, data.indexv, data.indexf);
				view_note();
			}


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
	var upcomming_frame = $(".next-presentation iframe")[0];

	upcomming_frame.contentWindow.postMessage(JSON.stringify({ method : 'slide', args : [data.indexh, data.indexv, data.indexf] }), "*");
	upcomming_frame.contentWindow.postMessage(JSON.stringify({ method : 'next' }), "*");
	view_note();
  }

  Reveal.addEventListener("slidechanged", notifyServer);

  Reveal.addEventListener("fragmentshown", notifyServer);

  Reveal.addEventListener("fragmenthidden", notifyServer);


  var start_time = moment().startOf("second");
  
  function get_time_watch ( ) {
	var now = moment().startOf('second');
	var dist =  now.diff(start_time);

	var seconds = dist / 1000;
	var minutes = Math.floor(seconds / 60);
        var seconds_value = seconds % 60; 
	var minutes_value = minutes % 60;
	var hours_value = Math.floor(minutes / 60);

	var h_value = (hours_value < 10) ? '0' + hours_value : hours_value;
	var m_value = (minutes_value < 10) ? '0' + minutes_value : minutes_value;
	var s_value = (seconds_value < 10) ? '0' + seconds_value : seconds_value;

	var h = "<span class='time-"+h_value+"'>"+h_value+"</span>";
	var m = "<span class='time-"+m_value+"'>"+m_value+"</span>";
	var s = "<span class='time-"+s_value+"'>"+s_value+"</span>";

	return [h, m, s].join("<span>:</span>");
  }

  function get_current_time() {
	return moment().format("HH:mm A");
  }

  set_time_string();
  setInterval(function() {
	set_time_string();
  }, 1000);

  $(".current-time").on('click', function () {
	start_time = moment().startOf('second');
	set_time_string();
  });

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

  // 최초 호출 
  setTimeout(function() {
	notifyServer();
  }, 500);

</script>
</body>
</html>
