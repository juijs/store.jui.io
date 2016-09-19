<?php 
error_reporting(E_ALL);
header('Content-Type: application/json');
include_once '../../bootstrap.php';
include_once 'common.php';

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login.'));
	exit;
}

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;
$good_count = $db->good_count;

$good_count->ensureIndex(array(
	'component_id' => 1,
	'login_type' => 1,
	'userid' => 1
));

$data = $good_count->findOne(array(
	'component_id' => $_POST['id'],
	'login_type' => $_SESSION['login_type'],
	'userid' => $_SESSION['userid']
));

if ($data) {

// 카운트 목록 지우기 
	 $good_count->remove(array(
		'component_id' => $_POST['id'],
		'login_type' => $_SESSION['login_type'],
		'userid' => $_SESSION['userid']
	), array ('justOne' => true ));

	// 이미 있으면 -1 
	$components->update(array(
		'_id' => new MongoId($_POST['id'])
	), array(
		'$inc' => array("good" => -1)
	));

	$row = $components->findOne(array(
		'_id' => new MongoId($_POST['id'])
	));

	echo json_encode(array('id' => $id, 'isLiked' => false, 'good' => number_format($row['good']), 'good_rate' => get_good_rate($row['good']), 'result' => true));

} else {
	// 없으면 더하기 1 

	$document = array(
		'component_id' => $_POST['id'],
		'login_type' => $_SESSION['login_type'],
		'userid' => $_SESSION['userid']
	);

	$good_count->insert($document);

	if ($document['_id']) {
		$components->update(array(
			'_id' => new MongoId($_POST['id'])
		), array(
			'$inc' => array("good" => 1)
		));

		$row = $components->findOne(array(
			'_id' => new MongoId($_POST['id'])
		));

		echo json_encode(array('id' => $id, 'good' => number_format($row['good']), 'good_rate' => get_good_rate($row['good']), 'result' => true));

	}

}
?>