<?php $page_id = 'style'; 

?>

<?php include_once "header.php";

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
			<?php if ($isMy) { ?>
			<div class="group" id="js_html_convert">
				<a class="btn"><i class="icon-upload"></i> Upload File</a> 
				<a class="btn" onclick="select_theme(this)">Select Theme</a>
			</div>
			<input type="file" accept=".less" id="component_load" />

			Element : 
			<select id="key-list" class='input' onchange="location.href='#' + this.value;"></select>

			Sample : 
			<select id="sample_list" class="input">
				<option value="">Select Sample</option>
			</select>
			<?php } ?>

			</div>
			<div style="float:right;padding:10px;padding-top:15px;">
				<?php if ($_GET['id']) { ?>
				<a class='btn' href="/view.php?id=<?php echo $_GET['id'] ?>">View</a>
				<?php } ?>			

				<a class='btn' onclick="coderun()">Run <i class="icon-play"></i></a>

				<?php if ($isMy) { ?>
				<div class='group'>
					<a class="btn" onclick="savecode()">Save</a>
					<?php if ($_GET['id']) { ?>
					<a class="btn" onclick="deletecode()">Delete</a>
					<?php } ?>
				</div>
				<?php } else { ?>

				<?php } ?>

			</div>

		</div>

		<div class="editor-area editor-layout-style">
			<div class="editor-left">
				 <?php include_once "include/style.editor.php" ?>

			</div>
			<div class="editor-right">
 				 <?php include_once "include/style.information.php" ?>
               
				  <?php include_once "include/style.result.php" ?>
			</div>
		</div>
	</div>
</div>

<?php include_once "include/style.script.php" ?>
<?php include_once "footer.php" ?>
