<?php
// Logout page for the site
require("config.inc.php");
include("header.php");

// If there is no first_name session variable then redirect to home page
if (!isset($_SESSION['first_name'])) {

	$url = BASE_URL . 'index.php';
	ob_end_clean();
	header("Location: $url");
	exit();

} else {  // Log out user
	$_SESSION = array();
	session_destroy();
	setcookie(session_name(), '', time()-3600);

}

echo '
		<section id="content">
   			<article id="login">
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success">
							<h3 align="center">You have succesfully logged out! </h3>
							<h4 align="center"><u><a href="index.php">Return to Home Page</a></u></h4>
						</div>
					</div>
				</div>
			</article>
		</section>';

?>
