<?
$arr = explode("/", $_SERVER['PATH_INFO']);
array_shift($arr);

$name = implode("/", $arr);
$ext = array_pop(explode(".", $name));

include_once '../bootstrap.php';

$extType = array(
	'component' => 'js',
	'theme' => 'js',
	'style' => 'less',
	'css' => 'css',
	'data' => '',
	'map' => 'svg'
);

// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$document = array( 'name' => $name );

$component = $components->findOne($document);

if ($ext == 'css' && $component['type'] == 'style') {
	$id = uniqid("temp".rand(0, 100).rand(0,100));
	$code = $component['component_code'];
	$filename = "jui/less/theme/".$id.".less";

	file_put_contents(__DIR__."/".$filename, $code);

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

	$component['type'] = 'css';
	$component['component_code'] = $result;

} else {
	$filename .= ".".$ext[$component['type']];
}

header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Transfer-Encoding: binary");

header("Content-Length: ".strlen($component['component_code']));
echo $component['component_code'];

?>
