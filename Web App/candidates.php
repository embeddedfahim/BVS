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
		<title>Candidates - BVS</title>
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
			<button type="button" class="btn btn-warning float-right btn-sm" style="margin-top: 10px; margin-bottom: 10px; font-weight: bold" data-toggle="modal" data-target="#addModal">Add New Candidate</button>
			<div id="candidate_list"></div>
			<div class="modal" id="addModal">
				<div class="modal-dialog">
    				<div class="modal-content">
    					<div class="modal-header">
        					<h4 class="modal-title">Add New Candidate</h4>
        					<button type="button" class="close" data-dismiss="modal">&times;</button>
      					</div>
      					<div class="modal-body">
        					<div class="form-group">
        						<label>Candidate Name:</label>
        						<input type="text" name="candidatename" id="candidatename" class="form-control" placeholder="Enter Candidate Name..">
                    <span id="error_candidatename" class="text-danger"></span>
        						<label>Voter Area:</label>
                    <select type="text" name="voterarea" id="voterarea" class="form-control"></select>
                    <span id="error_voterarea" class="text-danger"></span>
        					</div>
      					</div>
      					<div class="modal-footer">
      						<button type="button" style="font-weight: bold" class="btn btn-warning btn-sm" data-dismiss="modal" onclick="addCandidate()">Add</button>
        					<button type="button" style="font-weight: bold" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      					</div>
    				</div>
  				</div>
			</div>
      		<div class="modal" id="updateModal">
        		<div class="modal-dialog">
            		<div class="modal-content">
              			<div class="modal-header">
                  			<h4 class="modal-title">Edit Candidate</h4>
                  			<button type="button" class="close" data-dismiss="modal">&times;</button>
                		</div>
                <div class="modal-body">
                  	<div class="form-group">
                    	<label>Candidate Name:</label>
                    	<input type="text" name="newcandidatename" id="newcandidatename" class="form-control" placeholder="Enter Candidate Name..">
                  	</div>
                  	<div class="form-group">
        				<label>Voter Area:</label>
        				<select type="text" name="newvoterarea" id="newvoterarea" class="form-control"></select>
        			</div>
                </div>
                <div class="modal-footer">
                  	<button type="button" style="font-weight: bold" class="btn btn-warning btn-sm" data-dismiss="modal" onclick="updateCandidateDetails()">Update</button>
                  	<button type="button" style="font-weight: bold" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                  	<input type="hidden" id="hidden_candidate_id">
                </div>
            </div>
        </div>

  		<script type="text/javascript">
        	$(document).ready(function() {
        		loadCandidates();
            	loadVoterArea();
        	});

        	function loadCandidates() {
        		var loadcandidates = "loadcandidates";

				    $.ajax({
					   url: "candidates_backend.php",
					   type: "POST",
					   data: {loadcandidates: loadcandidates},

					   success: function(data, status) {
						  $('#candidate_list').html(data);
					   }
				    });
			     }

          function loadVoterArea() {
            var loadvoterarea = "loadvoterarea";

            $.ajax({
              url: "candidates_backend.php",
              type: "POST",
              data: {loadvoterarea: loadvoterarea},

              success: function(data) {
                $('#voterarea').html(data);
                $('#newvoterarea').html(data);
              }
            });
          }

  		function addCandidate() {
  			if($('#candidatename').val() != '') {
          var candidatename = $('#candidatename').val();
        }
        else {
          $('#error_candidatename').val('Candidate name is required!');
        }
  			if($('#voterarea').val() != ''){
          var voterarea = $('#voterarea').val();
        }
        else {
          $('#error_voterarea').val('Voter area is required!');
        }

  			$.ajax({
  				url: "candidates_backend.php",
  				type: 'post',
  				data: {candidatename: candidatename, voterarea: voterarea},

  			 	success: function(data) {
						loadCandidates();
						$('#candidatename').val('');
						$('#voterarea').val('');
  			 	}
  			});
  		}

        	function getCandidateDetails(id) {
				$("#hidden_candidate_id").val(id);
	  			
				$.post("candidates_backend.php", {
						id: id
					},
        		
					function(data) {
						var user = JSON.parse(data);
						$("#newcandidatename").val(user.candidatename);
						$("#newvoterarea").val(user.voter_area);
					}
				);
    			
    			$("#updateModal").modal("show");
    		}

			function updateCandidateDetails() {
    			var newcandidatename = $("#newcandidatename").val();
    			var newvoterarea = $('#newvoterarea').val();
    			var hidden_candidate_id = $("#hidden_candidate_id").val();
    			
				$.post("candidates_backend.php", {
						hidden_candidate_id: hidden_candidate_id,
						newcandidatename: newcandidatename,
						newvoterarea: newvoterarea
					},

					function(data) {
						$("#updateModal").modal("hide");
						loadCandidates();
					}
				);
			}

      function deleteCandidate(deleteid) {
    		var conf = confirm("Are you sure you want to delete this candidate and associated votes?");

        if(conf == true) {
          $.ajax({
          	url: "candidates_backend.php",
          	type: 'POST',
            data: {deleteid: deleteid},

            success: function(data) {
          		loadCandidates();
        		}
      		});
        }
      }
  		</script>
	</body>
</html>