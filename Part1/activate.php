<?php

// This page activates the user's account.
// They'll be brought here by clicking on the activation link sent to the email address
// they entered when they registered.
require('config.inc.php');
include('header.php');
// If $x and $y don't exist or aren't of the proper format, redirect the user:

if (isset($_GET['x'], $_GET['y'])
	&& filter_var($_GET['x'], FILTER_VALIDATE_EMAIL)
	&& (strlen($_GET['y']) == 32)
	) {
		// Update the database...
		require(MYSQL_CONN);
		$q = "UPDATE users SET active=NULL WHERE (email='" . mysqli_real_escape_string($dbc, $_GET['x']) . "') LIMIT 1";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

		// Print a customized message:
		if (mysqli_affected_rows($dbc) == 1) {
			echo '<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success">
							<h3 align="center">Your acount is now active. You may now log in.</h3>
						</div>
					</div>
				</div>
			</div>';
		} else {
			echo '<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning">
							<p align="center">Your account could not be activated.
							Please re-check the link or contact the system administrator.</p>
						</div>
					</div>
				</div>
			</div>';
		}

		mysqli_close($dbc);

		} else { // Redirect.
			$url = BASE_URL . 'index.php'; // Define the URL.
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		} // End of main IF-ELSE.
	
include('footer.php');
?>
