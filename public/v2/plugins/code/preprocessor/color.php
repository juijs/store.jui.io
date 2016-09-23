<?php

header('Content-Type: text/html');

if (!hasCache($file)) {
	$arr = file($file);

	ob_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Colors</title>
	<style type="text/css">
		.color {
			display:inline-block;
			width:100px;
			height:50px;
			box-sizing:border-box;
			padding:15px 5px;
			text-align:center;
		}
	</style>
</head>
<body>

<?php

foreach($arr as $color) {
	if (strpos($color, "//") !== false) continue;
	$arr = array_map('trim', explode(",", $color));

	$color = $arr[0];

	if ($color == "") continue;
?><div class='color' style="background-color:<?php echo $color?>;"><?php echo $color ?></div><?php 
}

?>
</body>
</html>
<?php
	
	$content = ob_get_contents();
	ob_end_clean();

	generateCache($file, $content);
}

outputCache($file);

