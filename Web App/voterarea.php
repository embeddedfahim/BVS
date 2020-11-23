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
		<title>Voter Areas - BVS</title>
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
		<div class="container">
			<button type="button" class="btn btn-warning float-right btn-sm" style="margin-top: 10px; margin-bottom: 10px; font-weight: bold" data-toggle="modal" data-target="#addModal">Add Voter Area</button>
			<div id="voterarea_list"></div>
			<div class="modal" id="addModal">
				<div class="modal-dialog">
    				<div class="modal-content">
    					<div class="modal-header">
        					<h4 class="modal-title">Add Voter Area</h4>
        					<button type="button" class="close" data-dismiss="modal">&times;</button>
      					</div>
      					<div class="modal-body">
        					<div class="form-group">
        						<label>Voter Area Name:</label>
        						<input type="text" name="voterareaname" id="voterareaname" class="form-control" placeholder="Enter Voter Area Name..">
                    			<span id="error_voterareaname" class="text-danger"></span>
        					</div>
      					</div>
      					<div class="modal-footer">
      						<button type="button" style="font-weight: bold" class="btn btn-success btn-sm" data-dismiss="modal" onclick="addVoterArea()">Add</button>
        					<button type="button" style="font-weight: bold" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      					</div>
    				</div>
  				</div>
			</div>
      		<div class="modal" id="updateModal">
        		<div class="modal-dialog">
            		<div class="modal-content">
              			<div class="modal-header">
                  			<h4 class="modal-title">Rename Voter Area</h4>
                  			<button type="button" class="close" data-dismiss="modal">&times;</button>
                		</div>
                <div class="modal-body">
                  	<div class="form-group">
                    	<label>Voter Area Name:</label>
                    	<input type="text" name="newvoterareaname" id="newvoterareaname" class="form-control" placeholder="Enter Voter Area Name..">
                  	</div>
                </div>
                <div class="modal-footer">
                  	<button type="button" style="font-weight: bold" class="btn btn-warning btn-sm" data-dismiss="modal" onclick="updateVoterAreaDetails()">Update</button>
                  	<button type="button" style="font-weight: bold" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                  	<input type="hidden" id="hidden_voterarea_id">
                </div>
            </div>
        </div>

  		<script type="text/javascript">
        	$(document).ready(function() {
            	loadVoterArea();
        	});

        	function loadVoterArea() {
            	var loadvoterarea = "loadvoterarea";

            	$.ajax({
            		url: "voterarea_backend.php",
              		type: "POST",
              		data: {loadvoterarea: loadvoterarea},

              		success: function(data) {
                		$('#voterarea_list').html(data);
              		}
            	});
          	}

  			function addVoterArea() {
  				if($('#voterareaname').val() != '') {
          			var voterareaname = $('#voterareaname').val();
        		}
        		else {
          			$('#error_voterareaname').val('Voter area name is required!!');
        		}

  				$.ajax({
  					url: "voterarea_backend.php",
  					type: 'post',
  					data: {voterareaname: voterareaname},

  			 		success: function(data) {
						loadVoterArea();
						$('#voterareaname').val('');
  			 		}
  				});
  			}

        	function getVoterAreaDetails(id) {
				$("#hidden_voterarea_id").val(id);
	  			
				$.post("voterarea_backend.php", {
						id: id
					},
        		
					function(data) {
						var user = JSON.parse(data);
						$("#newvoterareaname").val(user.voter_area_name);
					}
				);
    			
    			$("#updateModal").modal("show");
    		}

			function updateVoterAreaDetails() {
    			var newvoterareaname = $("#newvoterareaname").val();
    			var hidden_voterarea_id = $("#hidden_voterarea_id").val();
    			
				$.post("voterarea_backend.php", {
						hidden_voterarea_id: hidden_voterarea_id,
						newvoterareaname: newvoterareaname
					},

					function(data) {
						$("#updateModal").modal("hide");
						loadVoterArea();
					}
				);
			}

      		function deleteVoterArea(deleteid) {
    			var conf = confirm("Are you sure you want to delete this voter area?");

        		if(conf == true) {
          			$.ajax({
          				url: "voterarea_backend.php",
          				type: 'POST',
            			data: {deleteid: deleteid},

            			success: function(data) {
          					loadVoterArea();
        				}
      				});
        		}
      		}
  		</script>
	</body>
</html>