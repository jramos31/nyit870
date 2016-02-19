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

// Display logout message 
include("header.php");  // include the header again to display the modified links
echo '<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-warning"><h3 align="center">You have been logged out.</h3></div>
			</div>
		</div>
	</div>';

include("footer.php");
?>