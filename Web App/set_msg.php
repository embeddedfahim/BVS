<?php
	$msg = $_GET['msg'];
	file_put_contents('msg.txt', $msg);
?>