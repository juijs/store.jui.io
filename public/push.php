<?php 
include_once '../bootstrap.php';

header('Content-Type: application/json');

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$name = "data_".$_POST['id'];

$data = $db->{$name};
$components = $db->components;

$_POST = array_filter($_POST, 'strip_tags');

$type = $_POST['type'];

if (!$type) {
	$type = 'json';
}

if (!in_array($type, array('json', 'text'))) {
	echo json_encode(array('result' => false));
	exit;
}

$componentData = $components->findOne(array(
	'_id' => new MongoId($_POST['id'])
));

if (!$componentData) {
	echo json_encode(array('result' => false));
	exit;
}

if ($componentData['access'] != 'public') {
	echo json_encode(array('result' => false));
	exit;
}


if ($type == 'text') {
	$content = array('text' => $_POST['data']);
} else {
	$content = json_decode($_POST['data']);
	$message = "";
	 switch(json_last_error())
    {
        case JSON_ERROR_DEPTH:
            $message = 'Maximum stack depth exceeded';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $message = 'Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $message = 'Syntax error, malformed JSON';
        break;

    }

	if ($message) {
		echo json_encode(array('result' => false, 'message' => $message));
		exit;
	}
}



// must be saved 
$document = array(
	'componentId' => $_POST['id'],
	'type' => $type,
	'content' => $content,
	'update_time' => time()
);

$data->insert($document);
$result  = array(
	'ok' => $document['_id'] ? true : false 	
);

if ($result['ok']) {
	$id = (string)$document['_id'];

	echo json_encode(array('id' => $id, 'result' => true));
} else {
	echo json_encode(array('result' => false));
}

?>
