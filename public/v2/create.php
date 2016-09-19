<?php $page_id = 'create';

include_once '../../bootstrap.php';
include_once 'common.php';
// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$data = $components->findOne(array('_id' => new MongoId($_GET['id'])));

$isMy = true;
if ($_GET['id'] && ($data['login_type'] != $_SESSION['login_type'] || $data['userid'] != $_SESSION['userid']) ) {
    $isMy = false;
}

$type = $_GET['type']; // plugin name

if ($data['type']) { 
	$type = $data['type'];
}

if (!$type) $type = 'page'; 

include_once V2_PLUGIN."/$type/meta.php";
$meta = implode(PHP_EOL, $metaList);

$type_name = $type_list[$type]['name'];
$type_image = get_svg_image($type_list[$type]['img']);


?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>JENNIFER UI: Store</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<link href="css/flat.css" rel="stylesheet" />
<link href="css/edit.css" rel="stylesheet" />
<?php echo $meta ?>
<style type="text/css">
.CodeMirror {
	height: 100%;
}
.lint-error {font-family: arial; font-size: 70%; background: #ffa; color: #a00; padding: 2px 5px 3px; }
.lint-error-icon {color: white; background-color: red; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 7px;}
body { overflow: hidden; }
</style>

</head>
<body class="jui flat">

<?php include_once "nav-editor.php" ?>

<div class="content-container">
	<?php include_once V2_PLUGIN."/$type/create.php"; ?>
</div>


<?php include_once "footer.php" ?>
<?php include_once "modals.php" ?>
</body>
</html>
