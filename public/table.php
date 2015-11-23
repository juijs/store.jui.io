<?php 

$page_id = 'list';
include_once "header.php" ;

?>

		<div>
			<span class="content-btn">
				<a href="?sort=update_time" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'update_time' ? 'on' : 'off' ?>">Sort by date</a>
				<a href="?sort=good" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'update_time' ? 'on' : 'off' ?>">Sort by score</a>
			</span>
		</div>
<?php 


// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$sort_type = $_GET['sort'] ? $_GET['sort'] : 'good';

$sort = array();
$sort[$sort_type] = -1; 

$rows = $components->find(array(
    'access' => 'public'            
))->sort($sort)->limit(5);

?>

<style>
.summary-box {
	width:300px;
}
.summary-normal {
	position:relative;
}
.summary-normal .imagesfield iframe {
	height:100%;
}	

.summary-normal .name {
	position:absolute;
	left:0px;
	right:0px;
	top:0px;
	height:53px;
	z-index:99999;
	opacity:0;
}

.summary-box:hover .name {
	opacity:1;
	color:white;
	border-bottom:0px;
}

.summary-box:hover .imagesfield a {
	background:black;
	opacity:0.5;
}
</style>

<div style="margin-top:28px"></div>
<div id="content-container">
<?php  foreach ($rows as $data) {  include "box.php";   } ?>
</div>

<?php include_once "include/store.list.php" ?>
<?php include_once "footer.php" ?>
