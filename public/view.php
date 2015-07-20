<?php 
include_once '../bootstrap.php';

// connect
$m = new MongoClient();

// select a rowbase
$db = $m->store;

$components = $db->components;

$row = $components->findOne(array(
	'_id' => new MongoId($_GET['id'])
));

if ($row['access'] == 'private') {
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
	<meta property="og:url" content="http://store.jui.io/share.php?id={$id}"/>
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

include_once "header.php";


$type = $row['type'];
if (!$type) $type = 'component';
$first = strtoupper(substr($row['type'], 0, 1));

if (!$first) $first = "C";
$color = $type_colors[$first];

?>
<div id="content-container" style="padding:20px;">

	<div style="width:950px;margin:0 auto;padding-top:50px;">

		<?php
		$id = (string)$row['_id'];
		$link = "view.php?id=".($id);

		$type = $row['type'];
		if (!$type) $type = 'component';

		$first = strtoupper(substr($type, 0, 1));

		if (!$first) $first = "C";
		$color = $type_colors[$first];

		?>
		<div class="summary-box large"><div class="summary-normal">
				<div class="name">
					<span><img src="<?php echo $row['avatar'] ?>" width="30" height="30" class='avatar' align='absmiddle'/>&nbsp;<?php echo $row['username'] ?></span>

					<?php
						$share_text = urlencode($description)." #store #jui #js" ;
						$share_url = urlencode("http://".$_SERVER['HTTP_HOST']."/view.php?id=".$id);

						include "sns.button.php" 
					?>

					<span class="good" style="float:right;overflow:auto;display:inline-block;">
						<a href="javascript:void(good('<?php echo $id?>'))"><img src="images/good.png" /></a> 
						<span id="good_count_<?php echo $id?>"><?php echo $row['good'] ? $row['good'] : 0 ?></span>
					</span>
				</div>
				<div class="imagesfield">
					<div id="result" style="width:100%;height:400px;background:white;"><?php
						$type = $row['type'];
						$sample_type = $row['sample_type'];

						if ($type == 'style') {

							if (!$sample_type) {
								$sample_type = 'button';
							}
							?>
								<div style="padding:10px" class="jui-style">
									<?php include __DIR__."/sample/ui/implements/{$sample_type}.html" ?>
								</div>
						<?php
						}
						
						?></div>
				</div>
				<div class="summary-info">
					<div class="title"><span class="simbol simbol-<?php echo $type ?>"><?php echo $first ?></span> <?php echo $row['title'] ? $row['title'] : '&nbsp;' ?></div>
					<div class="content"><?php echo nl2br($row['description']) ?></div>
				</div>

				<div class="summary-buttons">
					<?php if ($_SESSION['login'] && $row['login_type'] == $_SESSION['login_type'] && $row['userid'] == $_SESSION['userid']) { ?>
						<a href="/<?php echo $row['type'] ?>.php?id=<?php echo $id ?>" class="btn-large">Edit</a>
					<?php } else { ?>
						<a href="/source.php?id=<?php echo $id ?>" class="btn-large">View Source</a>
					<?php } ?>
				</div>

			</div>

		</div>

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

<?php if ($row['type'] == 'style') { ?>
<link rel="stylesheet" href="sample/ui/css/jui.css" />
<link rel="stylesheet" href="generate.css.php?id=<?php echo $_GET['id'] ?>" />

<?php  } else { ?>

<script type="text/javascript" src="generate.js.php?id=<?php echo $_GET['id'] ?>"></script>
<script type="text/javascript" src="generate.js.php?id=<?php echo $_GET['id'] ?>&code=sample"></script>
<script type="text/javascript">

jui.ready(function() { 

	// 테마 설정 
	var theme = '<?php echo $row['name'] ?>';
	if (theme.indexOf("chart.theme.") > -1) {
		var obj = $("#result")[0].jui;
		if (obj) {
			obj.setTheme(theme.replace("chart.theme.", ""));
		}
	}

	$("#result").height($("#result")[0].scrollHeight);


});

</script>

<?php } ?>
<?php include_once "footer.php" ?>