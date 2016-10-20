<?php

header('Content-Type: application/pdf');
//header("Content-Type: text/plain");


if (!hasCache($file)) {
	$cache_file = getCache($file);
    $output_dir = dirname($cache_file);

    $pdfname = explode('.', basename($file));
    array_pop($pdfname);

    $pdfname = implode('.', $pdfname).".pdf";
    $logname = implode('.', $pdfname).".log";

    var_dump($pdfname, $output_dir, $cache_file, $logname);

    // pdftex 를 사용해서 지정된 디렉토리에 파일을 출력하고 
	echo system("pdftex -output-directory=$output_dir $file");

    var_dump(file_get_contents("$output_dir/$logname"));
    
    // 실제 캐쉬파일로 변경한다. 
    system("mv $output_dir/$pdfname $cache_file");
	touchCache($file);
}

outputCache($file);
