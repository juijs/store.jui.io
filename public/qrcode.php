<?php 

include_once '../bootstrap.php';

if ($row['access'] == 'private' || $row['access'] == 'share') {
	header("HTTP/1.0 404 Not Found");
	exit;
}

use Endroid\QrCode\QrCode;

header('Content-Type: image/png');

$file = CACHE."/qrcode/".$_GET['id'].".png";

if (!file_exists($file)) {
	$obj = "http://store.jui.io/v2/embed.php?id=".$_GET['id'];

	$qrCode = new QrCode();
	$qrCode
		->setText($obj)
		->setSize(200)
		->setPadding(10)
		->setErrorCorrection('high')
		->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
		->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
	//    ->setLabel('Made by store.jui.io')
		->setLabelFontSize(10)
		->setImageType(QrCode::IMAGE_TYPE_PNG);

	ob_start();
	// output image 
	$qrCode->render();
	$content = ob_get_contents();
	ob_end_clean();
	file_put_contents($file, $content);
}

readfile($file);

