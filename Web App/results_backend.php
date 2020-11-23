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

	if(isset($_POST['voterarea'])) {
		$voterarea = $_POST['voterarea'];
		$query = "SELECT * FROM poll_records WHERE voter_area = '$voterarea'";
		$result = mysqli_query($conn, $query);
		$total_poll_row = mysqli_num_rows($result);
		$query2 = "SELECT DISTINCT candidate_name FROM poll_records WHERE voter_area = '$voterarea'";
		$result2 = mysqli_query($conn, $query2);
		$output = '';

		if($total_poll_row > 0) {
			foreach($result2 as $row) {
				$query3 = "SELECT * FROM poll_records WHERE voter_area = '$voterarea' AND candidate_name = '".$row['candidate_name']."'";
				$result3 = mysqli_query($conn, $query3);
				$total_row = mysqli_num_rows($result3);
				$percentage_vote = round(($total_row / $total_poll_row) * 100);
				$progress_bar_class = '';
			
				if($percentage_vote >= 40) {
					$progress_bar_class = 'bg-success';
				}
				else if($percentage_vote >= 25 && $percentage_vote < 40) {
					$progress_bar_class = 'bg-info';
				}
				else if($percentage_vote >= 10 && $percentage_vote < 25) {
					$progress_bar_class = 'bg-warning';
				}
				else {
					$progress_bar_class = 'bg-danger';
				}

				$output .= '
							<div class="row">
								<div class="col-md-12" align="center">
									<label>'.$row['candidate_name'].': '.$total_row.' vote(s)</label>
								</div>
								<div class="col-md-12">
									<div class="progress">
										<div class="progress-bar '.$progress_bar_class.'" role="progressbar" aria-valuenow="'.$percentage_vote.'"aria-valuemin="0" aria-valuemax="100" style="width: '.$percentage_vote.'%">'.$percentage_vote.'%
										</div>
									</div>
								</div>
							</div>
				';
			}
		}

		echo $output;
	}
?>