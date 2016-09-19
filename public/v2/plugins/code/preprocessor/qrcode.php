<?php

/**
 *  Implement QRCode generator 
 * 
 * 
 */ 
use Endroid\QrCode\QrCode;

$obj = file_get_contents($file);

$qrCode = new QrCode();
$qrCode
    ->setText($obj)
    ->setSize(300)
    ->setPadding(10)
    ->setErrorCorrection('high')
    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
//    ->setLabel('Made by store.jui.io')
    ->setLabelFontSize(10)
    ->setImageType(QrCode::IMAGE_TYPE_PNG)
;

// now we can directly output the qrcode
header('Content-Type: '.$qrCode->getContentType());
$qrCode->render();