<?php 
include_once "../bootstrap.php" ;

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$document = array( '_id' => new MongoId($_GET['id']) );

$component = $components->findOne($document);


$sort_type = $_GET['sort'] ? $_GET['sort'] : 'update_time';


$sort = array();
$sort[$sort_type] = -1; 
$sort['update_time'] = -1;


$rows = $components->find(array(
    'access' => 'public',
	'_id' => new MongoId($_GET['id']),
	'update_time' => array('$gt' => $component['update_time'])
))->sort($sort)->limit(1);


foreach ($rows as $data) { 
	include "box.php"; 
} 

?>