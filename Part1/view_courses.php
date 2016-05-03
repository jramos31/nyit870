<?php
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');

// If there's no session variable for the user_id,
// then they haven't logged in yet.
// Redirect the user to the login page
if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'login.php'; // Homepage's URL

	ob_end_clean(); // Delete the unsent buffered data
	header("Location: $url");
	exit();  //  Quit the script

}
if ($_SESSION['user_level'] == '0') { // User is Student

	// get the user's info that was stored in sessions variables when they first logged in
	$usr_id = $_SESSION['user_id'];
	$fn = $_SESSION['first_name'];
	$ln = $_SESSION['last_name'];

	// Build the retrieval query and get the list of courses that this particular user is in
	$q = "SELECT c.course_num, c.course_title, c.section_num, c.campus, c.location, TIME_FORMAT(c.start_time, '%l:%i %p') AS starttime, TIME_FORMAT(c.end_time, '%l:%i %p') AS endtime, c.days, c.semester, c.course_id
          FROM courses AS c INNER JOIN students AS s ON c.course_id=s.course_id
          WHERE s.user_id=$usr_id";
	$r = @mysqli_query($dbc, $q);

	// Number of rows returned by the query
	$num = mysqli_num_rows($r);

	if ($num > 0) {  // Rows were returned

		echo '<!--  ****** Start of Page Content ******  -->
		<section id="content">
		   <article>
			<h3 class="sub-header"><p>' . $fn . ' ' . $ln. '\'s Courses</h3>
			<div class="table-responsive">
				<table class="table table-striped">';

					// Table Header
					echo '<thead>
						<tr>
							<th>Announcements</th>
							<th>Assignments</th>
							<th>Course</th>
							<th>Title</th>
							<th>Section</th>
							<th>Campus</th>
							<th>Location</th>
							<th>Start</th>
							<th>End</th>
							<th>Days</th>
							<th>Semester</th>
						</tr>
					</thead>
					<tbody>';

					// Display the records in the table body
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						echo '<tr>
							<td><a href="announcement_list.php?id=' . $row['course_id'] . '">Announcements</a></td>
							<td><a href="assignment_list.php?id=' . $row['course_id'] . '">Assignments</a></td>
							<td><small>' . $row['course_num'] . '</small></td>
							<td><small>' . $row['course_title'] . '</small></td>
							<td><small>' . $row['section_num'] . '</small></td>
							<td><small>' . $row['campus'] . '</small></td>
							<td><small>' . $row['location'] . '</small></td>
							<td><small>' . $row['starttime'] . '</small></td>
							<td><small>' . $row['endtime'] . '</small></td>
							<td><small>' . $row['days'] . '</small></td>
							<td><small>' . $row['semester'] . '</small></td>
						</tr>';
					}

				// Close the table and free the memory
					echo '</tbody>
				</table>
			</div>
		</article>';
		mysqli_free_result($r);

	} else {  // No rows returned

		echo '<!--  ****** Start of Page Content ******  -->
		<section id="content">
		   <article>
			<h1 class="page-header">My Courses</h1><br />

			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">You are not currently enrolled in any courses!</p></div>
				</div>
			</div>
		</article>';
	}

} elseif ($_SESSION['user_level'] == '1') { // User is Professor

	// get the user's info that was stored in sessions variables when they first logged in
	$usr_id = $_SESSION['user_id'];
	$ln = $_SESSION['last_name'];

	// Build the retrieval query
	$q = "SELECT course_num, course_title, section_num, campus, location, TIME_FORMAT(start_time, '%l:%i %p') AS starttime, TIME_FORMAT(end_time, '%l:%i %p') AS endtime, days, semester, course_id FROM courses WHERE prof_id=$usr_id";
	$r = @mysqli_query($dbc, $q);

	// Number of rows returned by the query
	$num = mysqli_num_rows($r);

	if ($num > 0) {  // Rows were returned

		echo 'echo <!--  ****** Start of Page Content ******  -->
		<section id="content">
		   <article>
			<h3 class="sub-header"><p>Professor ' . $ln. '\'s Courses</h3>
			<div class="table-responsive">
				<table class="table table-striped">';

					// Table Header
					echo '<thead>
						<tr>
							<th>Grades</th>
							<th>Announcements</th>
							<th>Assignments</th>
							<th>Course</th>
							<th>Title</th>
							<th>Section</th>
							<th>Campus</th>
							<th>Location</th>
							<th>Start</th>
							<th>End</th>
							<th>Days</th>
							<th>Semester</th>
						</tr>
					</thead>
					<tbody>';

					// Display the records in the table body
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						echo '<tr>
							<td><a href="student_list.php?id=' . $row['course_id'] . '">Grades</a></td>
							<td><a href="announcement_list.php?id=' . $row['course_id'] . '">Announcements</a></td>
							<td><a href="assignment_list.php?id=' . $row['course_id'] . '">Assignments</a></td>
							<td><small>' . $row['course_num'] . '</small></td>
							<td><small>' . $row['course_title'] . '</small></td>
							<td><small>' . $row['section_num'] . '</small></td>
							<td><small>' . $row['campus'] . '</small></td>
							<td><small>' . $row['location'] . '</small></td>
							<td><small>' . $row['starttime'] . '</small></td>
							<td><small>' . $row['endtime'] . '</small></td>
							<td><small>' . $row['days'] . '</small></td>
							<td><small>' . $row['semester'] . '</small></td>
						</tr>';
					}

				// Close the table and free the memory
					echo '</tbody>
				</table>
			</div>
		</article>';
		mysqli_free_result($r);

	} else { // No rows were returned
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning"><p align="center">You have never taught a course!</p></div>
					</div>
				</div>';
	}
	mysqli_close($dbc);
}

include('footer.php');
?>
