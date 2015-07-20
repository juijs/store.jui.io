<?php 

$files = scandir("./sample/ui/implements", 0);

array_shift($files);
array_shift($files);

header('Content-Type: application/json');
echo json_encode($files);
?>