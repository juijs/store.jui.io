<?php 
error_reporting(E_ALL);
include_once '../bootstrap.php';

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}

header('Content-Type: application/json');

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$_POST = array_filter($_POST, 'strip_tags');

$document = array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid'],
	'username' => $_SESSION['username'],
	'avatar' => $_SESSION['avatar'],
	'type' => $_POST['type'],
	'access' => $_POST['access'],
	'title' => $_POST['title'],
	'name' => $_POST['name'],
	'description' => $_POST['description'],
	'component_code' => $_POST['component_code'],
	'sample_code' => $_POST['sample_code'],
	'sample' => $_POST['sample'],
	'update_time' => time()
);


if ($_POST['id']) {
	$result = $components->update(array(
		'_id' => new MongoId($_POST['id']),
		'login_type' => $_SESSION['login_type'],
		'userid' => $_SESSION['userid']
	), array('$set' => $document), array('upsert' => true) );
} else {

	$prevData = $components->findOne(array(
		'name' => $_POST['name']
	));

	if ($prevData['_id']) {
		echo json_encode(array('result' => false, 'message' => 'Name is already exists.'));
		exit;
	}

	$components->insert($document);

	$result  = array(
		'ok' => $document['_id'] ? true : false 	
	);
}

if ($result['ok']) {
	$id = $_POST['id'] ? $_POST['id'] : (string)$document['_id'];

	echo json_encode(array('id' => $id, 'result' => true));

	// save component file 

	$file = ROOT."/public/api/".$document['name'].".js";
	if ($document['name'] && $document['access'] != 'private') {
		$code = print_comment($document).print_minified($document);
		file_put_contents($file, $code);
	} else {
		// if access is private, file is removed.
		@unlink($file);
	}

} else {
	echo json_encode(array('result' => false));
}



function print_minified($data) {
	
	$name = $data['name'];
	$minified = $data['minified'];

	$code = $data['component_code'];

	return $code;
}

function print_comment($data) {
	$arr = array();

	$arr[] = $data['title'];
	$arr[] = "";
	$arr[] = $data['description'];
	$arr[] = "";
	$arr[] = "@author ".$data['username'];

	
	$all_string = implode("\r\n", $arr);

	$arr = explode("\n", $all_string);

	$result = "/**\r\n";
	foreach ($arr as $str) { 
		$str = str_replace('\r', '', $str);
		$result .= " * ".$str."\r\n";
	}

	$result .= " */";


	return $result."\r\n";
}


?>
