<?php 

$files = scandir("./sample", 0);

array_shift($files);
array_shift($files);

header('Content-Type: application/json');
echo json_encode($files);
?>