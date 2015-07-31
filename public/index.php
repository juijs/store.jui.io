<?php include_once "header.php" ?>

		<div>
			<span class="content-btn">
				<a href="?sort=update_time" class="btn-simple form-btn-<?php echo $_GET['sort'] != 'good' ? 'on' : 'off' ?>">Sort by date</a>
				<a href="?sort=good" class="btn-simple form-btn-<?php echo $_GET['sort'] == 'good' ? 'on' : 'off' ?>">Sort by score</a>
			</span>
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
))->sort($sort)->limit(20);

?>

<div style="margin-top:28px"></div>
<div id="content-container">
<?php  foreach ($rows as $data) {  include "box.php";   } ?>
</div>


<script type="text/javascript">
$(function() {

	var $container = $('#content-container');
	
	$container.masonry({
	  // options
	  itemSelector: '.summary-box',
	  isFitWidth: true
	});

	window.loadLastList = function loadLastList() {
		var lastId = $(".summary-box:last").data('id');
		var sort = '<?php echo $sort_type ?>';

		$.get("/load-box.php", { lastId : lastId, sort : sort }, function(data) {
	
	        var $moreBlocks = jQuery( data );

		    $container.append( $moreBlocks );

	        $container.masonry( 'appended', $moreBlocks );         
		});
	}

    $(window).scroll(function(e) {
 	  var height = $(document.body)[0].scrollHeight - $(document.body)[0].scrollTop - $(window).height();

	  if (height == 0) {
		loadLastList();
	  }
    });
	
});

</script>
<?php include_once "footer.php" ?>
