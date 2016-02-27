<?php 
require('config.inc.php');
include('header.php');

// If there's no session variable for the user's email, 
// then they haven't logged in yet.
// Redirect the user to the login page
if (!isset($_SESSION['user_id'])) {
	
	$url = BASE_URL . 'login.php'; // Homepage's URL
	
	ob_end_clean(); // Delete the unsent buffered data
	header("Location: $url");
	exit();  //  Quit the script
	
} else {
?>

		<!--  ****** Start of Page Content ******  -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<h1 class="page-header">View My Grades</h1><br />
		  
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">Sorry This Page Is Under Construction.</p></div>
				</div>
			</div>
		</div>

<?php
}


include('footer.php');
?>