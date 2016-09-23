<?php
/**
 *  Bar Code Generator 
 *
 */ 


header('Content-Type: image/png');
if (!hasCache($file)) {
	$obj = file_get_contents($file);
	$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
	$content = $generator->getBarcode($obj, $generator::TYPE_CODE_128_A);

	generateCache($file, $content);
}

outputCache($file);

