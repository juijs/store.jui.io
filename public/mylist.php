<?php 

$page_id = "mylist";

?>
<?php include_once "header.php" ?>
 
<?php 
if (!$_SESSION['login']) {
	echo "<script>alert('Please login.');location.href='/';</script>";
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


		<?php foreach ($rows as $data) { 
				$link = ($data['type'] ? $data['type'] : 'manager').".php?id=".((string)$data['_id']);
		
		?>
		<div class="summary-box"><a href="<?php echo $link ?>"><div class="summary-normal">
                <div class="name">
                    <span><img src="<?php echo $data['avatar'] ?>" width="30" height="30" class='avatar' align='absmiddle'/>&nbsp;<?php echo $data['username'] ?></span>
                    <span class="good"><img src="images/good.png"> <?php echo $data['good'] ? $data['good'] : 0 ?></span>
                </div>
                <div class="imagesfield"><img class="chart-image" src="<?php echo $data['sample'] ? $data['sample'] : 'images/chart-sample.jpg' ?>"></div>
                <div class="summary-info">
                    <div class="title"><?php echo $data['title'] ? $data['title'] : '&nbsp;' ?></div>
                    <div class="content"><?php echo $data['description'] ? $data['description'] : '&nbsp;' ?></div>
                </div>
            </div></a>
        </div>
		<?php } ?>
		<div class="summary-box"><div class="summary-normal">

                <div class="name">
                    <span>Wow!</span>
                </div>
                <div class="imagesfield" style="background:white;font-size:20px;text-align:left;">
					<a href="manager.php"><div style="padding-left:20px;"><i class='icon-plus' style="margin-top:20px;color:#ddd;" ></i> Chart</div></a>
					<a href="theme.php"><div style="padding-left:20px;"><i class='icon-plus' style="margin-top:20px;color:#ddd;" ></i> Theme</div></a>
				</div>
                <div class="summary-info">
                    <div class="title">&nbsp;</div>
                    <div class="content">&nbsp;</div>
                </div>

            </div>
        </div>

    </div>
<?php include_once "footer.php" ?>
