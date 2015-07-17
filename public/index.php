<?php include_once "header.php" ?>

		<div>
			<span class="content-btn"><a href="?sort=update_time" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'good' ? 'on' : 'off' ?>">최신순</a><a href="?sort=good" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'good' ? 'on' : 'off' ?>">좋아요순</a></span>
		</div>
<?php 


// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$sort_type = $_GET['sort'] ? $_GET['sort'] : 'update_time';

$sort = array();
$sort[$sort_type] = -1; 

$rows = $components->find(array(
    'access' => 'public'            
))->sort($sort);

?>

    <div id="content-container">


		<?php foreach ($rows as $data) { 
		
			$id = (string)$data['_id'];

			if ($data['login_type'] == $_SESSION['login_type'] && $data['userid'] == $_SESSION['userid']) {
				$link = ($data['type'] ? $data['type'] : 'manager').".php?id=".($id);
			} else {
				$link = "view.php?id=".($id);
			}
		?>
		<div class="summary-box"><div class="summary-normal">
                <div class="name">
                    <span><img src="<?php echo $data['avatar'] ?>" width="30" height="30" class='avatar' align='absmiddle'/>&nbsp;<?php echo $data['username'] ?></span>
                    <span class="good" style="float:right;overflow:auto;display:inline-block;">
						<a href="javascript:void(good('<?php echo $id?>'))"><img src="images/good.png" /></a> 
						<span id="good_count_<?php echo $id?>"><?php echo $data['good'] ? $data['good'] : 0 ?></span>
					</span>
                </div>
                <div class="imagesfield">
					<a href="<?php echo $link ?>"><img class="chart-image" src="<?php echo $data['sample'] ? $data['sample'] : 'images/chart-sample.jpg' ?>"></a>
				</div>
                <div class="summary-info">
                    <div class="title"><?php echo $data['title'] ? $data['title'] : '&nbsp;' ?></div>
                    <div class="content"><?php echo $data['description'] ? $data['description'] : '&nbsp;' ?></div>
                </div>
            </div>
        </div>
		<?php } ?>
    </div>
<?php include_once "footer.php" ?>
