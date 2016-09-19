<?php $page_id = 'editor';

$is_viewer = true; 

include_once '../../bootstrap.php';
include_once 'common.php';
// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$data = $components->findOne(array('_id' => new MongoId($_GET['id'])));

$isMy = true;
if ($_GET['id'] && ($data['login_type'] != $_SESSION['login_type'] || $data['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}

$type = $_GET['type']; // plugin name
$id = $_GET['id'];
if ($data['type']) { 
	$type = $data['type'];
}

if (!$type) $type = 'page'; 

include_once V2_PLUGIN."/$type/meta.php";
$meta = implode(PHP_EOL, $metaList);

	$embed_url = "embed.php?id=".$id."&only=result";
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>Code Viewer - JENNIFER UI: Store</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<?php echo $meta ?>
<link href="css/flat.css" rel="stylesheet" />
<link href="css/flat-responsive.css" rel="stylesheet" />
<link href="css/edit.css" rel="stylesheet" />
<link href="css/edit-responsive.css" rel="stylesheet" />

<link href="<?php echo V2_PLUGIN_URL ?>/<?php echo $type ?>/resource/editor.css" rel="stylesheet" />
<link href="css/viewer.css" rel="stylesheet" />
<style type="text/css">
.CodeMirror {
	height: 100%;
}
.lint-error {font-family: arial; font-size: 70%; background: #ffa; color: #a00; padding: 2px 5px 3px; }
.lint-error-icon {color: white; background-color: red; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 7px;}
body { overflow: hidden; }
</style>

</head>
<body class="jui flat">

<div class="<?php echo $type ?>-editor editor-container view-all <?php echo $isMy ? 'my' : '' ?>">
<div class="editor-content has-toolbar">
		<div class="editor-toolbar">
			<div style="float:left;padding:10px" class="editor-left-toolbar">
				<?php include_once V2_PLUGIN."/$type/toolbar-left.php" ?>
			</div>
			<div style="float:right;padding:10px" class="editor-right-toolbar">
				<?php include_once V2_PLUGIN."/$type/toolbar-right.php" ?>
			</div>
		</div>

		<div class="editor-area view-only">
			<div class="editor-left">
				<?php include_once V2_PLUGIN."/$type/editor.php" ?>
			</div>
			<div class="editor-right">
				<?php include_once V2_PLUGIN."/$type/result.php" ?>
			</div>
		</div>
	</div>
</div>
<?php @include_once V2_PLUGIN."/$type/script.php" ?>
<?php include_once "include/script.php" ?>
<?php include_once "footer.php" ?>
<script type="text/javascript">
$(function () {
	$(".fullscreen-btn").on('click', function () {

		if (mobilecheck())
		{
			window.open('<?php echo $embed_url ?>', 'fullscreen-slider');
		} else {
			toggleFullScreen("#preview-frame");
		}

	});

});

</script>
</body>
</html>
