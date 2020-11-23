<?php
	include('header.php');
	include('dbconn2.php');
	session_start();

	if(!isset($_SESSION['username'])) {
		header('location: adminLogin.php');
	}
?>

<!DOCTYPE html>
<html>  
    <head>
		<title>Results - BVS</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="images/logo.png">
		<link rel="stylesheet" href="css/bootstrap.min.css" />
  		<script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
		<style type="text/css">
			body {
				font-family: 'Ubuntu', sans-serif;
			}
		</style>
    </head>  
    <body>  
        <div class="container" style="margin-top: 50px">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="card">
						<div class="card-header bg-warning text-white font-weight-bold" style="text-align: center; font-size: 20px">Poll Results</div>
						<div class="card-body bg-light">
							<div class="form-group">
								<label style="font-weight: bold">Voter Area</label>
								<select type="text" name="voterarea" id="voterarea" class="form-control"></select>
								<span id="error_voterarea" class="text-danger"></span>
							</div>
							<div class="text-center" style="margin-top: 20px; margin-bottom: 0px">
								<button style="font-weight: bold; font-size: 15px" class="btn btn-sm btn-warning" onClick="showFilteredResults()">Show Results</button>
							</div>
							<div style="margin-top: 50px" id="poll_result"></div>
						</div>
					</div>
				</div>
				<div class="col-md-2"></div>
			</div>
		</div>
    </body>  
</html>  
<script>  
	$(document).ready(function() {
		loadVoterArea();
	});

	function loadVoterArea() {
		var loadvoterarea = "loadvoterarea";

		$.ajax({
			url: "results_backend.php",
			type: "POST",
			data: {loadvoterarea: loadvoterarea},

			success: function(data) {
				$('#voterarea').html(data);
			}
		});
	}

	function showFilteredResults() {
		var voterarea = $('#voterarea').val();

		$.ajax({
			url: "results_backend.php",
			method: "POST",
			data: {voterarea: voterarea},

			success: function(data) {
				$('#poll_result').html(data);
			}
		});
	}
</script>