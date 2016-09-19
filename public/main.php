<?php include_once '../bootstrap.php' ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>JENNIFER UI: Store</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>

	<link href="//store.jui.io/jui-all/jui/dist/ui.min.css" rel="stylesheet" />
	<link href="//store.jui.io/jui-all/jui-grid/dist/grid.min.css" rel="stylesheet" />
	<link href="//store.jui.io/jui-all/jui/dist/ui-jennifer.min.css" rel="stylesheet" />
	<link href="//store.jui.io/jui-all/jui-grid/dist/grid-jennifer.min.css" rel="stylesheet" />

	<script type="text/javascript" src="//store.jui.io/jui-all/jui-core/dist/core.min.js"></script>
	<script type="text/javascript" src="//store.jui.io/jui-all/jui/dist/ui.min.js"></script>

	<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
	<script type="text/javascript" src="/js/main.js"></script>
	
	
	<link href="/css/flat.css" rel="stylesheet" />

</head>
<body class="jui flat">
		<?php include_once "v2/nav.php" ?>
		<?php include_once "v2/main_button.php" ?>

		<div class="tool_buttons" style="text-align:right">
			<span class="content-btn">
				<a href="?sort=update_time" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'update_time' ? 'on' : 'off' ?>">DATE</a><a href="?sort=good" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'update_time' ? 'on' : 'off' ?>">SCORE</a>
			</span>
		</div>
<?php 


// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$sort_type = $_GET['sort'] ? $_GET['sort'] : 'good';

$sort = array();
$sort[$sort_type] = -1; 

$rows = $components->find(array(
    'access' => 'public'            
));

$total = $rows->count();


$page = $_GET['page'];

if (!$page) $page = 1;

$page = intval($page);

$nextPage = $page+1;
$prevPage = $page-1;

$limit = 12;
$skip = ($page - 1) * $limit;

if ($page * $limit > $total) {
	$nextPage = -1; 
}

$rows = $rows->sort($sort)->skip($skip)->limit($limit);

?>

<div class="content-container">
<?php  

$boxCount = 0; 
foreach ($rows as $data) {  
//	if ($boxCount++ > 0) break;

	include "v2/box.php";   
} ?>
</div>

<?php include_once "include/store.list.php" ?>
<?php include_once "footer.php" ?>
