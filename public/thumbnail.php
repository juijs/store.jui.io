<?php 
include_once '../bootstrap.php';



if ($row['access'] == 'private' || $row['access'] == 'share') {
	header("HTTP/1.0 404 Not Found");
	exit;
}



$root = getcwd();
$path = $root."/thumbnail/".$_GET['id'].".png";



header("Content-Type: image/png");

if (file_exists($path)) {
	readfile($path);
} else {
	readfile("images/chart-sample.jpg");
}
?>
