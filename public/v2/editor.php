<?php $page_id = 'editor';

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

if ($data['type'] && $data['type'] != 'remotes') { 
	$type = $data['type'];
} else {
    //$type = 'code';
}

if (!$type) $type = 'page'; 

include_once V2_PLUGIN."/$type/meta.php";
$meta = implode(PHP_EOL, $metaList);

$type_name = $type_list[$type]['name'];
$type_image = get_svg_image($type_list[$type]['img']);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> -->
<title>JENNIFER UI: Store</title>
<link rel="shortcut icon" href="<?php echo URL_ROOT ?>/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo URL_ROOT ?>/favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
<script type="text/javascript" src="<?php echo URL_ROOT ?>/bower_components/jquery/dist/jquery.min.js"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript" src="<?php echo V2_URL ?>/js/main.js"></script>
<?php echo $meta ?>
<link href="<?php echo V2_URL ?>/css/flat.css" rel="stylesheet" />
<link href="<?php echo V2_URL ?>/css/flat-responsive.css" rel="stylesheet" />
<link href="<?php echo V2_URL ?>/css/edit.css" rel="stylesheet" />
<link href="<?php echo V2_URL ?>/css/edit-responsive.css" rel="stylesheet" />
<link href="<?php echo V2_PLUGIN_URL ?>/<?php echo $type ?>/resource/editor.css" rel="stylesheet" />
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

<?php include_once "nav-editor.php" ?>

<div class="<?php echo $type ?>-editor editor-container view-all <?php echo $isMy ? 'my' : '' ?>">
<div class="editor-content has-toolbar">

		<div class="editor-toolbar">
			<div style='float:left;padding:10px;' class="editor-left-toolbar">
					<a class="button button-link" id="library" title="<?php echo $type_list[$type]['name'] ?>"><i class="icon-tool"></i> PROJECT INFO</a>
					<?php include_once V2_PLUGIN."/$type/toolbar-left.php"; ?>
			</div>
			<div style="float:right;padding:10px;" class="editor-right-toolbar">
					<?php include_once V2_PLUGIN."/$type/toolbar-right.php"; ?>
					<?php if ($_GET['id']) { ?>
						<a class='button' href="/v2/view.php?id=<?php echo $_GET['id'] ?>" title="go a view page"><i class="icon-search"></i> VIEW</a>
					<?php } ?>			

				<?php if ($isMy) { ?>
				<a class="button active" onclick="savecode()" title="Save"><i class="icon-clip"></i> SAVE <?php echo ($type == 'code') ? 'Project Info' : '' ?></a>
				<?php if ($_GET['id']) { ?>
				<a class="button danger  active" onclick="deletecode()"><i class="icon-trashcan"></i> DELETE</a>
				<?php } ?>
				<?php } ?>

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
<?php include_once "modals.php" ?>
</body>
</html>
