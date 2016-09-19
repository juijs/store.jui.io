<?php
/**
 *  Bar Code Generator 
 *
 */ 


header('Content-Type: image/png');

$obj = file_get_contents($file);
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
echo $generator->getBarcode($obj, $generator::TYPE_CODE_128_A);