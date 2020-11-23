<?php
	include('dbconn2.php');
	session_start();

	if(isset($_POST['verify_fingerprint'])) {
		file_put_contents('mode.txt', 2);
	}

	sleep(10);
	$devid = file_get_contents('devid.txt');

	if($devid == 0) {
		$response = "Not Recognized!!";
	}
	else {
		$response = "Recognized..";
		$query = "SELECT name FROM voters WHERE devid = '$devid'";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
		}

		$_SESSION['username'] = $row['name'];
	}

	echo $response;
	sleep(5);
	file_put_contents('devid.txt', 0);
	file_put_contents('mode.txt', 0);
?>