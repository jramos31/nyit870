<?php 
include('header.php');
require_once('mysqli_connect.php');
// This script does the form handling when an new homework assignment is posted
// by the course instructor.
// Only the course instructor will be able to post when they assign homeworks.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // A new homework assignment was submitted through the form
	
	// Validate that data was entered into the text fields
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
	
	
	
	
/**************   TO DO ****************************************************
	// Validate if a file is being uploaded
	$file_upload = FALSE;  // set the flag
	if (isset($_FILES['upload'])) {
		
		// If a file is being uploaded, verify that it's the right type. 
		$allowed = array('application/msword', 'application/MSWORD','application/pdf');
		
		if (in_array($_FILES['upload']['type'], $allowed)) {
			// Move the file to the destination folder
			if (move_uploaded_file($_FILES['upload']['tmp_name'],"/uploads_assignments/{$_FILES['upload']['name']}" )) {
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success"><p align="center">The file was uploaded.</p></div>
					</div>
				</div>';
				$file_upload = TRUE;
			}
			
			
		} else { // Wrong file type
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">The file must be a PDF or Word Document.</p></div>
				</div>
			</div>';
			
			//$file_upload = FALSE;
			include('footer.php');
			exit(); //quit the script
		}
	} // End of if (isset($_FILES['upload']))
*************  END TO DO        ********/	


	if ($subject && $body) {  // Both are validated
		
		$id = $_POST['course_id'];  // received from the announcement_list.php form as a hidden input
		
		// Add new announcement into the database
		$q = "INSERT INTO assignments (asmnt_title, content, date_posted, course_id) 
		      VALUES ('" . mysqli_real_escape_string($dbc, $subject) . "', '" . mysqli_real_escape_string($dbc, $body) . "', NOW(), '$id')";
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_affected_rows($dbc) == 1) {
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-success"><p align="center">The new assignment has been posted.</p></div>
				</div>
			</div>';
			
		} else {
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">The homework assignment could not be posted due system error.</p></div>
				</div>
			</div>';
		}
	}


/************************   TO DO   ********************************************	
	if ($file_upload) {  
		if ($_FILES['upload']['error'] > 0) {   // Error checking for file upload
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">File could not be uploaded because: '; 
				
					// Print the specific error
					switch ($_FILES['upload']['error']) {
						case 1:
							print 'File exceeds upload_max_filesize setting in php.ini!';
							break;
						case 2:
							print 'File exceeds MAX_FILE_SIZE setting in the form!';
							break;
						case 3:
							print 'File was only partially uploaded!';
							break;
						case 4:
							print 'No file was uploaded!';
							break;
						case 6:
							print 'No temp folder was available!';
							break;
						case 7:
							print 'Unable to write to disk!';
							break;
						case 8:
							print 'File upload stopped!';
							break;
						default:
							print 'A system error occurred!';
							break;
					} // end switch 
					
			echo '</p></div>
				</div>
			</div>';
		} // End error checking IF
		
		// Delete the file if it still exists
		if (file_exists($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])) {
			unlink($_FILES['upload']['tmp_name']);
		}
	} // End of if ($file_upload)
****************************     END TO DO    **********************************/

		
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