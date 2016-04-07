<?php
// *** This script will delete a posted announcement in the database ***
//     - If a file associated with an announcement has been uploaded 
//        this script will also delete it from the directory
require('config.inc.php');
include('header.php');
?>

		<!--  ****** Start of Page Content ******  -->
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Delete Announcements</h1><br />

		<?php
		// Must check for valid ann_id  (Primary Key from announcements table)
		if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {  		// IF received from announcement_list_all.php

			$id = $_GET['id'];

		}  elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // IF received from form submission

			$id = $_POST['id'];

		} else {  // No valid ann_id, kill the script
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
				</div>
			</div>';
			include('footer.php');
			exit();
		}

		require_once('mysqli_connect.php');

		// Check for form submission.
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			if ($_POST['sure'] == 'Yes') {
				
				// Build select query to find any uploaded file associated with the announcement
				$q = "SELECT file_path FROM announcements WHERE ann_id=$id LIMIT 1";
				$r = @mysqli_query($dbc, $q);
				if (mysqli_num_rows($r) == 1) { 
					$row = mysqli_fetch_array($r, MYSQLI_NUM);
					$file_path = $row[0];	
				} else {
					$file_path = NULL;  // No announcement file in directory
				}
				// Build the DELETE query
				$q = "DELETE FROM announcements WHERE ann_id=$id LIMIT 1";
				$r = @mysqli_query($dbc, $q);

				if (mysqli_affected_rows($dbc) == 1) {  // DELETE query was successful
					
					//  If a file was uploaded for this announcement then delete it from the directory
					if (file_exists($file_path)) {
						if (!unlink($file_path)){
							echo '<b>ERROR: Could not delete: ' . $file_path . '</b>';
							exit();
						} 				
					}					
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success"><p align="center">Posted announcement has been deleted!</p></div>
						</div>
					</div>';

				} else {  // DELETE query failed

					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">This announcement could not be deleted due to system error.</p></div>
						</div>
					</div>';

					// Debugging message only
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p></div>
						</div>
					</div>';
				}

			} else {
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning"><p align="center">The announcement has not been deleted.</p></div>
					</div>
				</div>';
			}

		} else {  // No form submission, so show the form

			// Get user's info
			$q = "SELECT subject, content, file_path FROM announcements WHERE ann_id=$id";
			$r = @mysqli_query($dbc, $q);

			if (mysqli_num_rows($r) == 1) {  // user_id is valid, show the form

				$row = mysqli_fetch_array($r, MYSQLI_NUM);

				// Show announcement record to be deleted
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning"><h3 align="left">Subject: ' . $row[0] . '</h3><h3 align="left">Content: ' . $row[1] . '</h3>
						<h3 align="left">File: ' . $row[2] . '</h3>						
						</div>
					</div>
				</div>';

				// Create the form
				echo '<div class="col-md-3 col-md-offset-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Are you sure you want to delete this announcement?</h3></div>
							<div class="panel-body">
								<form role="form" action="delete_announcement.php" method="post">
									<div class="form-group">
										<label class="radio-inline">
											<input type="radio" name="sure" value="Yes"> Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="sure" value="No" checked> No
										</label>
									</div>

									<input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Submit">
									<input type="hidden" name="id" value="'. $id . '">
								</form>
							</div>
						</div>
				</div>';

			} else { // Not a valid user id
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
					</div>
				</div>';
			}
		} // End of main IF - form submission

		mysqli_close($dbc);
		?>	  
    </div> <!--  ****** End of Page Content ******  -->
<?php include('footer.php'); ?>
