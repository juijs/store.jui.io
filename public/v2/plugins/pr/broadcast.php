<?php 

if ($isMy) {
	include_once V2_PLUGIN."/pr/broadcast/server.php";
} else {
	include_once V2_PLUGIN."/pr/broadcast/client.php";
}

?>