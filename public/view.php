<?php 
include_once '../bootstrap.php';

// connect
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
	echo "<script>alert('This page is not exists.'); location.href = '/'; </script>";
	exit;
}

?>

<?php 

$title = $row['title'];
$id = (string)$row['_id'];
$description = str_replace("\r\n", "\\r\\n", $row['description']);
$username = $row['username'];

if (!$row['type']) $row['type'] = 'component';


include_once "include/generate.meta.php";

$metaList[] = 	'<script type="text/javascript" src="//store.jui.io/js/iframe-resizer/js/iframeResizer.min.js"></script>';

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

$page_id = 'view';
$body_class = 'back-white';
include_once "header.php";


$type = $row['type'];
if (!$type) $type = 'component';
$first = strtoupper(substr($row['type'], 0, 1));

if (!$first) $first = "C";
$color = $type_colors[$first];
?>
<div style="margin-top:100px"></div>
<div id="content-container" class="view-mode">

	<div style="width:100%;max-width:900px;;margin:0 auto;padding-top:50px;">

		<?php
		$id = (string)$row['_id'];
		$link = "view.php?id=".($id);

		$type = $row['type'];
		if (!$type) $type = 'component';

		$first = $type_text[$type];

		?>
		<div class="summary-box large border-none"><div class="summary-normal">
				<div class="name">
					<span><img src="<?php echo $row['avatar'] ?>" width="30" height="30" class='avatar' align='absmiddle'/>&nbsp;<?php echo $row['username'] ?></span>

					<?php
						$share_text = urlencode($description)." #store #jui #js" ;
						$share_url = urlencode("http://".$_SERVER['HTTP_HOST']."/view.php?id=".$id);
						$embed_url = "http://".$_SERVER['HTTP_HOST']."/embed.php?id=".$id;
						$thumbnail_url = "http://".$_SERVER['HTTP_HOST']."/thumbnail.php?id=".$id;
						include "sns.button.php" 
					?>

					<span class="good" style="float:right;overflow:auto;display:inline-block;">
						<a href="javascript:void(good('<?php echo $id?>'))"><i class="icon-like" style="color: #333; font-size: 15px;"></i></a> 
						<span id="good_count_<?php echo $id?>"><?php echo $row['good'] ? $row['good'] : 0 ?></span>
					</span>
				</div>
				<div class="imagesfield">
					<?php
						$embed_url = "embed.php?id=".$id."&only=result";

												// generate static file 
						$root = getcwd();
						$static_file = "/static/".$id."/embed.html";

						if (file_exists($root.$static_file)) {
							$embed_url = $static_file;
						}

						if ($row['type'] == 'pr') { 
					?>
					<iframe id="embed-frame" src="<?php echo $embed_url ?>" style="border:0px" width="100%" height="500px" allowfullscreen></iframe>
					<?php } else { ?>

					<iframe id="embed-frame" src="<?php echo $embed_url ?>" style="border:0px" width="100%"></iframe>
					<?php } ?>
					</div>
				<div class="summary-info">
					<div class="title"><span class="simbol simbol-<?php echo $type ?>"><?php echo $first ?></span> <?php echo $row['title'] ? $row['title'] : '&nbsp;' ?></div>
					<div class="content"><?php echo nl2br($row['description']) ?></div>
				</div>

				<div class="summary-buttons" style="text-align:center;overflow:auto;">

					<?php if ($row['type'] != 'pr') { ?>
					<span class='mini-view' style='float:left;padding:15px 0px;'>Download : </span>
					<span style="float:left;padding:10px 0px;">
						<div class="group">
							<?php if ($row['type'] == 'style') { ?>
							<a href="/download.php?id=<?php echo $id ?>" class="btn ">LESS</a>
							<a href="/download.php?id=<?php echo $id ?>&ext=css" class="btn ">CSS</a>
							<?php } else if ($row['type'] == 'component') { ?>
							<a href="/download.php?id=<?php echo $id ?>" class="btn "><?php echo $type_text[$row['type']] ?></a>
							<a href="/download.full.php?id=<?php echo $id ?>" class="btn ">Sample</a>
							<?php } else { ?>
							<a href="/download.php?id=<?php echo $id ?>" class="btn "><?php echo $type_text[$row['type']] ?></a>
							<a href="/download.php?id=<?php echo $id ?>&code=sample" class="btn ">Sample</a>
							<?php } ?>
						</div>
					</span>
					<?php } ?>
					
					<span style="float: right; padding: 10px 0px;">
						<?php if ($row['type']  == 'pr') { ?>

					    <a class="btn broadcast-btn"><i class='icon-was'></i> Broadcast</a>
					    <a class="btn fullscreen-btn"><i class='icon-was'></i> Full Screen</a>
					    <a href="/editor.php?id=<?php echo $id ?>" class="btn focus"><?php if ($isMy) { ?>Edit<?php } else { ?>Source<?php } ?></a>
						<?php } else { ?>
					    <a href="/<?php echo $row['type'] ?>.php?id=<?php echo $id ?>" class="btn focus"><?php if ($isMy) { ?>Edit<?php } else { ?>Source<?php } ?></a>
						<?php } ?>
					</span>
				</div>

			</div>

		</div>
		<p>&nbsp;</p>
		<?php if (!$detect->isMobile()) { ?>
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
		<div style="margin-top:50px"></div>
		<?php } ?>
	</div>
</div>
	<div style="margin-top:50px"></div>
<script>


 var mobilecheck = function () {
     var check = false;
     (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
     return check;
    }

$(function () {
	$("#embed-frame").iFrameResize({ 
		log: true
	});

	$(".broadcast-btn").on("click", function () {
		window.open("/broadcast.php?id=<?php echo $_GET['id'] ?>", "board_cast_<?php echo $_GET['id'] ?>");
	});

	$(".fullscreen-btn").on('click', function () {

		if (mobilecheck())
		{
			window.open('<?php echo $embed_url ?>', 'fullscreen-slider');
		} else {
			$("#embed-frame")[0].contentWindow.toggleFullScreen();
		}

	});
})

function setContentHeight (height) {
	$("#embed-frame").height((height));
}
</script>
</body>
</html>
