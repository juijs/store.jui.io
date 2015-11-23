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


$sort_type = $_GET['sort'] ? $_GET['sort'] : 'update_time';

$sort = array();
$sort[$sort_type] = -1; 


$rows = $components->find(array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
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
<div>
	<span class="content-btn">
		<a href="?sort=update_time" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'good' ? 'on' : 'off' ?>">Sort by date</a>
		<a href="?sort=good" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'good' ? 'on' : 'off' ?>">Sort by score</a>
	</span>
</div>

<div style='margin-top:28px;'></div>

<div id="content-container">
	<?php 
	foreach ($rows as $data) { 
		include "box.php";
	} 
	?>
</div>

<?php include_once "include/store.list.php" ?>

</body>
</html>
