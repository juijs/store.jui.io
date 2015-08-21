<?php 
include_once "../bootstrap.php" ;

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$sort_type = $_GET['sort'] ? $_GET['sort'] : 'good';
$max = isset($_GET['max']) ? $_GET['max'] : 5;



$sort = array();
$sort[$sort_type] = -1; 


$rows = $components->find(array(
    'access' => 'public',
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