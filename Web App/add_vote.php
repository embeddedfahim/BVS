<?php
	include('dbconn2.php');
	date_default_timezone_set('Asia/Dhaka');
	$date = date('Y-m-d');
	$votername = $_POST["votername"];
	$query = "SELECT voter_area FROM voters WHERE name = '$votername'";
	$result = mysqli_query($conn, $query);

	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
	}

	$voterarea = $row['voter_area'];

	if(isset($_POST["poll_option"])) {
		$candidate_name = $_POST["poll_option"];
		$query = "SELECT * FROM vote_timestamps WHERE voter_name = '$votername' AND `date` = '$date'";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0) {
			echo "You have already voted!!";
		}
		else {
			$query = "INSERT INTO poll_records (candidate_name, voter_area) VALUES ('$candidate_name', '$voterarea')";
			mysqli_query($conn, $query);
			$query = "INSERT INTO vote_timestamps (voter_name, `date`) VALUES ('$votername', '$date')";
			mysqli_query($conn, $query);

			echo "Poll submitted successfully..";
		}
	}
?>