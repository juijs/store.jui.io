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
))->limit(20);



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
		<div class="summary-box"><div class="summary-normal">

                <div class="name">
                    <span>Select Component!</span>
                </div>
                <div class="imagesfield" style="background:white;font-size:20px;text-align:center;height:245.5px;">
					<a href="component.php" class='btn' style='width:70%;margin-bottom:10px;'><i class='icon-plus' ></i> Chart Brush</a>
					<a href="theme.php" class='btn' style='width:70%;margin-bottom:10px;'><i class='icon-plus' ></i> Chart Theme</a>
					<a href="style.php" class='btn' style='width:70%;margin-bottom:10px;'><i class='icon-plus' ></i> UI Theme</a>
				</div>
            </div>
        </div>

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
