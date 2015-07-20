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
));



?>

<p>My List </p>

    <div id="content-container">


		<?php 
		foreach ($rows as $data) { 
			include "box.php";
		} 
		?>
		<div class="summary-box"><div class="summary-normal">

                <div class="name">
                    <span>Wow!</span>
                </div>
                <div class="imagesfield" style="background:white;font-size:20px;text-align:left;">
					<a href="component.php"><div style="padding-left:20px;"><i class='icon-plus' style="margin-top:20px;color:#ddd;" ></i> Chart</div></a>
					<a href="theme.php"><div style="padding-left:20px;"><i class='icon-plus' style="margin-top:20px;color:#ddd;" ></i> Theme</div></a>
					<a href="style.php"><div style="padding-left:20px;"><i class='icon-plus' style="margin-top:20px;color:#ddd;" ></i> Style</div></a>
				</div>
                <div class="summary-info">
                    <div class="title">&nbsp;</div>
                    <div class="content">&nbsp;</div>
                </div>

            </div>
        </div>

    </div>
<?php include_once "footer.php" ?>
