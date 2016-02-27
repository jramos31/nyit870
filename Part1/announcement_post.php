<?php 
include('header.php');
require_once('mysqli_connect.php');
// This script does the form handling when an new announcement isposted
// by the course instructor.
// Only the course instructor will be able to post announcements.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // A new announcement was submitted through the form
	
	// Validate that data was entered into the subject and announcement fields
	if (!empty($_POST['subject'])) {
		$subject = htmlentities($_POST['subject']);
	} else {
		$subject = FALSE;
		echo '<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-warning"><p align="center">Please enter a subject for this announcement.</p></div>
			</div>
		</div>';
	}
	if (!empty($_POST['body'])) {
		$body = htmlentities($_POST['body']);
	} else {
		$body = FALSE;
		echo '<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-warning"><p align="center">Please enter a message for this announcement.</p></div>
			</div>
		</div>';
	}
	
	if ($subject && $body) {  // Both are validated
		
		$id = $_POST['course_id'];  // received from the announcement_list.php form as a hidden input
		
		// Add new announcement into the database
		$q = "INSERT INTO announcements (subject, content, date_posted, course_id) 
		      VALUES ('" . mysqli_real_escape_string($dbc, $subject) . "', '" . mysqli_real_escape_string($dbc, $body) . "', NOW(), '$id')";
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_affected_rows($dbc) == 1) {
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-success"><p align="center">Course announcement has been posted.</p></div>
				</div>
			</div>';
			
		} else {
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">The announcement could not be posted due system error.</p></div>
				</div>
			</div>';
		}
	}
	
} else { // If somehow this paged was accessed directly, quit the script
	echo '<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
		</div>
	</div>';
	include('footer.php');
	exit();
}

include('footer.php');
?>