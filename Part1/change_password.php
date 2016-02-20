<?php  
// *****  This script will allow a user who is currently signed in to change their password.  *****
//         - Since the user's email and current password was already provided during log in, 
//         - they'll only need to provide the new password and its confirmation.

// Include the confuration and header
require("config.inc.php");
include("header.php"); 

// If there's no session variable for the user's email, 
// redirect the user back to the homepage
if (!isset($_SESSION['user_id'])) {
	
	$url = BASE_URL . 'index.php'; // Homepage's URL
	
	ob_end_clean(); // Delete the unsent buffered data
	header("Location: $url");
	exit();  //  Quit the script
	
}

?>

		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

		<?php //**** Handles the new password entry and confirmation ****
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			require(MYSQL_CONN);
			
			$pw = FALSE;
			
			// Match the new entered password against the confirmation password 
			if (preg_match('/^(\w){4,20}$/', $_POST['password1'])) {
				
				if ($_POST['password1'] == $_POST['password2']) {
					$pw = mysqli_real_escape_string($dbc, $_POST['password1']);
				} else {
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="center">The password you entered did not match the confirmed password!</p>
							</div>
						</div>
					</div>';
				}
			} else {
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="center">Please enter a valid password!</p>
							</div>
						</div>
					</div>';
			}
			
			// If both password entries matched
			if ($pw) {
				
				// Create the UPDATE query
				$q = "UPDATE users SET pass=SHA1('$pw') WHERE user_id={$_SESSION['user_id']} LIMIT 1";
				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows($dbc) == 1) { // UPDATE query was successful
					
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success">
								<h3 align="center">Your password has been successfully changed!</h3>
							</div>
						</div>
					</div>';
					
					// Close the database connection, include the page footer and quit the script.
					mysqli_close($dbc);
					include('footer.php');
					exit();
					
				} else {  // UPDATE query failed
					
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="left">The password was not changed. The new password can not be the same as the current password.
								If you think there has been an error, please contact your system administrator.</p>
							</div>
						</div>
					</div>';
				}
				
			} else { // Validation failed 
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="center">Please try again.</p>
							</div>
						</div>
					</div>';
			}
			
			// Close database connection 
			mysqli_close($dbc);
			
		}  // End of main IF 
		?>

		
			<h1 class="page-header">User Registration</h1>
			
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Change Your Password</h3>
					</div>
					<div class="panel-body">
						<form role="form" action="change_password.php" method="post">
							<div class="form-group">
								<input class="form-control" placeholder="New Password" 
									name="password1" size="20" maxlength="20" type="password" value="">
								<small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small>
								<br />
								<input class="form-control" placeholder="Confirm New Password" 
									name="password2" size="20" maxlength="20" type="password" value="">
							</div>
							
							<input type="submit" name="submit" value="Change My Password" class="btn btn-lg btn-success btn-block" />                    </fieldset>
						</form>
					</div>
				</div>
			</div>
		</div> <!-- END OF <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"> -->
		  
		
<?php include('footer.php'); ?>