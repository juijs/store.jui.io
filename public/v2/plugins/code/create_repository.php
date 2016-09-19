<?php 
error_reporting(E_ALL);
include_once '../../../../bootstrap.php';

use Cz\Git\GitRepository;

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

//$_POST = array_filter($_POST, 'strip_tags');

$type = 'code';
// must be saved 
$document = array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid'],
	'username' => $_SESSION['username'],
	'avatar' => $_SESSION['avatar'],
	'type' => $type,
	'access' => $_POST['access'],
	'title' => $_POST['title'],
	'name' => $_POST['name'],
	'description' => $_POST['description'],
	'license' => $_POST['license'],
	'update_time' => time()
);

// 새로운 정보 업데이트 
if ($_POST['id']) {
	$result = $components->update(array(
		'_id' => new MongoId($_POST['id']),
		'login_type' => $_SESSION['login_type'],
		'userid' => $_SESSION['userid']
	), array('$set' => $document), array('upsert' => true) );

} 
// 저장 
else {

	$components->insert($document);

	$document['component_id'] = (string)$document['_id'];

	$result  = array(
		'ok' => $document['_id'] ? true : false 	
	);
}

function is_remote_repository ($name) {
	$is_http = strpos($name, "http://") !== false;
	$is_https = strpos($name, "https://") !== false;

	$is_remote = $is_http || $is_https;

	return $is_remote; 
}

if ($result['ok']) {

	// 디렉토리 만들기 
	// git init 
	// readme 커밋하기 
	// 리턴하기 
	$id = $_POST['id'] ? $_POST['id'] : (string)$document['_id'];
	
	$dir = REPOSITORY.'/'.$id. '/';
	mkdir($dir, 0777, true);


	if ($_POST['init']) {

		if (is_remote_repository($document['name'])) {
			// clone repository 
			$repo = GitRepository::cloneRepository($document['name'], $dir);

			// update code project name 
			$real_code_name = GitRepository::extractRepositoryNameFromUrl($document['name'] );

			// TODO: update mongodb 
			//
			
			// TODO: add code readme.md 

		} else {

			$repo = GitRepository::init($dir);

			// create a new file in repo
			$filename = $repo->getRepositoryPath() . '/README.md';
			file_put_contents($filename, "# Init Project");

			// commit
			$repo->addFile($filename);
			$repo->commit('init project');

		}
	}

	echo json_encode(array('result' => true, 'id' => $id ));

} else {
	echo json_encode(array('result' => false));
}

?>
