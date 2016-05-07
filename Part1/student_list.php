<?php
// *** This script lists students registered in the selected course  ***
//     - This page because must accessed through the view_courses.php page.
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
require('pagination_links.php');
?>

	<!--  ****** Start of Page Content ******  -->
	<section id="content">
		<article>
			<h1 class="page-header">Student Listing and Course Assignments</h1><br />
			<?php

			if ($_SESSION['user_level'] == '1') {  // Only instructors should be able to access this page

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
									FROM users
										INNER JOIN students USING(user_id)
										INNER JOIN courses USING(course_id)
									WHERE students.course_id=$id";
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
					show_page_links($pages, $display, $start, $id, "student_list");

					// Build the retrieval query  - get list of students in this course
					$q = "SELECT users.user_id, CONCAT(users.first_name, ' ', users.last_name) AS student_name,
									CONCAT(courses.course_num, ' ', courses.course_title, ' - ', courses.section_num) AS my_course, courses.semester
							FROM users
								INNER JOIN students USING(user_id)
								INNER JOIN courses USING(course_id)
							WHERE students.course_id=$id
							ORDER BY my_course, users.last_name LIMIT $start, $display";
					$r = @mysqli_query($dbc, $q);

					if (!(mysqli_num_rows($r)>0)) { // No students
						echo '<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-warning"><p align="center">There are no students for this course.</p></div>
							</div>
						</div>';
					} else { // Fetch students

						// Get a count of the homework assignments for the course
						$q_asn_count = "SELECT COUNT(*) FROM assignments WHERE course_id=$id";
						$r_asn_count = @mysqli_query($dbc, $q_asn_count);
						$row = @mysqli_fetch_array($r_asn_count, MYSQLI_NUM);
						$asn_count = $row[0];


						if (!($asn_count > 0)) {		// No assignments
							echo '
								<div class="row">
									<div class="col-lg-12">
										<div class="alert alert-warning">
											<p align="center">There are no assignments for this course.</p>
										</div>
									</div>
								</div>';
						} else {
							echo '
								<div class="row">
									<div class="col-lg-12">';
							$course_printed = FALSE;  // Set this flag to false because the top of the table needs to printed only once


							while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

								// Get the grades for the submitted homework assignments
								$q_hw_grade = "SELECT a.asmnt_id, a.asmnt_title, a.content, a.date_due, h.hw_id, h.s_id, h.comments, h.file_path, h.grade, h.date_posted
													FROM assignments AS a
													INNER JOIN homeworks AS h USING(asmnt_id)
													WHERE a.course_id=$id AND h.s_id=" . $row['user_id'] . "
													ORDER BY a.asmnt_id";
								$r_hw_grade = @mysqli_query($dbc, $q_hw_grade);

								// Get a count of the homeworks submitted filtered by student and course
								$q_hw_count = "SELECT COUNT(*)
														FROM assignments AS a
														INNER JOIN homeworks AS h USING(asmnt_id)
														WHERE a.course_id=$id AND h.s_id=" . $row['user_id'] . "";
								$r_hw_count = @mysqli_query($dbc, $q_hw_count);
								$row_hw = @mysqli_fetch_array($r_hw_count, MYSQLI_NUM);
								$hw_count = $row_hw[0];

								if (!$course_printed) {

									echo '<h3>' . $row['my_course'] . '&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;' . $row['semester'] . '</h3><br>';
									echo '<div class="table-responsive">
									<table class="table table-striped">
									<thead><tr><th>Student Name</th>';
									$index=0;
									while($index < $asn_count){
										echo '<th><small>ASGN ' . ($index + 1) . '</small></th>';
										$index++;
									}
									echo '<th>AVG</th></tr></thead>
									<tbody>';
									$course_printed = TRUE;
								}

								// For calculating grade average
								$grade_sum = 0;
								$hw_graded_count = 0;

								// Display the students and grades for all homework assignments
								echo '<tr>
									<td>' . $row['student_name'] . '</td>';
									$index = 0;
									while ($index < $asn_count) {
										while ($row_grades = mysqli_fetch_array($r_hw_grade, MYSQLI_ASSOC)) {

											// When a homework is submitted late, calculate how many days overdue it is:
											$post_date = new DateTime($row_grades['date_posted']);
											$due_date = new DateTime($row_grades['date_due']);
											$overdue = "";
											if ($due_date < $post_date) {
												$diff = $post_date->diff($due_date);
												$overdue = $diff->format('%m Month(s), %d Day(s), %h Hour(s), %i Min(s)') . ' Late<br>';
											}

											if ($row_grades['grade'] == NULL) {
												// Homework has been submitted but has not been graded yet
												echo '<td><small>' . $row_grades['asmnt_title']. '<br>' . $overdue . ' <a href="view_grades.php?uid=' . $row['user_id'] . '&sname=' . $row['student_name'] . '&cid=' . $id . '&cname=' . $row['my_course'] . '&hid=' . $row_grades['hw_id'] .'">Not Graded</a></small></td>';
												$index++;
											} else {
												// Homework has been submitted and graded
												echo '<td><small>' . $row_grades['asmnt_title']. '<br>' . $overdue . ' <a href="view_grades.php?uid=' . $row['user_id'] . '&sname=' . $row['student_name'] . '&cid=' . $id . '&cname=' . $row['my_course'] . '&hid=' . $row_grades['hw_id'] .'">' . $row_grades['grade'] . '/10</small></a></td>';
												$hw_graded_count++;
												$grade_sum += $row_grades['grade'];
												$index++;
											}

										}  // END OF: WHILE ($row_grades = mysqli_fetch_array())

										if ($index <> $asn_count) {
											// No homework was submitted for this assignments
											echo '<td><small><i>Not Submitted</i><small></td>';
										}
										$index++;
									}  // END OF: WHILE ($index < $asn_count)

								if ($hw_graded_count == 0) {
									$hw_average = 0;
								} else {
									$hw_average = ROUND(($grade_sum / $hw_graded_count), 0);
								}

								echo '<td>' . $hw_average . '/100</td>';
							} // END OF MAIN WHILE

							echo '
										</tbody>
									</table>
								</div>
							</div>';
						} // END OF   IF (!($asn_count > 0)) 		// No assignments
					} // END OF:    IF ( !(mysqli_num_rows($r)>0) )

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
				}

			} else {  // User is not an instructor

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
			mysqli_close($dbc);
			?>
	</article>

<?php include('footer.php'); ?>
