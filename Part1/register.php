<?php 
// This page registers accounts for new users 
require('config.inc.php');
include('header.php'); ?>

		<!--  ****** Start of Page Content ******  -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
		<?php   //***** Handles the registration form  data entry *****
		if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

			// Need the database connection:
			require(MYSQL_CONN);
			
			// Trim all the incoming data:
			$trimmed = array_map('trim', $_POST);
			
			// Assume invalid values:
			$fn = $ln = $e = $p = FALSE;
			
			// Check for a first name:
			if (preg_match('/^[A-Z \'.-]{2,20}$/i',$trimmed['first_name'])) {
				$fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
			} else {
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">Please enter your first name!</p></div>
						</div>
					</div>';
			}
			
			// Check for a last name!
			if (preg_match('/^[A-Z \'.-]{2,20}$/i',$trimmed['last_name'])) {
				$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
			} else {
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">Please enter your last name!</p></div>
						</div>
					</div>';
			}
			
			// Check for an email address:
			if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
				$e = mysqli_real_escape_string($dbc, $trimmed['email']);
			} else {
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">Please enter a valid email address!</p></div>
						</div>
					</div>';
			}
			
			// Check for a password and match against the confirmed password:
			if (preg_match('/^\w{4,20}$/', $trimmed['password1']) ) {
				if ($trimmed['password1'] == $trimmed['password2']) {
					$p = mysqli_real_escape_string($dbc, $trimmed['password1']);
				} else {
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">Your password did not match the confirmed password!</p></div>
						</div>
					</div>';
				}
			} else {
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">Please enter a valid password!</p></div>
						</div>
					</div>';
			}
			
			if ($fn && $ln && $e && $p) {  // If everything's OK...
				
				// Make sure the email address is available:
				$q = "SELECT user_id FROM users WHERE email='$e'";
				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
				
				if (mysqli_num_rows($r) == 0) { // Available.
					
					// Create the activation code:
					$a = md5(uniqid(rand(), true));
					
					// Add the user to the database:
					$q = "INSERT INTO users (email, pass, first_name, last_name, active, registration_date) VALUES ('$e', SHA1('$p'), '$fn', '$ln', '$a', NOW() )";
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
					
					if (mysqli_affected_rows($dbc) == 1) {  // If it ran OK.
						
						// Send the email:
						$body = "Thank you for registering at DP-JR Blackboard. To activate your account, please click on this link:\n\n";
						$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
						mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@sitename.com');
						
						// Finish the page:
						echo '<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-success">
										<h3 align="center">Thank you for registering! A confirmation email has been sent to your address. 
										                   Please click on the link in that email in order to activate your account.
									    </h3>
									</div>
								</div>
							</div>';
						include('footer.php');  // Include the HTML footer.
						exit(); // Stop the page.
						
					} else {  // If it did not run OK.
						echo '<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-warning">
										<p align="center">You could not be registered due to a system error. We apologize for any incovenience.</p>
									</div>
								</div>
							</div>';
					}
					
				} else {  // The email address is not available.
					echo '<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-warning">
									<p align="center">That email address has already been registered. If you have forgotton your password, 
										              use the link at left to have your password sent to you.
									</p>
								</div>
							</div>
						</div>';			
				}
					
			} else {  // If one of the data tests failed.
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">Please try again.</p></div>
						</div>
					</div>';
			}
			
			mysqli_close($dbc);
			
		} // End of the main Submit conditional.
		?>

		
			<h1 class="page-header">Register Your Account</h1>
			
			<div class="col-md-4 col-md-offset-4">
			
				<div class="panel panel-default">
					<div class="panel-heading"><h3 class="panel-title">Register</h3></div>
					<div class="panel-body">
						<form role="form" action="register.php" method="post">				
							<div class="form-group">
								<input class="form-control" placeholder="First Name" type="text" 
									name="first_name" size="20" maxlength="20" 
									value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>">
								<br />
								<input class="form-control" placeholder="Last Name" type="text" 
									name="last_name" size="20" maxlength="40" 
									value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>">
								<br />

								<input class="form-control" placeholder="Email" type="email" 
									name="email" size="30" maxlength="60" 
									value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>">
								<br />
								
								<input class="form-control" placeholder="Password" type="password" 
									name="password1" size="20" maxlength="20" 
									value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>">
									<small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small>
								<br />
								
								<input class="form-control" placeholder="Confirm Password" type="password" 
									name="password2" size="20" maxlength="20" 
									value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>">
								
								
							</div>
							
							<input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Register" /></div>
							
						</form>
					</div>
				</div>
			</div>
		</div>  <!--  END OF Page Content  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
<?php include('footer.php'); ?>