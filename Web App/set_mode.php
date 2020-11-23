<?php
	if(isset($_POST['set_mode'])){
		$mode = $_POST['set_mode'];
		file_put_contents('mode.txt', $mode);
	}
?>