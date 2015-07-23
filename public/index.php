<?php include_once "header.php" ?>

		<div>
			<span class="content-btn"><a href="?sort=update_time" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'good' ? 'on' : 'off' ?>">최신순</a><a href="?sort=good" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'good' ? 'on' : 'off' ?>">좋아요순</a></span>
		</div>
<?php 


// connect
$m = new MongoClient();

// select a database
$db = $m->store;

$components = $db->components;

$sort_type = $_GET['sort'] ? $_GET['sort'] : 'update_time';

$sort = array();
$sort[$sort_type] = -1; 
$sort['update_time'] = -1;

$rows = $components->find(array(
    'access' => 'public'            
))->sort($sort)->limit(10);

?>

<div style="margin-top:28px"></div>
<div id="content-container">


	<?php 
	$lastId = "";
	foreach ($rows as $data) { 
		include "box.php"; 

		$lastId = $data['id'];
	} 
	?>
</div>

<script type="text/javascript">
$(function() {
	var lastId = '<?php echo (string)$lastData['id'] ?>';

	var $container = $('#content-container');
	
	$container.masonry({
	  // options
	  itemSelector: '.summary-box',
	  isFitWidth: true
	});

	  $(window).scroll(function(e) {
		console.log(e);
	  });
	
});

</script>
<?php include_once "footer.php" ?>
