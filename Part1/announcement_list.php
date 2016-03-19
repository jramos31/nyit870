<?php
// *** This script list anouncements posted by course's instructor ***
//     - The page will only list the announcements pertaining to a specific course
//     - to access this page because it must accessed through
//       the view_courses.php page.

require('config.inc.php');
include('header.php');

require_once('mysqli_connect.php');

require('pagination_links.php');
?>

		<!--  ****** Start of Page Content ******  -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<h1 class="page-header">Class Announcements</h1><br />

		<?php
		// Check for a valid course_id
		if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {  		// received from view_courses.php

			$id = $_GET['id'];



			// ****   Setup Pagination   ****
			$display = 5;  // Limit the number of records shown on each page

			// Number of pages
			if (isset($_GET['p']) && is_numeric($_GET['p'])) {  // Determined

				$pages = $_GET['p'];

			} else {		// Need to determine

				// Get a count of the number of records

				$q = "SELECT COUNT(*)
						FROM announcements AS a INNER JOIN courses AS c ON a.course_id=c.course_id
						WHERE c.course_id=$id";
				$r = @mysqli_query($dbc, $q);
				$row = @mysqli_fetch_array($r, MYSQLI_NUM);
				$records = $row[0];

				// Calculate number of pages
				if ($records > $display) {  // More than 1 page
					$pages = ceil($records/$display);
				} else {

					$pages = 1;
				}
			}

			// Determine starting point of the results
			if (isset($_GET['s']) && is_numeric($_GET['s'])) {
				$start = $_GET['s'];
			} else {
				$start = 0;
			}

			// **** End - Setup Pagination  ****************

			// The function is defined in pagination_links.php
			show_page_links($pages, $display, $start, $id, "announcement_list");






			// Build the query
			$q = "SELECT a.subject, a.content, a.file_path, a.date_posted, c.course_num, c.course_title, c.section_num
			      FROM announcements AS a INNER JOIN courses AS c ON a.course_id=c.course_id
				  WHERE c.course_id=$id
				  ORDER BY a.date_posted DESC LIMIT $start, $display";
			$r = mysqli_query($dbc, $q);

			if (!(mysqli_num_rows($r)>0)) { // No announcements
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning"><p align="center">There are no announcements for this course.</p></div>
					</div>
				</div>';
			} else { // Fetch announcements

				echo '<div class="row">
					<div class="col-lg-12">';

				$course_printed = FALSE;  // Set this flag to false because the course for this announcement
										  // only needs to printed once, all the announcements will printed
										  // below it

				while ($messages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

					if (!$course_printed) {
						echo "<h3>Course: {$messages['course_num']} {$messages['course_title']} {$messages['section_num']}</h3><br>";  // Display only once
						$course_printed = TRUE;
					}

					// Display the message
					echo "<p><b>Date:</b> &nbsp; &nbsp; &nbsp; {$messages['date_posted']}<br>
					         <b>Subject:</b> &nbsp; {$messages['subject']}<br>
							 <b>Message:</b> &nbsp; {$messages['content']}<br>";
					if ( !($messages['file_path'] == NULL) ) {
							// if instructor uploaded a document associated with the assignment,
							// display the link to that document
							echo "<b>Document:</b> &nbsp; <a href=\"{$messages['file_path']}\">Click Here</a></p><br>";
					} else {
							echo "</p><br>";
					}
				} // END WHILE
				echo '</div></div>';

			} // END OF:    if ( !(mysqli_num_rows($r)>0) )

			// Professor will also be able to post new course announcements
			// uploading documents pertaining to announcements is optional
			// *** Show the form ***
			if ($_SESSION['user_level'] == '1') {
				echo '<div class="row">
					<div class="col-md-6 col-md-offset-2">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Post New Announcement</h3>
							</div>
							<div class="panel-body">
								<form role="form" enctype="multipart/form-data" action="announcement_post.php" method="post">
									<div class="form-group">
										<label>Subject:</label>
										<input class="form-control" placeholder="Enter a subject"
											name="subject" size="60" maxlength="100" type="text" value="">
										<br>
										<label>Announcement:</label>
										<textarea class="form-control" name="body" row="3"></textarea>
									</div>

									<div class="form-group">
										<!-- instructor may upload MS Word and PDF documents if part of the assignment -->
										<label>Upload a File (Optional)</label><br>
										<label>Save File As:</label>
										<input class="form-control" placeholder="New File Name"
											name="newfilename" size="60" maxlength="100" type="text" value="">

										<label>Select File:</label>
										<input class="form-control" name="upload" type="file">

										<label><small>Select a MS Word Document (.doc, .docx) or PDF file of 524 KB or Smaller to be uploaded</small></label>
										<input name="MAX_FILE_SIZE" type="hidden" value="524288">

										<input name="course_id" type="hidden" value="' . $id .'">

									</div>
									<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-success btn-block">
								</form>
							</div>
						</div>
					</div>
				</div>';
			}

		}  else {  // No valid course_id, kill the script
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
				</div>
			</div>';
			include('footer.php');
			exit();
		} // End of main IF
		?>

		</div> <!-- End of main page content  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
