<?php 

$page_id = 'list';



include_once "include/generate.meta.php";
$meta = implode(PHP_EOL, $metaList);

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
	'access' => 'public',
	'type' => array('$ne' => 'code')	
));

$total = $rows->count();


$page = $_GET['page'];

if (!$page) $page = 1;

$page = intval($page);

$nextPage = $page+1;
$prevPage = $page-1;

$limit = 12;
$skip = ($page - 1) * $limit;

if ($page * $limit > $total) {
	$nextPage = -1; 
}

$rows = $rows->sort($sort)->skip($skip)->limit($limit);

?>

<div style="margin-top:28px"></div>
<div id="content-container">
<?php  

$boxCount = 0; 
foreach ($rows as $data) {  
//	if ($boxCount++ > 0) break;

	include "box.php";   
} ?>
</div>

<?php include_once "include/store.list.php" ?>
<?php include_once "footer.php" ?>
