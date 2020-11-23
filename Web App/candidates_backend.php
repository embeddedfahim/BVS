<?php
	include('dbconn2.php');
	
	
	if(isset($_POST['loadvoterarea'])) {
		$data = '<option value="" selected="selected">Select Voter Area..</option>';
		$query = "SELECT voter_area_name FROM voter_area_list";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)) {
				$data .= '<option value="'.$row['voter_area_name'].'">'.$row['voter_area_name'].'</option>';
			}
		}

		echo $data;
	}
	
	if(isset($_POST['loadcandidates'])) {
		$data = '<table class="table table-bordered table-striped text-center">
					<tr class="bg-warning text-white">
						<th>Sl.</th>
						<th>Candidate Name</th>
						<th>Voter Area</th>
						<th>Operation</th>
					</tr>';
		$displayquery = "SELECT * FROM `candidates`"; 
		$result = mysqli_query($conn, $displayquery);

		if(mysqli_num_rows($result) > 0) {
			$serial = 1;
			
			while($row = mysqli_fetch_array($result)) {
				$data .= '<tr class="bg-light text-dark">
							<td>'.$serial.'</td>
							<td>'.$row['candidatename'].'</td>
							<td>'.$row['voter_area'].'</td>
							<td>
								<button style="font-weight: bold" onclick="getCandidateDetails('.$row['candidateid'].')" class="btn btn-warning btn-sm">Edit</button>
								<button style="font-weight: bold" onclick="deleteCandidate('.$row['candidateid'].')" class="btn btn-danger btn-sm">Delete</button>
							</td>
    					  </tr>';
    			$serial++;
			}
		}

		$data .= '</table>';
    	echo $data;
	}

	if(isset($_POST['candidatename']) && isset($_POST['voterarea'])) {
		$query = "INSERT INTO `candidates` (candidatename, voter_area) VALUES ('$candidatename', '$voterarea')";
		mysqli_query($conn, $query);
	}

	if(isset($_POST['deleteid'])) {
		$candidateid = $_POST['deleteid'];
		$query1 = "SELECT candidatename FROM candidates WHERE candidateid = '$candidateid'";
		$result = mysqli_query($conn, $query1);
		$candidatename = implode(mysqli_fetch_assoc($result));
		$query2 = "DELETE FROM poll_records WHERE candidate_name = '$candidatename'";
		mysqli_query($conn, $query2);
		$query3 = "DELETE FROM candidates WHERE candidateid = '$candidateid'";
		mysqli_query($conn, $query3);
	}

	if(isset($_POST['id']) && isset($_POST['id']) != "") {
    	$candidate_id = $_POST['id'];
    	$query = "SELECT * FROM candidates WHERE candidateid = '$candidate_id'";
    	
    	if(!$result = mysqli_query($conn, $query)) {
        	exit(mysqli_error());
    	}
    	
    	$response = array();
    	
    	if(mysqli_num_rows($result) > 0) {
    		while($row = mysqli_fetch_array($result)) {
            	$response = $row;
        	}
    	}
    	else {
        	$response['status'] = 200;
        	$response['message'] = "Data not found!";
    	}

    	echo json_encode($response);
	}
	else {
    	$response['status'] = 200;
    	$response['message'] = "Invalid request!";
	}

	if(isset($_POST['hidden_candidate_id'])) {
		$hidden_candidate_id = $_POST['hidden_candidate_id'];
		$newcandidatename = $_POST['newcandidatename'];
		$newvoterarea = $_POST['newvoterarea'];
    	$query = "UPDATE candidates SET candidatename = '$newcandidatename', voter_area = '$newvoterarea' WHERE candidateid = '$hidden_candidate_id'";

    	if(!$result = mysqli_query($conn, $query)) {
    		exit(mysqli_error());
    	}
    }
?>