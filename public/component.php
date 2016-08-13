<?php $page_id = 'component'; 

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

<script type="text/javascript">

/* @Deprecated */
function showFullscreen(type) {
	$(".view-" + type).addClass('fullscreen').css({ 'z-index' : 99999 })
	componentCode.refresh();
	sampleCode.refresh();
}

/* @Deprecated */ 
function closeFullscreen(type) {
	$(".view-" + type).removeClass('fullscreen').css({ 'z-index' : 0 })
	componentCode.refresh();
	sampleCode.refresh();
}

</script>

<style type="text/css">
.CodeMirror {
	height: 100%;
}
.lint-error {font-family: arial; font-size: 70%; background: #ffa; color: #a00; padding: 2px 5px 3px; }
.lint-error-icon {color: white; background-color: red; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 7px;}
body { overflow: hidden; }
</style>

<div class="editor-container view-all <?php echo $isMy ? 'my' : '' ?>">
	<div class="editor-content has-toolbar has-statusbar">
		<div class="editor-toolbar">
			<div style='float:left;padding:10px;'>
				<a class="btn" id="library"><i class='icon-gear'></i> Setting</a>
			</div>
			<div style="float:right;padding:10px;">
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

				<?php include_once "include/component.editor.php" ?>

			</div>

			<div class="editor-right">
				<?php include_once "include/component.result.php" ?>
			</div>

		</div>
	</div>

		<div class="editor-statusbar">
			<div class="group" style="float:left; padding:10px;">

				<label style='margin-right:10px'><small><strong>Ctrl-F</strong> Start searching</small> </label>
				<label style='margin-right:10px'><small><strong>Ctrl-G</strong> Find Next</small> </label>
				<label style='margin-right:10px'><small><strong>Shift-Ctrl-F</strong> Replace</small> </label>
				
				<i class="icon-help"></i>
			</div>
		</div>
</div>
<?php include_once "include/component.script.php" ?>
<?php include_once "footer.php" ?>
