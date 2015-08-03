<?php
$root = getcwd();
var_dump(getcwd());
var_dump($_SERVER);
echo shell_exec("webshot http://store.jui.io/embed.php\?id=55b9b33894976c6d013f5967\&only=true {$root}/thumbnail/sample.image.png");
echo "webshot http://store.jui.io/embed.php\?id=55b9b33894976c6d013f5967\\\&only=true {$root}/sample.image.png";
//phpinfo();
?>
