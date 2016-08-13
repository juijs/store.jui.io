<?php $page_id = 'theme'; 

include_once "include/generate.meta.php";
$meta = implode(PHP_EOL, $metaList);
include_once "header.php";


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



?>

<style type="text/css">
.CodeMirror {
	height: 100%;
}
body { overflow: hidden; }
</style>

<div class="editor-container view-all">
	<div class="editor-content has-toolbar">
		<div class="editor-toolbar">
			<div style='float:left;padding:10px;'>
				<a class="btn" id="library"><i class='icon-gear'></i> Setting</a>
			</div>
			<div style="float:right;padding:10px;padding-top:15px;">
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
		<div class="editor-area view-only">
			<div class="editor-left">
				<?php include_once "include/theme.editor.php" ?>
			</div>
			<div class="editor-right">
                <?php //include_once "include/theme.information.php" ?>

				<?php include_once "include/theme.result.php" ?>
				
			</div>
		</div>
	</div>
</div>
<?php include_once "include/theme.script.php" ?>
<?php include_once "footer.php" ?>
