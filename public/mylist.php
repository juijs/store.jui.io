<?php
error_reporting(E_ALL);

$page_id = "mylist";

include_once "include/generate.meta.php";
$meta = implode(PHP_EOL, $metaList);
include_once "header.php";

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

$mode = $_GET['mode'] ? $_GET['mode'] : 'list';

$modeName = $mode == 'list' ? 'Preview' : 'List';

?>
<div>
	<span class="content-btn">
		<a href="?mode=<?php echo ($mode == 'list' ? 'preview' : 'list') ?>" class="btn-simple"><?php echo $modeName ?> Mode</a>
		<a href="?sort=update_time&mode=<?php echo $mode ?>" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'good' ? 'on' : 'off' ?>">Sort by date</a>
		<a href="?sort=good&mode=<?php echo $mode ?>" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'good' ? 'on' : 'off' ?>">Sort by score</a>
	</span>
</div>

<div style='margin-top:28px;'></div>

<div id="content-container">
<?php 

	if ($rows->count() > 0) { 
		foreach ($rows as $data) { 
			if ($mode == 'list') { 
				include "box.list.php";
			} else { 
				include "box.php";
			}

	} 
	} else {
?>
<div class="summary-box box-list">
	<div class="summary-normal">
		<div class="summary-info">
			<div class="title"></div>
			<div class="content"> Create a your new code.</div>
		</div>
	</div>
</div>


	<?
	}
	?>
</div>

<?php include_once "include/store.list.php" ?>


<script type="text/javascript">
window.deletecode = function deletecode (id) {
	if (confirm("Delete this component?\r\ngood count is also deleted.")) {
		$.post("/delete.php", { id : id }, function(res) {
			if (res.result) {
				location.href = '/mylist.php'; 	
			} else {
				alert(res.message ? res.message : 'Failed to delete');
			}
		});
	}
}
</script>

</body>
</html>
