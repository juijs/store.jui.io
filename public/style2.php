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
	<div class="editor-content">
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

<?php include_once "include/style2.script.php" ?>
<?php include_once "footer.php" ?>
