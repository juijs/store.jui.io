<?php 
include_once '../bootstrap.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header('Content-Type: text/css');


// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$document = array( '_id' => new MongoId($_GET['id']) );

$component = $components->findOne($document);


$sample_type = $component['sample_type'];

if (!$sample_type) {
	$sample_type = "buttons";
}

$id = uniqid("temp".rand(0, 100));
$code = $component['component_code'];

$defaultTheme = "jui/less/theme/jennifer.less";
$filename = "jui/less/theme/".$id.".less";

$realCode = str_replace('@import "../theme.less";
', '', file_get_contents($defaultTheme)).PHP_EOL.$code;

file_put_contents(__DIR__."/".$filename, $realCode);

try {
	$parser = new Less_Parser();
	$parser->parseFile( __DIR__."/".$filename );
	$result = $parser->getCss();

    $result = str_replace("../img", "img", $result);
    $result = str_replace("../widget/img", "img", $result);

} catch (Exception $ex) {

	echo $ex->getMessage();
}

unlink($filename);

echo $result; 

?>
