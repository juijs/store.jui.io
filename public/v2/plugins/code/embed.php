<?php 
// must have $data 
$path = REPOSITORY.'/'.$id.'/';

$start_page = $row['start_page'];

if ($start_page) {
	if (file_exists($path.$start_page)) {
		$url = "/v2/code/{$id}/".$start_page;
		header("Location: ".$url);	
		exit;
	}

}

$default_view_list = array(
	'index.html',
	'index.htm',
	'index.jade',
	'index.color',
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
