<?php
	include('dbconn2.php');
	session_start();
	$name = $_SESSION['username'];

	if(isset($_POST['loadcandidates'])) {
		$query = "SELECT voter_area FROM `voters` WHERE name = '$name'";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
		}
		
		$voter_area = $row['voter_area'];
		$query = "SELECT * FROM `candidates` WHERE voter_area = '$voter_area'"; 
		$result = mysqli_query($conn, $query);
		$data = '';

		if(mysqli_num_rows($result) > 0) {
			$serial = 1;
			
			while($row = mysqli_fetch_array($result)) {
				$data .= '<div class="radio">
							<p><input type="radio" name="poll_option" class="poll_option" value="'.$row['candidatename'].'" /> '.$row['candidatename'].'</p>
						  </div>';
    			$serial++;
			}
		}

    	echo $data;
	}

	if(isset($_POST['loaduserinfo'])) {
		$query = "SELECT * FROM `voters` WHERE name = '$name'";
		$result = mysqli_query($conn, $query);
		$response = '';

		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
		}

		$response .= '<p style="text-align: center;  font-weight: bold">Welcome, '.$row['name'].'. Your voter area is '.$row['voter_area'].'.</p>';

    	echo $response;
	}

	if(isset($_POST['loadvotername'])) {
    	echo $name;
	}
?>