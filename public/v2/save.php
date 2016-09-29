<?php 
error_reporting(E_ALL);
include_once '../../bootstrap.php';

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
$history = $db->components_history;

//$_POST = array_filter($_POST, 'strip_tags');

// must be saved 
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
	'html_code' => $_POST['html_code'],
	'css_code' => $_POST['css_code'],
	'theme_name' => $_POST['theme_name'],
	'sample_type' => $_POST['sample_type'],
	'sample' => $_POST['sample'],
	'data_type' => $_POST['data_type'],
	'license' => $_POST['license'],
	'resources' => $_POST['resources'],
	'preprocessor' => $_POST['preprocessor'],
	'update_time' => time()
);

$type = $_POST['type'];

include_once V2_PLUGIN."/$type/save.php";


if ($_POST['id']) {
	$prevData = $components->findOne(array(
		'_id' => new MongoId($_POST['id'])
	));

	$revData = $history->find(array(
		'component_id' => $_POST['id']
	))->sort(array(
		'rev' => -1	
	))->limit(1);

	if ($revData && $revData->count(true) > 0) {
		foreach($revData as $_rev) {
			$rev = $_rev['rev'];
		}

	} else {
		$rev = 0;
	}

	$result = $components->update(array(
		'_id' => new MongoId($_POST['id']),
		'login_type' => $_SESSION['login_type'],
		'userid' => $_SESSION['userid']
	), array('$set' => $document), array('upsert' => true) );

	// rev 번호 얻어오기 
	// rev 번호, component_id 추가 해서 현재 document 복사 
	// 새로운 rev 번호 추가 
	$prevData['component_id'] = $_POST['id'];
	$prevData['rev'] = $rev+1;
	unset($prevData['_id']);

	$history->insert($prevData);

} else {

	/*
	$prevData = $components->findOne(array(
		'name' => $_POST['name']
	));

	if ($prevData['_id']) {
		echo json_encode(array('result' => false, 'message' => 'Name is already exists.'));
		exit;
	} */

	$components->insert($document);

	$document['component_id'] = (string)$document['_id'];
	$document['rev'] = 0;
	$history->insert($document);

	$result  = array(
		'ok' => $document['_id'] ? true : false 	
	);
}

if ($result['ok']) {
	$id = $_POST['id'] ? $_POST['id'] : (string)$document['_id'];
	

	$thumbnail_url = "http://{$_SERVER['HTTP_HOST']}/v2/embed.php?id={$id}&only=true";
	$thumbnail_path = ABSPATH."/thumbnail/{$id}.png";

	// auto created sample image 
	$root = getcwd();
	shell_exec(escapeshellcmd("webshot --window-size=800/600 --render-delay=500 {$thumbnail_url} {$thumbnail_path}"));

	echo json_encode(array('id' => $id, 'result' => true));
	// create static file 
	$data = $document;

	@include_once V2_PLUGIN."/$type/make.static.file.php";

} else {
	echo json_encode(array('result' => false));
}

?>
