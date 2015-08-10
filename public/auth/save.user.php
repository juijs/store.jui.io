<?php
// connect
$m = new MongoClient();
// select a database
$db = $m->store;

$users = $db->users;

$document = array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid'],
	'username' => $_SESSION['username'],
	'avatar' => $_SESSION['avatar'],
	'update_time' => time()
);

$users->update(array(
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
), $document, array(
	'upsert' => true	
));
?>
