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
		<title>New Voter Registration - BVS</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="images/logo.png">
		<link rel="stylesheet" href="css/bootstrap.min.css">
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
			<div class="modal" id="addFingerprint">
				<div class="modal-dialog">
    				<div class="modal-content">
    					<div class="modal-header">
        					<h4 class="modal-title">Add Fingerprint</h4>
        					<button type="button" class="close" data-dismiss="modal">&times;</button>
      					</div>
      					<div class="modal-body">
        					<div class="form-group">
        						<label>Status:</label>
								<div id="status_area"></div>
        					</div>
      					</div>
      					<div class="modal-footer">
        					<button type="button" style="font-weight: bold" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      					</div>
    				</div>
  				</div>
			</div>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="card">
						<div class="card-header bg-warning text-white font-weight-bold"style="text-align: center; font-size: 20px">Registration Form</div>
						<div class="card-body bg-light">
							<form method="POST" id="registration_form">
								<div class="form-group">
									<label style="font-weight: bold">NID No.</label>
									<input type="text" name="nid" id="nid" placeholder="Enter NID Number.." class="form-control" />
									<span id="error_nid" class="text-danger"></span>
								</div>
								<div class="form-group">
									<label style="font-weight: bold">Full Name</label>
									<input type="text" name="fullname" id="fullname" placeholder="Enter Full Name.." class="form-control" />
									<span id="error_fullname" class="text-danger"></span>
								</div>
								<div class="form-group">
									<label style="font-weight: bold">Age</label>
									<input type="text" name="age" id="age" placeholder="Enter Age.." class="form-control" />
									<span id="error_age" class="text-danger"></span>
								</div>
								<div class="form-group">
									<label style="font-weight: bold">Address</label>
									<input type="text" name="address" id="address" placeholder="Enter Address.." class="form-control" />
									<span id="error_address" class="text-danger"></span>
								</div>
								<div class="form-group">
									<label style="font-weight: bold">Mobile No.</label>
									<input type="text" name="mobile" id="mobile" placeholder="Enter Mobile Number.." class="form-control" />
									<span id="error_mobile" class="text-danger"></span>
								</div>
								<div class="form-group">
									<label style="font-weight: bold">Voter Area</label>
									<select type="text" name="voterarea" id="voterarea" class="form-control"></select>
									<span id="error_voterarea" class="text-danger"></span>
								</div>
								<div class="form-group col text-center" style="margin-top: 20px; margin-bottom: 0px">
									<button type="button" class="btn btn-sm btn-warning" style="font-weight: bold; font-size: 15px" onClick="addFingerprint()">Add Fingerprint</button>
								</div>
								<div class="form-group col text-center" style="margin-top: 20px; margin-bottom: 0px">
									<input style="font-weight: bold; font-size: 15px" type="submit" name="reg" id="reg" class="btn btn-sm btn-warning" value="Submit" />
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
	var devid = '';
	
	$(document).ready(function() {
		$('#reg').attr('disabled', 'disabled');
		loadVoterArea();
		getDevID();

		$('#registration_form').on('submit', function(event) {
			var nid = $('#nid').val();
			var fullname = $('#fullname').val();
			var age = $('#age').val();
			var address = $('#address').val();
			var mobile = $('#mobile').val();
			var voterarea = $('#voterarea').val();

			event.preventDefault();

			$.ajax({
				url: "registration_backend.php",
				method: "POST",
				data: {devid: devid, nid: nid, fullname: fullname, age: age, address: address, mobile: mobile, voterarea: voterarea},
				dataType: "JSON",

				success: function(data) {
					if(data.success) {
						alert("Registration successful..");
						$('#nid').val('');
						$('#fullname').val('');
						$('#age').val('');
						$('#address').val('');
						$('#mobile').val('');
						$('#voterarea').val('');
					}
					if(data.error) {
						$('#reg').val('Submit');
						$('#reg').attr('disabled', false);

						if(data.error_nid != '') {
							$('#error_nid').text(data.error_nid);
						}
						else {
							$('#error_nid').text('');
						}
						if(data.error_fullname != '') {
							$('#error_fullname').text(data.error_fullname);
						}
						else {
							$('#error_fullname').text('');
						}
						if(data.error_age != '') {
							$('#error_age').text(data.error_age);
						}
						else {
							$('#error_age').text('');
						}
						if(data.error_address != '') {
							$('#error_address').text(data.error_address);
						}
						else {
							$('#error_address').text('');
						}
						if(data.error_mobile != '') {
							$('#error_mobile').text(data.error_mobile);
						}
						else {
							$('#error_mobile').text('');
						}
						if(data.error_voterarea != '') {
							$('#error_voterarea').text(data.error_voterarea);
						}
						else {
							$('#error_voterarea').text('');
						}
					}
				}
			});
		});
	});

	function loadVoterArea() {
		var loadvoterarea = "loadvoterarea";

		$.ajax({
			url: "registration_backend.php",
			type: "POST",
			data: {loadvoterarea: loadvoterarea},

			success: function(data) {
				$('#voterarea').html(data);
			}
		});
	}

	function getDevID() {
		$.ajax({
           	url: 'get_devid.php',
           	type: 'POST',

            success: function(data) {
           		devid = data;
           	}
    	});
	}

	function addFingerprint() {
		var set_mode = '1';
		
		$('#addFingerprint').modal("show");

		$.ajax({
           	url: 'set_mode.php',
            type: 'POST',
            data: {set_mode: set_mode}
        });
		
		setInterval(function() {
			$('#status_area').load('get_msg.php', function(response) {
      			if(response == "Stored..") {
      				alert("Fingerprint added successfully..");
      				$('#addFingerprint').modal("hide");
      				$('#reg').attr('disabled', false);
      			}
    		});
		}, 100);
	}
</script>