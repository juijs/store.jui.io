<?php $page_id = 'component'; 

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
.lint-error {font-family: arial; font-size: 70%; background: #ffa; color: #a00; padding: 2px 5px 3px; }
.lint-error-icon {color: white; background-color: red; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 7px;}
body { overflow: hidden; }
</style>

<div class="editor-container view-all">
	<div class="editor-content">
		<div class="editor-area">
			<div class="editor-left">

				<?php include_once "include/component.editor.php" ?>

				<?php include_once "include/component.sample.php" ?>

			</div>

			<div class="editor-right">
                
				<?php include_once "include/component.information.php" ?>


				<?php include_once "include/component.result.php" ?>
			</div>
		</div>
	</div>
</div>
<?php include_once "include/component.script.php" ?>
<?php include_once "footer.php" ?>
