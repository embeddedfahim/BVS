<?php
	include('dbconn2.php');
	session_start();

	if(!isset($_SESSION['username'])) {
		header('location: login.php');
	}
?>

<!DOCTYPE html>
<html>  
    <head>
		<title>Poll - BVS</title>
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
    	<div class="jumbotron-small text-center" style="margin-top: 20px; margin-bottom: 20px">
			<h2 class="text-warning">United Voting System (UVS)</h2>
			<h5 class="text-dark">Developed in Bangladesh</h5>
		</div>
		<nav class="navbar navbar-expand-sm bg-light navbar-light">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="collapsibleNavbar">
				<ul class="navbar-nav mx-auto">
					<li class="nav-item">
						<div style="margin-top: 5px; margin-bottom: -5px" id="user_info"></div>
					</li>
				</ul>
			</div>
		</nav>
        <div class="container" style="margin-top: 50px">
        	<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<button type="button" class="btn btn-warning float-right btn-sm" style="margin-top: 10px; margin-bottom: 50px; font-weight: bold" onClick="window.location.href='logout.php'">Log Out</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="card">
						<div class="card-header bg-warning text-white font-weight-bold"style="text-align: center; font-size: 20px">Select your desired candidate and press 'Submit'..</div>
						<div class="card-body bg-light">
							<form method="POST" id="poll_form">
								<div class="form-group col text-center font-weight-bold" id="candidate_list"></div>
								<div class="form-group col text-center" style="margin-top: 20px; margin-bottom: 0px">
									<input style="font-weight: bold; font-size: 15px" type="submit" name="poll_button" id="poll_button" class="btn btn-sm btn-warning" />
								</div>
							</form>
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
		loadUserInfo();
		loadCandidates();

		function loadCandidates() {
			var loadcandidates = "loadcandidates";

			$.ajax({
				url: "poll_backend.php",
				type: "POST",
				data: {loadcandidates: loadcandidates},
			
				success: function(data, status) {
					$('#candidate_list').html(data);
				}
			});
		}

		function loadUserInfo() {
			var loaduserinfo = "loaduserinfo";

			$.ajax({
				url: "poll_backend.php",
				type: "POST",
				data: {loaduserinfo: loaduserinfo},
			
				success: function(data) {
					$('#user_info').html(data);
				}
			});
		}
	
		$('#poll_form').on('submit', function(event) {
			var poll_option = '';
			var votername = <?php echo json_encode($_SESSION['username']); ?>;

			event.preventDefault();

			$('.poll_option').each(function() {
				if($(this).prop("checked")) {
					poll_option = $(this).val();
				}
			});

			if(poll_option != '') {
				$('#poll_button').attr('disabled', 'disabled');
				
				$.ajax({
					url: "add_vote.php",
					method: "POST",
					data: {poll_option: poll_option, votername: votername},
					
					success: function(data) {
						$('#poll_form')[0].reset();
						$('#poll_button').attr('disabled', false);
						alert(data);
						location.href = "logout.php";
					}
				});
			}
			else {
				alert("Please select a candidate!!");
			}
		});
	});
</script>