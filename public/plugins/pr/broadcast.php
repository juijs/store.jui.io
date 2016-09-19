<?php 

if ($isMy) {
	include_once PLUGIN."/pr/broadcast/server.php";
} else {
	include_once PLUGIN."/pr/broadcast/client.php";
}

?>