<?php 
header('Content-Type: application/json');
include_once '../../bootstrap.php';

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login to create chart.'));
	exit;
}

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

if ($_POST['id']) {
	$result = $components->remove(array(
		'_id' => new MongoId($_POST['id']),
		'login_type' => $_SESSION['login_type'],
		'userid' => $_SESSION['userid']
	));

    $db->good_count->remove(array(
        'component_id' => $_POST['id']            
    ));

	if ($result) {

		@unlink(ABSPATH."/thumbnail/".$_POST['id'].".png");
		echo json_encode(array('result' => true));
	} else {
		$result  = array(
			'ok' => false,
			'message' => 'Failed to delete' 
		);

		echo json_encode($result);
	}

} else {

	$result  = array(
		'ok' => false,
		'message' => 'Failed to delete' 
	);

	echo json_encode($result);
}
?>
