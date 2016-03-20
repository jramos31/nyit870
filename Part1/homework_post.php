<?php
// *** This script allows for students o upload their completed homework in MS Word format ***
//     - to access this page because it must accessed through the assignment_list.php page
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
?>

<!--  ****** Start of Page Content ******  -->
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<h1 class="page-header">Submit Your Homework</h1><br />

		<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // Homework document was submitted through the form

			// course_id number -- we'll need this value to be redirected back to the assignment_list page
			$c_id = $_POST['course'];

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

				// Verify that it's type of file we want uploaded (Only Word Documents )
				// ** Even if the user specifies a file name with an acceptable file extension,
				//    if the file type is not acceptable (image files) they'll still be rejected
				if ( $file_mime == 'application/msword' || $file_mime == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ) {

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
						exit();
					}

					// Set up destination file path
					// hard-coded values were assignment to constant variables in config.inc.php
					$target = HW_DOCS . DIRECTORY_SEPARATOR . $file_name;

					// Check if filename aleady exists in the direcotry
					if (file_exists($target)) {
						echo '<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-warning">
									<p align="center">Error: A file with that name already exists.
									Please try changing the file name before uploading it.<br>';
									echo 'To go back <a href="assignment_list.php?id=' . $c_id . '">Click Here</a></p>
								</div>
							</div>
						</div>';
						exit();
					}

					// Move file to it's new home
					if (move_uploaded_file($source, $target)) {

						$file_upload = TRUE;   //  SUCCESS

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
								<p align="center">Please upload only MS Word Documents (.doc, .docx)<br>';
								echo 'To go back <a href="assignment_list.php?id=' . $c_id . '">Click Here</a></p>
							</div>
						</div>
					</div></div>';
					include('footer.php');
					exit();
				}

			} else {
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
								<p align="center">You must upload your Homework Document.</p>
								<p align="center">Please upload only MS Word Documents (.doc, .docx)<br>';
								echo 'To go back <a href="assignment_list.php?id=' . $c_id . '">Click Here</a></p>
							</div>
						</div>
					</div></div>';
					include('footer.php');
					exit();
			}	// End of IF:   if (is_uploaded_file($_FILES['upload']['tmp_name']))

			//exit(); // Comment out or Remove this line to proceed with Database Insertion

			if ($file_upload) {

				if (!empty($_POST['body'])) {
					$body = htmlentities($_POST['body']);
				} else {
					$body = $_POST['title'];  // DEFAULT
				}

				$id = $_POST['asmnt_id'];  // received from the homework_post.php form as a hidden input
				$usr_id = $_SESSION['user_id'];
				$file_path = HW_DIR . '/' . $file_name;

				// Add a new record in the database for the homework document uploaded by the user (student)
				$q = "INSERT INTO homeworks (comments, file_path, asmnt_id, s_id) VALUES
						('" . mysqli_real_escape_string($dbc, $body) . "', '" . mysqli_real_escape_string($dbc, $file_path) . "', $id, $usr_id)";
				$r = mysqli_query($dbc, $q);

				if (mysqli_affected_rows($dbc) == 1) {

					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success"><p align="center">The new assignment has been posted.<br>';
							echo 'To go back <a href="assignment_list.php?id=' . $c_id . '">Click Here</a></p></div>
						</div>
					</div>
					</div>'; //   <!-- End of main page content  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->

					include('footer.php');
					exit();

				} else {
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">The homework assignment could not be posted due system error.</p></div>
						</div>
					</div>';
					exit();
				}

			} // End of IF: 	if ($file_upload)

		} //  End IF: if ($_SERVER['REQUEST_METHOD'] == 'POST')

		// Check for a valid asmnt_id from assignment_list.php
		if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {

			// These values will be needed for checking if this user (student) already submitted their homework docs for this assignment.
			// If they haven't already uploaded for this assignment, these values will be used  when inserting  a record to database
			// when hw docs are uploaded.
			$id = $_GET['id'];
			$hw_title = $_GET['title'];
			$course_id = $_GET['cid'];  // This value is necessary for getting redirected back to the assignment_list for this particular course
			$usr_id = $_SESSION['user_id'];

			// Build query
			$q = "SELECT a.asmnt_id, a.asmnt_title, a.content, h.file_path, h.s_id, a.date_posted, a.course_id
				FROM assignments AS a
				INNER JOIN homeworks AS H USING(asmnt_id)
				WHERE a.asmnt_id=$id AND h.s_id=$usr_id";
			$r = mysqli_query($dbc, $q);

			if (!(mysqli_num_rows($r)>0)) { // Nothing submitted for this homework assignment

					echo '<div class="row">
							<div class="col-md-6 col-md-offset-2">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">"' . $hw_title . '"</h3>
									</div>
									<div class="panel-body">
										<form role="form" enctype="multipart/form-data" action="homework_post.php" method="post">
											<div class="form-group">
												<label>Comments (Optional):</label>
												<textarea class="form-control" name="body" row="3"></textarea>
											</div>

											<div class="form-group">
												<!-- Student must upload a MS Word document (.doc or .docx) -->
												<label>Upload a File</label><br>
												<label>Save File As:</label>
												<input class="form-control" placeholder="New File Name"
													name="newfilename" size="60" maxlength="100" type="text" value="">

												<label>Select File:</label>
												<input class="form-control" name="upload" type="file">

												<label><small>Select a MS Word Document (.doc, .docx) 524 KB or Smaller to be uploaded<small></label>
												<input name="MAX_FILE_SIZE" type="hidden" value="524288">

												<input name="asmnt_id" type="hidden" value="' . $id . '">
												<input name="title" type="hidden" value="' . $hw_title . '">
												<input name="course" type="hidden" value="' . $course_id . '">
											</div>

											<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-success btn-block">
										</form>
									</div>
								</div>
							</div>
					</div>';
			} else { // Homework Documents already submitted

				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success"><p align="center">You have already completed this assignment!.<br>';
						echo 'To go back <a href="assignment_list.php?id=' . $course_id . '">Click Here</a></p>
						</div>
					</div>
				</div>';

			}

		}  else {  // No valid asmnt_id, kill the script
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
				</div>
			</div></div>';
			include('footer.php');
			exit();
		} // End of  IF:  if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) )

		?>	
	</div> <!-- End of main page content  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->

<?php
include('footer.php');
?>
