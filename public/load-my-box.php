<?php 
include_once "../bootstrap.php" ;

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$sort_type = $_GET['sort'] ? $_GET['sort'] : 'update_time';
$max = isset($_GET['max']) ? $_GET['max'] : 5;

$sort = array();
$sort[$sort_type] = -1; 
$sort['update_time'] = -1;


$rows = $components->find(array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
))->sort($sort);


$checkPoint = false;
$count = 0;
foreach ($rows as $data) { 
	$id = (string)$data['_id'];
	if ($id == $_GET['lastId']) {
		$checkPoint = true;
		continue;
	}

	if ($checkPoint) {
		include "box.php"; 
		$count++;
		if ($count >= $max) break;
	}
} 

?>