<?php
	include('dbconn2.php');
	session_start();
	$username = '';
	$password = '';
	$error_username = '';
	$error_password = '';
	$error = 0;

	if(empty($_POST["username"])) {
		$error_username = 'Username is required!';
		$error++;
	}
	else {
		$username = $_POST["username"];
	}
	if(empty($_POST["password"])) {
		$error_password = 'Password is required!';
		$error++;
	}
	else {
		$password = $_POST["password"];
	}
	if($error == 0) {
		$query = "SELECT * FROM admins WHERE username = '$username'";
		$result = mysqli_query($conn, $query);
		
		if(mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				if($row['password'] == $password) {
					$_SESSION['username'] = $row['username'];
				}
				else {
					$error_password = "Wrong Password!!";
					$error++;
				}
			}
		}
		else {
			$error_username = "Wrong Username!!";
			$error++;
		}
	}
	if($error > 0) {
		$output = array(
			'error'				=>	true,
			'error_username'	=>	$error_username,
			'error_password'	=>	$error_password
		);
	}
	else {
		$output = array(
			'success'			=>	true
		);	
	}

	echo json_encode($output);
?>