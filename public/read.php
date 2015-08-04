<?php 

include_once '../bootstrap.php';

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$document = array( '_id' => new MongoId($_GET['id']) );

$component = $components->findOne($document);

//ob_start('ob_gzhandler');
header('Content-Type: application/json');

if ($component) {
	unset($component['_id']);

	if ($component['type'] == 'data') {
		$name = "data_".$_GET['id'];

		$data = $db->{$name};

		$temp = $data->find();

		$list = array();
		foreach($temp as $t) {
			$list[] = array(
				'id' => (string)$t['_id'],
				'content' => $t['content'],
				'type' => $t['type']	
			);
		}

		$component['items'] = $list;
	}

	echo json_encode($component);
} else {
	echo json_encode(array());
}

?>
