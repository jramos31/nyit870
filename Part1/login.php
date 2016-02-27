<?php 
// Include the confuration and header
require("config.inc.php");
include("header.php"); ?>


		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

		<?php //**** Handles the email and password entry ****
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			require(MYSQL_CONN);
			
			// Validate user's email address
			if (!empty($_POST['email'])) {
				$email = mysqli_real_escape_string($dbc, $_POST['email']);
			} else {
				$email = FALSE;
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="center">You forgot to enter your email address!</p>								
							</div>
						</div>
					</div>';
			}
			
			// Validate user's password 
			if (!empty($_POST['pass'])) {
				$pw = mysqli_real_escape_string($dbc, $_POST['pass']);
			} else {
				$pw = FALSE;
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="center">You forgot to enter your password!</p>								
							</div>
						</div>
					</div>';
			}
			
			// If both are valid
			if ($email && $pw) {
				
				// Query database
				$q = "SELECT user_id, first_name, last_name, user_level FROM users WHERE (email='$email' AND pass=SHA1('$pw')) AND active IS NULL";
				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// If the user was found in the database
				if (@mysqli_num_rows($r) == 1) {
					
					$_SESSION = mysqli_fetch_array($r, MYSQLI_ASSOC);
					mysqli_free_result($r);
					mysqli_close($dbc);
					
					// Redirect user to the home page
					$url = BASE_URL . 'index.php';			
					ob_end_clean();			
					header("Location: $url");
					exit();
					
				} else {  // No match found in database!
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="center">Either email address or password do not match those on file or you need to activate your account.</p>								
							</div>
						</div>
					</div>';
				}
			} else { 
				echo '<p>Please try again!</p>';
			}
		}
		?>

		
			<h1 class="page-header">Welcome</h1>
			
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Please Sign In</h3>
					</div>
					<div class="panel-body">
						<form role="form" action="login.php" method="post">
							<div class="form-group">
									<input class="form-control" placeholder="E-mail" name="email" type="email" value="">
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Password" name="pass" type="password" value="">
								</div>
								
								<input type="submit" name="submit" value="Login" class="btn btn-lg btn-success btn-block" >                    </fieldset>
						</form>
					</div>
				</div>
			</div>
		</div> <!-- END OF <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"> -->
		  
		
<?php include('footer.php'); ?>