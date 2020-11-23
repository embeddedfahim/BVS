<!DOCTYPE html>
<html>
	<head>
		<title>User Login - BVS</title>
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
		<div class="jumbotron-small text-center" style="margin-top: 20px; margin-bottom: 20px">
			<h2 class="text-warning">Biometric Voting System (BVS)</h2>
			<h5 class="text-dark">Developed in Bangladesh</h5>
		</div>
		<div class="container" style="margin-top: 50px">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<button type="button" class="btn btn-warning float-right btn-sm" style="margin-top: 10px; margin-bottom: 50px; font-weight: bold" onClick="window.location.href='adminLogin.php'">Admin Login</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<div class="card">
						<div class="card-header bg-warning text-white font-weight-bold"style="text-align: center; font-size: 20px">User Login</div>
						<div class="card-body bg-light">
							<form method="POST" id="login_form">
								<div class="form-group col text-center" style="margin-top: 20px; margin-bottom: 0px">
									<input style="font-weight: bold; font-size: 15px" name="verify_fingerprint" id="verify_fingerprint" class="btn btn-sm btn-warning" onClick="verifyFingerprint()" value="Verify Fingerprint" />
								</div>
								<div class="form-group col text-center" style="margin-top: 20px; margin-bottom: 0px">
									<input style="font-weight: bold; font-size: 15px" name="login" id="login" class="btn btn-sm btn-warning" onClick="logIn()" value="Log In" />
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>
	</body>
</html>
<script>
	$(document).ready(function() {
		$('#login').attr('disabled', 'disabled');
	});

	function verifyFingerprint() {
		var verify_fingerprint = "verify_fingerprint";

		$.ajax({
			url: "login_backend.php",
			type: "POST",
			data: {verify_fingerprint: verify_fingerprint},

			beforeSend:function() {
				$('#verify_fingerprint').val('Verifying...');
				$('#verify_fingerprint').attr('disabled', true);
			},
					
			success: function(data) {
				alert(data);

				if(data == "Recognized..") {
					$('#verify_fingerprint').val('Verify Fingerprint');
					$('#login').attr('disabled', false);
				}
				else {
					$('#verify_fingerprint').val('Verify Fingerprint');
					$('#verify_fingerprint').attr('disabled', false);
				}
			}
		});
	}

	function logIn() {
		location.href = "poll.php";
	}
</script>