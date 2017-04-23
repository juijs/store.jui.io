<?php 
include_once '../../bootstrap.php';

$arr = explode("/", $_SERVER['PATH_INFO']);

array_shift($arr);
$id = array_shift($arr);
$filename = implode("/", $arr);

// connect
$m = new MongoClient();

// select a rowbase
$db = $m->store;

$components = $db->components;

$row = $components->findOne(array(
	'_id' => new MongoId($id)
));

$isMy = true;
if ($id && ($row['login_type'] != $_SESSION['login_type'] || $row['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}

if ($row['access'] == 'private' && !$isMy) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

if (!$row['type']) $row['type'] = 'component';

// 플러그인 마다 gist 를 둔다. 
include_once V2_PLUGIN."/{$row['type']}/gist.php";

?>