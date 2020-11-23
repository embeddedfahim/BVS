<?php
	$devid = $_GET['devid'];
	file_put_contents('devid.txt', $devid);
?>