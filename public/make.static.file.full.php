<?php 
// must have $data 

// this file has only result view

ob_start();

$title = $data['title'];
//$id = (string)$data['_id'];
$description = str_replace("\r\n", "\\r\\n", $data['description']);
$username = $data['username'];

if (!$data['type']) $data['type'] = 'component';

include_once "header.static.full.php";


$type = $data['type'];

?>
<div id="content-container">
	<div class='nav-container result-only' >
        <div id='embedResult' class='nav-content active' style="overflow:hidden" >
			<?php
				echo $data['html_code'];
			?>		
		
		</div>
	</div>

</div>

<script type="text/javascript">
<?php echo $data['component_code'] ?>
</script>
<script type="text/javascript">
<?php echo $data['sample_code'] ?>
</script>
</body>
</html>
<?php 


$static = ob_get_contents();
ob_end_clean();

// generate static file 
return $static;
?>
