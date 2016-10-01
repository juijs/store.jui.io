<?php 
include_once '../../bootstrap.php';

// connect
$m = new MongoClient();

// select a rowbase
$db = $m->store;

$components = $db->components;

$row = $components->findOne(array(
	'_id' => new MongoId($_GET['id'])
));

$isMy = true;
if ($_GET['id'] && ($row['login_type'] != $_SESSION['login_type'] || $row['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}



if ($row['access'] == 'private' && !$isMy) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$only = $_GET['only'];

$title = $row['title'];
$id = (string)$row['_id'];
$description = str_replace("\r\n", "\\r\\n", $row['description']);
$username = $row['username'];

if (!$row['type']) $row['type'] = 'component';


include_once V2_PLUGIN."/{$row['type']}/embed.php";

?>
