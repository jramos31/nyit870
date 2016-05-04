<?php
// *** This script list homework assignments posted by course's instructor ***
//     - The page will only list the assignments pertaining to a specific course
//     - to access this page because it must accessed through
//       the view_courses.php page.

require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
require('pagination_links.php');
?>

		<!--  ****** Start of Page Content ******  -->
        <section id="content">
   			<article>
        		<h1 class="page-header">Homework Assignments</h1><br />

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
        						FROM assignments AS a INNER JOIN courses AS c ON a.course_id=c.course_id
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
        			show_page_links($pages, $display, $start, $id, "assignment_list");

        			if ($_SESSION['user_level'] == '1') {  // If user is PROFESSOR
        				// Build the query
        				$q = "SELECT a.asmnt_title, a.content, a.file_path, a.date_posted, a.date_due, c.course_num, c.course_title, c.section_num, c.semester
        					  FROM assignments AS a INNER JOIN courses AS c ON a.course_id=c.course_id
        					  WHERE c.course_id=$id
        					  ORDER BY a.date_posted DESC LIMIT $start, $display";
        				$r = mysqli_query($dbc, $q);

        				if (!(mysqli_num_rows($r)>0)) { // No assignments
        					echo '
                                <div class="row">
    						        <div class="col-lg-12">
        							    <div class="alert alert-warning">
                                            <p align="center">There are no assignments for this course.</p>
                                        </div>
				                    </div>
        					    </div>';
        				} else { // Fetch assignments

        					// *** Show Posted Assignments ***
        					echo '
                                <div class="row">
			                        <div class="col-lg-12">
					                    <div class="panel-body">';

        					$course_printed = FALSE;  // Set this flag to false because the course name for this assignments
        											  // only needs to printed once since it serves as a list heading,
        											  // all the assignments will be listed below it

        					while ($messages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

        						if (!$course_printed) {  // Display only once
        							echo "<h3>Course: {$messages['course_num']} {$messages['course_title']} {$messages['section_num']}
        							&nbsp; &nbsp; &nbsp; &nbsp; Term: {$messages['semester']}</h3><br>";
        							$course_printed = TRUE;
        						}

								// Format the dates
								$posted_on = new DateTime($messages['date_posted']);
								$posted_on = $posted_on->format('m/d/Y');
								$due_on = new DateTime($messages['date_due']);
								$due_on = $due_on->format('m/d/Y');

        						// Display the message(s)
        						echo "<p><b>Date Posted:</b> &nbsp; &nbsp; &nbsp; {$posted_on}&nbsp; &nbsp; &nbsp;
										 <b>Due On: </b> &nbsp; &nbsp; &nbsp; {$due_on} &nbsp; &nbsp; &nbsp;
        								 <b>Assignment:</b> &nbsp; {$messages['asmnt_title']} &nbsp; &nbsp; &nbsp; <br>
        								 <b>Comment:</b> &nbsp; {$messages['content']}<br>";
        						if ( !($messages['file_path'] == NULL) ) {
        							// if instructor uploaded a document associated with the assignment,
        							// display the link to that document
        							echo "<b>Document:</b> &nbsp; <a href=\"{$messages['file_path']}\">Click Here</a><br><br></p>";
        						} else {
        							echo "<br><br></p>";
        						}
        					} // END OF: while
        					echo '
                                    </div>
                                </div>
                            </div>';

        				} 	// END OF:  		if ( !(mysqli_num_rows($r)>0) )

        				// Professor will also be able to post new homework assignments
						/*
						$current_date = new DateTime();
						$def_due_dte = $current_date->modify('+1 week');
						$due_dt_string = $def_due_dte->format('m-d-Y');
						$due_dt_string2 = $def_due_dte->format('Y-m-d H:i:s');
						$dt_str = "2012-07-08";
						$time_str = "12:31:50";
						$dt_str = $dt_str . $time_str;
						$new_dt = new DateTime($dt_str);
						$new_dt = $new_dt->format('Y-m-d H:i:s');
						*/

        				// *** Show Form ***
        				echo '
                            <div class="row">
        						<div class="col-md-6 col-md-offset-2">
        							<div class="panel panel-default">
        								<div class="panel-heading">
        									<h3 class="panel-title">Post A New Homework Assignment</h3>
        								</div>
        								<div class="panel-body">
        									<form role="form" enctype="multipart/form-data" action="assignment_post.php" method="post">
        										<div class="form-group">
													<label>Due Date: </label>
													<input class="form-control" placeholder="MM-DD-YYYY"
        												name="due_date" type="text" value=""><br>
        											<label>Subject:</label>
        											<input class="form-control" placeholder="Enter a subject (i.e. Homework #, Report #, etc.)"
        												name="subject" size="60" maxlength="100" type="text" value="">
        											<br>
        											<label>Assignment:</label>
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

        											<label><small>Select a MS Word Document (.doc, .docx) 524 KB or Smaller to be uploaded</small></label>
        											<input name="MAX_FILE_SIZE" type="hidden" value="524288">

        											<input name="course_id" type="hidden" value="' . $id .'">
        										</div>

        										<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-success btn-block">
        									</form>
        								</div>
        							</div>
        						</div>
        					</div>';

        			} // END OF: if ($_SESSION['user_level'] is a TEACHER)


        			if ($_SESSION['user_level'] == '0') {   // User is a STUDENT

        				// Build the query
        				$q = "SELECT a.asmnt_id, a.asmnt_title, a.content, a.file_path, a.date_posted, a.date_due, c.course_num, c.course_title, c.section_num, c.semester
        					  FROM assignments AS a INNER JOIN courses AS c ON a.course_id=c.course_id
        					  WHERE c.course_id=$id
        					  ORDER BY a.date_posted DESC LIMIT $start, $display";
        				$r = mysqli_query($dbc, $q);

        				if (!(mysqli_num_rows($r)>0)) { // No assignments
        					echo '
                                <div class="row">
    						        <div class="col-lg-12">
    							        <div class="alert alert-warning">
                                            <p align="center">There are no assignments for this course.</p>
                                        </div>
					                </div>
        					    </div>';
        				} else { // Fetch Assignments

        					// *** Show Posted Assignments ***
        					echo '
                                <div class="row">
        						    <div class="col-lg-12">
        						        <div class="panel-body">';

        					$course_printed = FALSE;  // Set this flag to false because the course name for this assignments
        											  // only needs to printed once since it serves as a list heading,
        											  // all the assignments will be listed below it

        					while ($messages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

        						if (!$course_printed) {  // Display only once
        							echo "<h3>Course: {$messages['course_num']} {$messages['course_title']} {$messages['section_num']}
        										&nbsp; &nbsp; &nbsp; &nbsp; Term: {$messages['semester']}</h3><br>";

        							$course_printed = TRUE;
        						}

								// Format the dates
								$posted_on = new DateTime($messages['date_posted']);
								$posted_on = $posted_on->format('m/d/Y');
								$due_on = new DateTime($messages['date_due']);
								$due_on = $due_on->format('m/d/Y');

        						// Display the message(s) and link to the homework upload form.
        						// The link will pass the value of asmnt_id and asmnt_title to the form.
        						echo "<p><b>Date Posted:</b> &nbsp; &nbsp; &nbsp; {$posted_on}&nbsp; &nbsp; &nbsp;
										 <b>Due On: </b> &nbsp; &nbsp; &nbsp; {$due_on} &nbsp; &nbsp; &nbsp;
        								 <b>Assignment:</b> &nbsp; {$messages['asmnt_title']} <br>
        								 <b>Comment:</b> &nbsp; {$messages['content']}<br>";
        						echo '<a href="homework_post.php?id=' . $messages['asmnt_id']  . '&title=' . urlencode($messages['asmnt_title']) . '&cid='. $id .'">Submit This Assignment</a><br>';
        						if ( !($messages['file_path'] == NULL) ) {
        							// if instructor uploaded a document associated with the assignment,
        							// display the link to that document
        							echo "<b>Document:</b> &nbsp; <a href=\"{$messages['file_path']}\">Click Here</a><br><br></p>";
        						} else {
        							echo "<br></p>";
        						}
        					}

        					echo '
                                        </div>
                                    </div>
                                </div>';
        				} // END OF: Else Fetch Assignments

        			}  // END OF: if ($_SESSION['user_level'] is a STUDENT

        		}  else {  // No valid course_id, kill the script
        			echo '
                        <div class="row">
    				        <div class="col-lg-12">
			                    <div class="alert alert-warning">
                                    <p align="center">This page was accessed in error.</p>
                                </div>
                            </div>
                        </div>';
        			include('footer.php');
        			exit();
        		} // End of main IF
        		?>
        </article>
<?php
include('footer.php');
?>
