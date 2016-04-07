<?php
// *** This script retrieves all the records from the users table  ***
//     - Only users logged in as administrators will be able
//     - to access this page.

// Include the config and header files and the connection string
require('config.inc.php');
include('header.php');

?>

		<!--  ****** Start of Page Content ******  -->
        <section id="content">
    		<article>
                <h1 class="page-header">Edit User</h1><br />

        		<?php
        		// Must check for valid user_id
        		if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {  		// IF received from view_users.php

        			$id = $_GET['id'];

        		}  elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // IF received from form submission

        			$id = $_POST['id'];

        		} else {  // No valid user_id, kill the script
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

        			// Array for storing relevant error messages
        			$error_msgs = array();

        			// Check that user enters their first and last name and email address
        			if (empty($_POST['first_name'])) {
        				$error_msgs[] = '<p align="center">Enter your first name.</p>';
        			} else {
        				$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        			}

        			if (empty($_POST['last_name'])) {
        				$error_msgs[] = '<p align="center">Enter your last name.</p>';
        			} else {
        				$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        			}

        			if (empty($_POST['email'])) {
        				$error_msgs[] = '<p align="center">Enter your email address.</p>';
        			} else {
        				$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
        			}

        			// If user entered all information
        			if (empty($error_msgs))  {// No errors

        				// Make sure the new email address the user entered is not already registered by another user
        				$q = "SELECT user_id FROM users WHERE email='$e' AND user_id != $id";
        				$r = @mysqli_query($dbc, $q);

        				if (mysqli_num_rows($r) == 0) {   //  The email is unique, so make the change in the system
        					$q = "UPDATE users SET first_name='$fn', last_name='$ln', email='$e' WHERE user_id=$id LIMIT 1";
        					$r = @mysqli_query ($dbc, $q);
        					if (mysqli_affected_rows($dbc) == 1)  {		// Update successfull
        						echo '<p>User info has been updated</p>';
        					} else {			// Update Not successfull
        						echo '<div class="row">
        							<div class="col-lg-12">
        								<div class="alert alert-warning">
        									<p align="center">The user could not be edited due to a system error. We apologize for any inconvenience.</p>
        									<p>' . mysqli_error($dbc) . '<br>Query: ' . $q . '</p>
        								</div>
        							</div>
        						</div>';
        					}
        				} else {  // Email is not unique
        					echo '<div class="row">
        						<div class="col-lg-12">
        							<div class="alert alert-warning"><p align="center">The email address has already been registered.</p></div>
        						</div>
        					</div>';
        				}
        			} else {  // Display the errors
        				echo '<div class="row">
        						<div class="col-lg-12">
        							<div class="alert alert-warning">
        							<p align="center">The following error(s) were found: </p>';
        							foreach ($error_msgs as $msg) {
        								echo " $msg";
        							}
        				echo '</div>
        						</div>
        					</div>';
        			}
        		}

        		// Retrieve the users info to display in the form before they make any changes
        		$q = "SELECT first_name, last_name, email FROM users WHERE user_id=$id";
        		$r = @mysqli_query ($dbc, $q);
        		if (mysqli_num_rows($r) == 1) {  // Valid user id

        			$row = mysqli_fetch_array($r, MYSQLI_NUM);

        			echo '<div class="panel panel-default">
        					<div class="panel-heading"><h3 class="panel-title">Edit User Info</h3>
                            </div>
        					<div class="panel-body">
        						<form role="form" action="edit_user.php" method="post">
        							<div class="form-group">
        								<input class="form-control" placeholder="First Name" type="text"
        									name="first_name" size="20" maxlength="20" value="' . $row[0] . '">
        								<br>
        								<input class="form-control" placeholder="Last Name" type="text"
        									name="last_name" size="20" maxlength="40" value="' . $row[1] . '">
        								<br>
        								<input class="form-control" placeholder="Email" type="email"
        									name="email" size="30" maxlength="60" value="' . $row[2] . '">
        								<br>
        							</div>
        							<input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Submit" >
        							<input type="hidden" name="id" value="' . $id . '" >
        						</form>
        					</div>
        				</div>';

        		} else {  // User id was not valid
        			echo '<div class="row">
        				<div class="col-lg-12">
        					<div class="alert alert-warning"><p align="center">This page has been accessed in error.</p></div>
        				</div>
        			</div>';
        		}
        		mysqli_close($dbc);
        		?>
		</article>
		<!--  ****** End of Page Content ******  -->
<?php 	include('footer.php'); ?>
