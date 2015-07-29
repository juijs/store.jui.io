<?php 

$page_id = "mylist";

?>
<?php include_once "header.php" ?>
 
<?php 
if (!$_SESSION['login']) {
	echo "<script>alert('Please login.');location.href='/login_form.php';</script>";
	exit;
}

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$rows = $components->find(array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
))->sort(array(
	'update_time' => -1
))->limit(100);



?>
	<div>
		<span class="content-btn"><a href="?sort=update_time" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'good' ? 'on' : 'off' ?>">최신순</a><a href="?sort=good" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'good' ? 'on' : 'off' ?>">좋아요순</a></span>
	</div>

	<div style='margin-top:28px;'></div>

    <div id="content-container">


		<?php 
		foreach ($rows as $data) { 
			include "box.php";
		} 
		?>
    </div>

<script type="text/javascript">
$(function() {
	$('#content-container').masonry({
	  // options
	  itemSelector: '.summary-box',
	  isFitWidth: true
	});
});

</script>
<?php include_once "footer.php" ?>
