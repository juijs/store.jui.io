<?php 
include_once '../../bootstrap.php';

$share_view_enable = true;

include_once 'common.php';
// connect
//
//
$m = new MongoClient();

// select a rowbase
$db = $m->store;

if (isset($_GET['rev'])) {
	$components = $db->components_history;

	$row = $components->findOne(array(
		'component_id' => $_GET['id'],
		'rev' => intval($_GET['rev'])
	));

	$row['_id'] = $row['component_id'];
} else {
	$components = $db->components;

	$row = $components->findOne(array(
		'_id' => new MongoId($_GET['id'])
	));
}

$isMy = true;
if ($_GET['id'] && ($row['login_type'] != $_SESSION['login_type'] || $row['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}


if (($row['access'] == 'private') && !$isMy) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

if ($row == null) {
	echo "<script>alert('This page is not exists.'); location.href = '/v2/'; </script>";
	exit;
}

$title = $row['title'];
$id = (string)$row['_id'];
$description = str_replace("\r\n", "\\r\\n", $row['description']);
$username = $row['username'];

if (!$row['type']) $row['type'] = 'component';


include_once "include/generate.meta.php";

//$metaList[] = 	'<script type="text/javascript" src="//store.jui.io/js/iframe-resizer/js/iframeResizer.min.js"></script>';

$meta = implode(PHP_EOL, $metaList);

$meta =<<<EOD
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

	{$meta}
EOD;

$type = $row['type'];
if (!$type) $type = 'component';
$first = strtoupper(substr($row['type'], 0, 1));

if (!$first) $first = "C";
$color = $type_colors[$first];

$page_id = 'view';


$data = $db->good_count->findOne(array(
	'component_id' => $id,
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
));

$likedClass = '';

if ($data) {
	$likedClass = 'liked';
}

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>JENNIFER UI: Store</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>

<?php echo $meta ?>

<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<link href="/v2/css/<?php echo $theme ?>.css" rel="stylesheet" />
<link href="/v2/css/<?php echo $theme ?>-responsive.css" rel="stylesheet" />

</head>
<body class="jui flat">
<div class="t">
	<?php include_once "nav.php" ?>

	<div class="content-container view-mode">

			<?php
				$id = (string)$row['_id'];
				$link = "view.php?id=".($id);

				$type = $row['type'];
				if (!$type) $type = 'component';

				$first = $type_text[$type];

				$embed_url = "embed.php?id=".$id."&only=result";

										// generate static file 
				$static_file = "/static/".$id."/embed.html";

				if (file_exists(ABSPATH.$static_file)) {
					//$embed_url = $static_file;
				}

				$good_rate = get_good_rate($row['good']);

			?>
		<div class="layout" >

			<div class="left">

				<div class="line"></div>
				<div class="type-text"><?php echo $first ?></div>
				<div class="title"> <?php echo $row['title'] ? $row['title'] : '&nbsp;' ?></div>
				<div class="info">
					<img class="avatar" src="<?php echo $row['avatar'] ?>" width="40px" height="40px" align="middle" />
					<div class="user-name"><?php echo $row['username'] ?></div>
					<div class="date"><?php echo timeAgoInWords( date('Y-m-d H:i:s', $row['update_time']), 'Asia/Seoul', 'ko') ?></div>
				</div>
				<div class="divider"></div>
				<div class="frame">
					<iframe id="embed-frame" src="<?php echo $embed_url ?>" width="100%" height="500px" allowfullscreen></iframe>
					<div class="resizer" title="Please drag for content resizing "><i class="icon-grip1"></i></div>
				</div>
			</div>
			<div class="right">
				<div class="button-group">

					<a href="/v2/editor.php?id=<?php echo $id ?>" class="button button-regular active"><?php if ($isMy) { ?>Edit<?php } else { ?>Source<?php } ?></a>
					<?php if ($isMy) { ?>
					<a href="#" onclick="deletecode('<?php echo $id ?>')" class="button button-regular  danger  active" style="margin-top:10px;">Delete</a>
					<?php } ?>
					<div class="title">TOOLS</div>
			
					<?php include_once V2_PLUGIN."/$type/tools.php" ?>
				</div>
				<div class="divider"></div>
				<div class="text" id="component-information-<?php echo $id ?>" >
					<div class="title">DESCRIPTION</div>
					<div class="description"><?php echo nl2br($row['description']) ?></div>
					<div class="title score">SCORE <span class="liked-number"><?php echo number_format($row['good']) ?></span></div>
					<div class="score-chart good-type-<?php echo $good_type ?>" style="position:relative;">
						<div  class="score-number <?php echo $likedClass ?> " onclick="liked('<?php echo $id?>')" title="Like">
						<svg width="100%" height="100%"  xmlns:xlink= "http://www.w3.org/1999/xlink">
							<circle cx="50%" cy="50%" r="48%" fill="transparent" stroke-width="5" stroke="#d8d8d8" />
							<circle class="gauge" cx="50%" cy="50%" r="48%" fill="transparent" stroke-width="5" stroke="#4ad0b2" stroke-dasharray="<?php echo $good_rate['fill'] ?> <?php echo $good_rate['empty'] ?>" />
						</svg>
						<?php echo get_svg_image('loveit') ?>
						<?php echo get_svg_image('loveit-2') ?>
						</div>
					</div>

					<a class="button button-regular button-common view-share-btn" title="Share a code"><?php echo get_svg_image('share') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>


		<!-- disqus --> 
		<div id="disqus_thread"></div>
		<script type="text/javascript">
			/* * * CONFIGURATION VARIABLES * * */
			var disqus_shortname = 'store-jui';
			
			/* * * DON'T EDIT BELOW THIS LINE * * */
			(function() {
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
		<!-- //disqus --> 

	</div>

<script>


	

	$(function () {

		$(".view-share-btn").click(function() {
			show_share_modal();
		});

		$(".broadcast-btn").on("click", function () {
			window.open("/v2/broadcast.php?id=<?php echo $_GET['id'] ?>", "board_cast_<?php echo $_GET['id'] ?>");
		});

		$(".fullscreen-btn").on('click', function () {

			if (mobilecheck())
			{
				window.open('<?php echo $embed_url ?>', 'fullscreen-slider');
			} else {
				toggleFullScreen();
			}

		});

		function whichTransitionEvent(){
		  var t,
			  el = document.createElement("fakeelement");

		  var transitions = {
			"transition"      : "transitionend",
			"OTransition"     : "oTransitionEnd",
			"MozTransition"   : "transitionend",
			"WebkitTransition": "webkitTransitionEnd"
		  }

		  for (t in transitions){
			if (el.style[t] !== undefined){
			  return transitions[t];
			}
		  }
		}

		function whichTransition(){
		  var t,
			  el = document.createElement("fakeelement");

		  var transitions = {
			"transition"      : "transitionend",
			"OTransition"     : "oTransitionEnd",
			"MozTransition"   : "transitionend",
			"WebkitTransition": "webkitTransitionEnd"
		  }

		  for (t in transitions){
			if (el.style[t] !== undefined){
				return t; 
			}
		  }
		}

		var transitionEvent = whichTransitionEvent();
		var myCSSObj     = { 'transition' : 'all 0.5s ease-in-out' };
				myCSSObj[whichTransition()] = 'all 0.5s ease-in-out';


		$(".score-layer").on('click', function () {

			var $self = $(this);
			$self.find("image").css(myCSSObj);
			$self.addClass('boom');
			$self.find("image").one(transitionEvent, function (e) {
				$self.find("image").css('transition', '');
				$self.removeClass('boom');
			});
		})

		$(".resizer").on("mousedown", function MouseDown (e) {
			
			var x  = e.clientX;
			var y  = e.clientY; 

			var prevX = x;
			var prevY  = y;

			var mousemove = function (e2) {
				var $a = $("#embed-frame");
				var h = $a.height();
				$a.height(h + (e2.clientY - prevY));
				prevY = e2.clientY;
			};

			$(".frame").append("<div class='screen-block'></div>");
			$("#disqus_thread").append("<div class='screen-block'></div>");

			var mouseup = function (e) {
				$('body').off('mousemove', mousemove).off('mouseup', mouseup);
				$(".frame .screen-block").remove();
				$("#disqus_thread .screen-block").remove();
			};

			$('body').on('mousemove', mousemove);

			$('body').on('mouseup', mouseup);
		});
	})

	function setContentHeight (height) {
		$("#embed-frame").height((height));
	}

	function open_fullscreen() {
		$("#embed-frame").appendTo(".fullscreen");
		
		$("body").addClass('open-fullscreen');
	} 

	function close_fullscreen() {
		$("#embed-frame").appendTo(".frame");
		$("body").removeClass('open-fullscreen');
	}

	window.deletecode = function deletecode (id) {
		if (confirm("Delete this component?\r\ngood count is also deleted.")) {
			$.post("/delete.php", { id : id  }, function(res) {
				if (res.result) {
					location.href = '/v2/dashboard.php'; 	
				} else {
					alert(res.message ? res.message : 'Failed to delete');
				}
			});
		}
	}



	</script>

	<?php include_once "footer.php" ?>
</div>
<?php include_once "modals.php" ?>
</body>
</html>

