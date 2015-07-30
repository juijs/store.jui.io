<?php $page_id = 'map'; 

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
		<div class="editor-area">
			<div class="editor-left">

				<?php include_once "include/map.editor.php" ?>

				<?php include_once "include/map.sample.php" ?>

			</div>

			<div class="editor-right">
                
				<?php include_once "include/map.information.php" ?>


				<?php include_once "include/map.result.php" ?>
			</div>
		</div>
	</div>
</div>
<?php include_once "include/map.script.php" ?>
<?php include_once "footer.php" ?>
