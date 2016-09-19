<?php 
// must have $data 
$path = REPOSITORY.'/'.$id.'/';

$default_view_list = array(
	'index.html',
	'index.htm',
	'index.jade',
	'index.md',
	'README.md'
);



foreach($default_view_list as $file_path) {
	if (file_exists($path.$file_path)) {
		$url = "/v2/code/{$id}/".$file_path;
		//var_dump($url);
		header("Location: ".$url);	
		exit;
	}
}

// empty file 

echo "Empty Code";
