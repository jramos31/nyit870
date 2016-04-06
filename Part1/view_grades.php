<?php
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');

// If there's no session variable for the user's email,
// then they haven't logged in yet.
// Redirect the user to the login page
if (!isset($_SESSION['user_id'])) {   // User not logged in

	$url = BASE_URL . 'login.php'; // Homepage's URL

	ob_end_clean(); // Delete the unsent buffered data
	header("Location: $url");
	exit();  //  Quit the script

} else {
	?>
				<!--  ****** Start of Page Content ******  -->
				<section id="content">
				   <article id="login">
					<h1 class="page-header">View Student Grades</h1><br />
					<?php
					if (isset($_SESSION['user_level'])) {
							if ($_SESSION['user_level'] == '0') {	// User is Student

								// Get the values user needs to view their grades
								$id = $_SESSION['user_id'];
								$sname = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];

								// Student must select which one of his/her courses they want to see grades for:
								//     Build the retrieval query and get the list of courses that this particular user is in
								$q = "SELECT c.course_id, CONCAT(c.course_num, ' ', c.course_title, ' - ', c.section_num) AS course_name
									  FROM courses AS c INNER JOIN students AS s ON c.course_id=s.course_id
									  WHERE s.user_id=$id";
								$r = @mysqli_query($dbc, $q);

								if (!(mysqli_num_rows($r)>0)) { // No results
										echo '<div class="row">
											<div class="col-lg-12">
												<div class="alert alert-warning"><p align="center">There are no courses listed for you.</p></div>
											</div>
										</div>';
										exit();
								} else {
									echo '<p>Select a course to see your grades:</p>
									<form action="view_grades.php" method="post">
										<select name="course">';
										$cnames = array();
										while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
											$cnames[$row['course_id']] = $row['course_name'];
											echo '<option value="' . $row['course_id'] . '">' . $row['course_name'] . '</option>';
										}
										echo '</select>
									<input type="submit" name="submit" value="GO">
									</form>';
								}

								// Check for form submission (the Drop down list)
								if ($_SERVER['REQUEST_METHOD'] == 'POST') {

									if (isset($_POST['course'])) {
										$cid = $_POST['course'];
										$cname = $cnames[$_POST['course']];
									}else {
										$_POST['course'] = '';
									}

									// Build the retrieval query for the grades
									$q = "SELECT a.asmnt_title, a.content, h.hw_id, h.s_id, h.comments, h.file_path, h.grade
											FROM assignments AS a
											INNER JOIN homeworks AS h USING(asmnt_id)
											WHERE a.course_id=$cid AND h.s_id=$id
											ORDER BY h.hw_id";
									$r = @mysqli_query($dbc, $q);
									if (!(mysqli_num_rows($r)>0)) { // No results
											echo '<div class="row">
												<div class="col-lg-12">
													<div class="alert alert-warning"><p align="center">You don\'t have grades listed for:<br>' . $cname . '.</p></div>
												</div>
											</div>';
									} else {
										echo '<div class="row">
											<div class="col-lg-8">';

										$tabletop_printed = FALSE;  // Set this flag to false because the top of the table needs to printed only once
										while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

											if (!$tabletop_printed) {

												echo '<h3>' . $cname . '</h3><br><p><b><u>Student Name: ' . $sname . '</u></b></p>';
												echo '<div class="table-responsive">
													<table class="table table-striped">
													<thead><tr><th><b>Homework Assignment</b></th><th><b>Grade</b></th></tr></thead>
													<tbody>';
												$tabletop_printed = TRUE;
											}

											// Display the grades
											echo '<tr>
												<td>' . $row['asmnt_title'] . '</td>';
											if ($row['grade'] == NULL) {
												echo '<td>NOT GRADED</td></tr>';
											} else {
												echo '<td>' . $row['grade'] . '/100</td></tr>';
											}
										} // END WHILE
										echo '</tbody>
										</table></div></div>';
									} // END OF:    if ( !(mysqli_num_rows($r)>0) )

								} // END OF: if ($_SERVER['REQUEST_METHOD'] == 'POST')
							} // END OF: if user is a student

							if ($_SESSION['user_level'] == '1') {	// User is Professor/Faculty
								// Receive valid student User ID, student name, course_id and course name from student_list.php
								if ( (isset($_GET['uid'])) && (is_numeric($_GET['uid'])) ) {
									$id = $_GET['uid'];
									$sname = $_GET['sname'];
									$cid = $_GET['cid'];
									$cname = $_GET['cname'];

									// Build the retrieval query
									$q = "SELECT a.asmnt_title, a.content, h.hw_id, h.s_id, h.comments, h.file_path, h.grade
											FROM assignments AS a
											INNER JOIN homeworks AS h USING(asmnt_id)
											WHERE a.course_id=$cid AND h.s_id=$id
											ORDER BY h.hw_id";
									$r = @mysqli_query($dbc, $q);

									if (!(mysqli_num_rows($r)>0)) { // No results
										echo '<div class="row">
											<div class="col-lg-12">
												<div class="alert alert-warning"><p align="center">There are no hw assignments listed for this student.</p></div>
											</div>
										</div>';
									} else {
										echo '<div class="row">
											<div class="col-lg-12">';

										$tabletop_printed = FALSE;  // Set this flag to false because the top of the table needs to printed only once
										while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

											if (!$tabletop_printed) {

												echo '<h3>' . $cname . '</h3><br>';
												echo '<div class="table-responsive">
													<table class="table table-striped">
													<thead><tr><th><b>' . $sname . '</b></th><th></th></tr></thead>
													<tbody>';
												$tabletop_printed = TRUE;
											}

											// Display the students
											echo '<tr>
												<td>' . $row['asmnt_title'] . '</td>
												<td><a href="' . $row['file_path'] . '">Download Document</a></td>';
											if ($row['grade'] == NULL) {
												echo '<td><a href="grade_entry.php?hw=' . $row['hw_id'] . '&uid=' . $id . '&sname=' . $sname . '&cid=' . $cid . '&cname=' . $cname . '">NOT GRADED</a></td></tr>';
											} else {
												echo '<td><a href="grade_entry.php?hw=' . $row['hw_id'] . '&uid=' . $id . '&sname=' . $sname . '&cid=' . $cid . '&cname=' . $cname . '">' . $row['grade'] . '/100</a></td></tr>';
											}
										} // END WHILE
										echo '</tbody>
										</table></div></div>';
									} // END OF:    if ( !(mysqli_num_rows($r)>0) )
								} else { // no values were received from student_list.php, redirect user to view_courses.php
									$url = BASE_URL . 'view_courses.php'; // Homepage's URL

									ob_end_clean(); // Delete the unsent buffered data
									header("Location: $url");
									exit();  //  Quit the script
								}// END OF: if isset() values from student_list.php
							} // END OF: if user is professor/faculty
							}
						}
						?>
					</article>
<?php include('footer.php'); ?>
