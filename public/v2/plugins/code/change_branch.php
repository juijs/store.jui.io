<?php 
error_reporting(E_ALL);
include_once '../../../../bootstrap.php';

use Cz\Git\GitRepository;

if (!$_SESSION['login']) {
	echo json_encode(array("result"=> false, 'message' => 'Please login'));
	exit;
}

header('Content-Type: application/json');


	$id = $_POST['id'];
	
	$dir = REPOSITORY.'/'.$id. '/';

    $repo = new GitRepository($dir);

    $repo->checkout($_POST['branch_name']);

    //var_dump($repo->getCurrentBranchName());

	echo json_encode(array('result' => true ));
?>
