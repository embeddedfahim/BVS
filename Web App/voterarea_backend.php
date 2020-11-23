<?php
	include('dbconn2.php');
	
	
	if(isset($_POST['loadvoterarea'])) {
		$data = '<table class="table table-bordered table-striped text-center">
					<tr class="bg-warning text-white">
						<th>Sl.</th>
						<th>Voter Area Name</th>
						<th>Operation</th>
					</tr>';
		$query = "SELECT * FROM `voter_area_list`"; 
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0) {
			$serial = 1;
			
			while($row = mysqli_fetch_array($result)) {
				$data .= '<tr class="bg-light text-dark">
							<td>'.$serial.'</td>
							<td>'.$row['voter_area_name'].'</td>
							<td>
								<button style="font-weight: bold" onclick="getVoterAreaDetails('.$row['id'].')" class="btn btn-warning btn-sm">Rename</button>
								<button style="font-weight: bold" onclick="deleteVoterArea('.$row['id'].')" class="btn btn-danger btn-sm">Delete</button>
							</td>
    					  </tr>';
    			$serial++;
			}
		}

		$data .= '</table>';
    	echo $data;
	}

	if(isset($_POST['voterareaname'])) {
		$query = "INSERT INTO `voter_area_list` (voter_area_name) VALUES ('$voterareaname')";
		mysqli_query($conn, $query);
	}

	if(isset($_POST['deleteid'])) {
		$voterareaid = $_POST['deleteid'];
		$query = "DELETE FROM voter_area_list WHERE id = '$voterareaid'";
		mysqli_query($conn, $query);
	}

	if(isset($_POST['id']) && isset($_POST['id']) != "") {
    	$voterarea_id = $_POST['id'];
    	$query = "SELECT * FROM voter_area_list WHERE id = '$voterarea_id'";
    	
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

	if(isset($_POST['hidden_voterarea_id'])) {
		$hidden_voterarea_id = $_POST['hidden_voterarea_id'];
		$newvoterareaname = $_POST['newvoterareaname'];
    	$query = "UPDATE voter_area_list SET voter_area_name = '$newvoterareaname' WHERE id = '$hidden_voterarea_id'";

    	if(!$result = mysqli_query($conn, $query)) {
    		exit(mysqli_error());
    	}
    }
?>