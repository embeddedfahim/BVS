<?php
	include('dbconn2.php');
	$devid = $_POST["devid"];
	$nid = '';
	$fullname = '';
	$age = '';
	$address = '';
	$mobile = '';
	$voterarea = '';
	$error_nid = '';
	$error_fullname = '';
	$error_age = '';
	$error_address = '';
	$error_mobile = '';
	$error_voterarea = '';
	$error = 0;

	if(isset($_POST['loadvoterarea'])) {
		$data = '<option value="" selected="selected">Select Voter Area..</option>';
		$query = "SELECT voter_area_name FROM voter_area_list";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$data .= '<option value="'.$row['voter_area_name'].'">'.$row['voter_area_name'].'</option>';
			}
		}

		echo $data;
	}

	if(empty($_POST["fullname"])) {
		$error_fullname = 'Full name is required!';
		$error++;
	}
	else {
		$fullname = $_POST["fullname"];
	}
	if(empty($_POST["age"])) {
		$error_age = 'Age is required!';
		$error++;
	}
	else {
		if($_POST["age"] < 18) {
			$error_age = 'Age must be greater than or equal to 18!';
			$error++;
		}
		else {
			$age = $_POST["age"];
		}
	}
	if(empty($_POST["address"])) {
		$error_address = 'Address is required!';
		$error++;
	}
	else {
		$address = $_POST["address"];
	}
	if(empty($_POST["voterarea"])) {
		$error_voterarea = 'Voter area is required!';
		$error++;
	}
	else {
		$voterarea = $_POST["voterarea"];
	}
	if(empty($_POST["nid"])) {
		$error_nid = 'NID no. is required!';
		$error++;
	}
	else {
		$nid = $_POST["nid"];
		$query = "SELECT * FROM voters WHERE nid = '$nid'";
		$result = mysqli_query($conn, $query);
		
		if(mysqli_num_rows($result) > 0) {
			$error_nid = "NID no. already exists!!";
			$error++;
		}
	}
	if(empty($_POST["mobile"])) {
		$error_mobile = 'Mobile no. is required!';
		$error++;
	}
	else {
		$mobile = $_POST["mobile"];
		$query = "SELECT * FROM voters WHERE mobile = '$mobile'";
		$result = mysqli_query($conn, $query);
		
		if(mysqli_num_rows($result) > 0) {
			$error_mobile = "Mobile no. already exists!!";
			$error++;
		}
	}
	
	if($error == 0) {
		$query = "INSERT INTO voters (devid, nid, name, age, address, mobile, voter_area) VALUES ('$devid', '$nid', '$fullname', '$age', '$address', '$mobile', '$voterarea')";
		mysqli_query($conn, $query);
	}
	if($error > 0) {
		$output = array(
			'error'				=>	true,
			'error_nid'			=>	$error_nid,
			'error_fullname'	=>	$error_fullname,
			'error_age'			=>	$error_age,
			'error_address'		=>	$error_address,
			'error_mobile'		=>	$error_mobile,
			'error_voterarea'	=>	$error_voterarea
		);
	}
	else {
		$output = array(
			'success'			=>	true
		);	
	}

	echo json_encode($output);
?>