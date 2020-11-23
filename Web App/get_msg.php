<?php
	$msg = file_get_contents('msg.txt');
	
	if($msg == "Stored..") {
		echo $msg;
		file_put_contents('mode.txt', 0);
		file_put_contents('msg.txt', '');
	}
	else if($msg == "Deleted from device..") {
		echo $msg;
		file_put_contents('devid.txt', 0);
		file_put_contents('mode.txt', 0);
		sleep(2);
		file_put_contents('msg.txt', '');
	}
	else {
		echo $msg;
	}
?>