<?php include_once '../../bootstrap.php' ?>
<?php include_once 'common.php'; 

$page_id = 'index';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>JENNIFER UI: Store</title>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>

	<link href="//store.jui.io/jui-all/jui/dist/ui.min.css" rel="stylesheet" />
	<link href="//store.jui.io/jui-all/jui/dist/ui-jennifer.min.css" rel="stylesheet" />

	<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
	<script type="text/javascript" src="/v2/js/main.js"></script>
	
	
	<link href="/v2/css/<?php echo $theme ?>.css" rel="stylesheet" />
	<link href="/v2/css/<?php echo $theme ?>-responsive.css" rel="stylesheet" />

</head>
<body class="jui flat">

	<div class="t">

		<?php include_once "nav.php" ?>
	<?php 


	// connect
	$m = new MongoClient();

	// select a database
	$db = $m->store;

	$components = $db->components;

	$sort_type = $_GET['sort'] ? $_GET['sort'] : 'update_time';

	$sort = array();
	$sort[$sort_type] = -1; 

	$rows = $components->find(array(
		'access' => 'public',
		//'type' => array ('$ne' => 'code')
	));

	$total = $rows->count();


	$page = $_GET['page'];

	if (!$page) $page = 1;

	$page = intval($page);

	$nextPage = $page+1;
	$prevPage = $page-1;

	$limit = 9;
	$skip = ($page - 1) * $limit;

	if ($page * $limit > $total) {
		$nextPage = -1; 
	}

	$rows = $rows->sort($sort)->skip($skip)->limit($limit);

	$clipPath = array();
	?>

	<div class="content-container list">
		<?php include_once "main_button.php" ?>
		<?php include_once "tool_button.php" ?>
		<div class="box-list">
	<?php  

	$boxCount = 0; 
	$temp_box_array = array();
	foreach ($rows as $data) {  
		$temp_box_array[] = $data; 
	} 
	
	$chunk = array_chunk($temp_box_array, 3);
	foreach($chunk as $key => $boxes)  {
		echo "<div class='box-row box-row-{$key}'>";
		foreach ($boxes as $data) {
			include "box.php";
		}
		echo "</div>";
	}
	?>
		</div>
	</div>

	<?php include_once "include/store.list.php" ?>
	<?php include_once "footer.php" ?>


</div>
<?php include_once "modals.php" ?>
</body>
</html>
