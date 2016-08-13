<?php $page_id = 'page';

error_reporting(E_ALL);
include_once '../bootstrap.php';
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

if ($data['type']) { 
	$type = $data['type'];
}

if (!$type) $type = 'page'; 

include_once PLUGIN."/$type/meta.php";
$meta = implode(PHP_EOL, $metaList);
include_once "header.php";

?>

<style type="text/css">
.CodeMirror {
	height: 100%;
}
.lint-error {font-family: arial; font-size: 70%; background: #ffa; color: #a00; padding: 2px 5px 3px; }
.lint-error-icon {color: white; background-color: red; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 7px;}
body { overflow: hidden; }
</style>

<div class="<?php echo $type ?>-editor editor-container view-all <?php echo $isMy ? 'my' : '' ?>">
<div class="editor-content <?php echo ($has_toolbar) ? 'has-toolbar' : '' ?> <?php echo ($has_statusbar) ? 'has-statusbar' : '' ?>">

		<?php if ($has_toolbar) { ?>
		<div class="editor-toolbar">
			<div style='float:left;padding:10px;'>
				<a class="btn" id="library"><i class='icon-gear'></i> Setting</a>
				<?php include_once PLUGIN."/$type/toolbar-left.php"; ?>
			</div>
			<div style="float:right;padding:10px;">
				<?php include_once PLUGIN."/$type/toolbar-right.php"; ?>
				<?php if ($_GET['id']) { ?>
				<a class='btn' href="/view.php?id=<?php echo $_GET['id'] ?>"><i class="icon-report2"></i> View</a>
				<?php } ?>			

				<?php if ($isMy) { ?>

					<a class="btn" onclick="savecode()"><i class="icon-edit"></i> Save</a>
					<?php if ($_GET['id']) { ?>
					<a class="btn" onclick="deletecode()"><i class="icon-trashcan"></i> Delete</a>
					<?php } ?>

				<?php } else { ?>

				<?php } ?>

			</div>

		</div>
		<?php } ?>

		<div class="editor-area view-only">
			<div class="editor-left">

				<?php include_once PLUGIN."/$type/editor.php" ?>

			</div>

			<div class="editor-right">
				<?php include_once PLUGIN."/$type/result.php" ?>
			</div>

		</div>
	</div>

	<?php if ($has_statusbar)  { ?>
		<div class="editor-statusbar">
				<?php include_once PLUGIN."/$type/statusbar.php"; ?>
		</div>
	<?php } ?>
</div>
<?php include_once PLUGIN."/$type/script.php" ?>
<?php include_once "include/script.php" ?>
<?php include_once "footer.php" ?>
