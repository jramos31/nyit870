<?php
// *** This script will delete a user's record in the database ***
//     - Only users logged in as administrators will be able
//       to access this page because it must accessed through
//       the view_users.php page.
require('config.inc.php');
include('header.php');
?>

		<!--  ****** Start of Page Content ******  -->
        <section id="content">
    		<article>
                <h1 class="page-header">Delete User</h1><br />

        		<?php
        		// Must check for valid user_id
        		if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {  		// IF received from view_users.php

        			$id = $_GET['id'];

        		}  elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // IF received from form submission

        			$id = $_POST['id'];

        		} else {  // No valid user_id, kill the script
        			echo '
                        <div class="row">
            			    <div class="col-lg-12">
            					<div class="alert alert-warning">
                                    <p align="center">This page was accessed in error.</p>
                                </div>
            				</div>
            			</div>';
        			include('footer.php');
        			exit();
        		}

        		require_once('mysqli_connect.php');

        		// Check for form submission.
        		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        			if ($_POST['sure'] == 'Yes') {

        				// Build the DELETE query
        				$q = "DELETE FROM users WHERE user_id=$id LIMIT 1";
        				$r = @mysqli_query($dbc, $q);

        				if (mysqli_affected_rows($dbc) == 1) {  // DELETE query was successful

        					echo '
                                <div class="row">
            					    <div class="col-lg-12">
            							<div class="alert alert-success">
                                            <p align="center">User has been deleted!</p>
                                        </div>
            						</div>
            					 </div>';
        				} else {  // DELETE query failed
        					echo '
                                <div class="row">
            						<div class="col-lg-12">
            							<div class="alert alert-warning">
                                            <p align="center">This user could not be deleted due to system error.</p>
                                        </div>
            						</div>
            					 </div>';

        					// Debugging message only
        					echo '
                                <div class="row">
            						<div class="col-lg-12">
            							<div class="alert alert-warning">
                                            <p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>
                                        </div>
            						</div>
            					 </div>';
        				}

        			} else {
        				echo '
                        <div class="row">
            				<div class="col-lg-12">
            						<div class="alert alert-warning">
                                        <p align="center">The user has not been deleted.</p>
                                    </div>
            					</div>
            				</div>';
        			}

        		} else {  // No form submission, so show the form

        			// Get user's info
        			$q = "SELECT CONCAT(last_name, ', ', first_name) FROM users WHERE user_id=$id";
        			$r = @mysqli_query($dbc, $q);

        			if (mysqli_num_rows($r) == 1) {  // user_id is valid, show the form

        				$row = mysqli_fetch_array($r, MYSQLI_NUM);

        				// Show user record to be deleted
        				echo '
                            <div class="row">
            					<div class="col-lg-12">
            						<div class="alert alert-warning">
                                        <h3 align="center">Name: ' . $row[0] . '</h3>
            						</div>
            					</div>
            				</div>';

        				// Create the form
        				echo '
                            <div class="col-md-3 col-md-offset-4">
        						<div class="panel panel-default">
        							<div class="panel-heading">
        								<h3 class="panel-title">Are you sure you want to delete this user?</h3></div>
        							<div class="panel-body">
        								<form role="form" action="delete_user.php" method="post">
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
        				echo '
                        <div class="row">
            				<div class="col-lg-12">
            					<div class="alert alert-warning">
                                    <p align="center">This page was accessed in error.</p>
                                </div>
            				</div>
            			</div>';
        			}
        		} // End of main IF - form submission

        		mysqli_close($dbc);
        		?>
        </article>
<?php include('footer.php'); ?>
