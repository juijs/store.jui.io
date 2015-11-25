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

//var_dump($row);

if (($row['access'] == 'private') && !$isMy) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

?>

<?php 

$title = $row['title'];
$id = (string)$row['_id'];
$description = str_replace("\r\n", "\\r\\n", $row['description']);
$username = $row['username'];

if (!$row['type']) $row['type'] = 'component';

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
EOD;

$page_id = 'view';
include_once "header.php";


$type = $row['type'];
if (!$type) $type = 'component';
$first = strtoupper(substr($row['type'], 0, 1));

if (!$first) $first = "C";
$color = $type_colors[$first];

?>
<div style="margin-top:28px"></div>
<div id="content-container" style="padding:10px;">

	<div style="width:100%;max-width:950px;;margin:0 auto;padding-top:50px;">

		<?php
		$id = (string)$row['_id'];
		$link = "view.php?id=".($id);

		$type = $row['type'];
		if (!$type) $type = 'component';

		$first = $type_text[$type];

		?>
		<div class="summary-box large"><div class="summary-normal">
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
					?>
					<iframe src="<?php echo $embed_url ?>" style="border:0px" width="100%" height="400px" frameborder="0" border="0" id="result"></iframe>
					</div>
				<div class="summary-info">
					<div class="title"><span class="simbol simbol-<?php echo $type ?>"><?php echo $first ?></span> <?php echo $row['title'] ? $row['title'] : '&nbsp;' ?></div>
					<div class="content"><?php echo nl2br($row['description']) ?></div>
				</div>

				<div class="summary-buttons" style="text-align:center;overflow:auto;">
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
					
					<span style="float: right; padding: 10px 0px;">
					    <a href="/<?php echo $row['type'] ?>.php?id=<?php echo $id ?>" class="btn focus"><?php if ($isMy) { ?>Edit<?php } else { ?>Source<?php } ?></a>
					</span>
				</div>

			</div>

		</div>
        <p>&nbsp;</p>
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

	</div>

</div>
<script>
function setContentHeight (height) {
	$("#result").height(height+20);
}
</script>
</body>
</html>

