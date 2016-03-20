<?php

require('config.inc.php');
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

	// Validate if a file is being uploaded
	$file_upload = FALSE;  // set the flag
	if (is_uploaded_file($_FILES['upload']['tmp_name'])) {

		$file_mime = $_FILES['upload']['type'];  // get file's MIME type

		if (empty($_POST['newfilename'])) {   // user did not specify a new file name
			$file_name = $_FILES['upload']['name'];
		} else {
			$file_name = $_POST['newfilename']; // user wants to upload the file but with a new name

			// Process the user-specified file name just in case
			// they didn't include a file extension in the name.
			// ** WARNING ** does not work if the period (.) is used as part of the file name (ie: file.name)
 			$file_ext_index = strrpos($file_name, '.');
			if ($file_ext_index == '') {  // add the proper file extension if the user left it out

				switch($file_mime) {
					case 'application/msword':
						$file_name = $file_name . '.doc';
						break;
					case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
						$file_name = $file_name . '.docx';
						break;
					default:
						echo '<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-warning">
									<p align="center">File must be a MS Word Document</p>
								</div>
							</div>
						</div>';
						exit(); // quit the script
				}
			}
		}  // End of IF:   if (empty($_POST['newfilename']))

		$source = $_FILES['upload']['tmp_name'];  // temporary location of uploaded file on web server
		$file_size = $_FILES['upload']['size'];   // get file size in bytes
		$max_file_size = $_POST['MAX_FILE_SIZE']; // Maximum file size as indicated in the form in assignment_list.php

		// Verify that it's type of file we want uploaded (Only Word Documents or PDF's)
		// ** Even if the user specifies a file name with an acceptable file extension,
		//    if the file type is not acceptable (image files) they'll still be rejected
		if ( $file_mime == 'application/msword' ||
		     $file_mime == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ) {

			// Check that the file size doesn't exceed the maximum limit (524 KB or 524288 Bytes)
			if ($file_size > $max_file_size) {
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning">
							<p align="center">That file exceeds the maximum allowable file size limit.
							Please reduce the file size or select a different file and try again.</p>
						</div>
					</div>
				</div>';
				include('footer.php');
				exit();
			}

			// Set up destination file path (absolute path) variable
			$target = ANNOUNCE_DOCS . DIRECTORY_SEPARATOR . $file_name;

			// Check if filename aleady exists in the direcotry
			if (file_exists($target)) {
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning">
							<p align="center">Error: A file with that name already exists.
							Please try changing the file name before uploading it.
							</p>
						</div>
					</div>
				</div>';
				exit();
			}

			// Move file to it's new home
			if (move_uploaded_file($source, $target)) {

				$file_upload = TRUE;    // SUCCESS

			} else {
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning">
							<p align="center">ERROR: FILE WAS NOT UPLOADED!!!</p>
						</div>
					</div>
				</div>';
				exit();
			}

		} else {  // Wrong file type
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning">
						<p align="center">The file you tried to upload was not the correct type.</p>
						<p align="center">Please upload only MS Word Documents (.doc, .docx)</p>
					</div>
				</div>
			</div>';
			include('footer.php');
			exit();
		}

	} // End of IF:     if (is_uploaded_file($_FILES['upload']['tmp_name']))

	//exit(); // Comment out or Remove this line to proceed with Database Insertion

	if ($subject && $body) {  // Both are validated

		$id = $_POST['course_id'];  // received from the announcement_list.php form as a hidden input
		
		if ($file_upload) { // A file was uploaded and validated
			// Add new announcement with "relative" file path into the database
			$file_path = ANNOUNCE_DIR . '/' . $file_name;
			$q = "INSERT INTO announcements (subject, content, file_path, date_posted, course_id)
				  VALUES ('" . mysqli_real_escape_string($dbc, $subject) . "', '" . mysqli_real_escape_string($dbc, $body) . "', '" . mysqli_real_escape_string($dbc, $file_path) . "' , NOW(), '$id')";
		} else {

			// Add new announcement into the database
			$q = "INSERT INTO announcements (subject, content, date_posted, course_id)
					VALUES ('" . mysqli_real_escape_string($dbc, $subject) . "', '" . mysqli_real_escape_string($dbc, $body) . "', NOW(), '$id')";
		}
		$r = mysqli_query($dbc, $q);

		if (mysqli_affected_rows($dbc) == 1) {

			// Redirect user back to the assignments list page

			$url = BASE_URL . "announcement_list.php?id=$id";
			ob_end_clean();
			header("Location: $url");
			exit();

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
