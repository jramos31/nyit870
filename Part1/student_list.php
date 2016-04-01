<?php
// *** This script lists students registered in the selected course  ***
//     - This page because must accessed through the view_courses.php page.
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
require('pagination_links.php');
?>

	<!--  ****** Start of Page Content ******  -->
	<div class="col-sm-3 col-sm-offset-3 col-md-6 col-md-offset-2 main">
		<h1 class="page-header">Student Listing</h1><br />	

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

				// Build the retrieval query
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

					echo '<div class="row">
						<div class="col-lg-12">';					
					
								
					$course_printed = FALSE;  // Set this flag to false because the top of the table needs to printed only once
					
					
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

						if (!$course_printed) {
													
							echo '<h3>' . $row['my_course'] . '&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;' . $row['semester'] . '</h3><br>';  
							echo '<div class="table-responsive">
								<table class="table table-striped">
								<thead><tr><th></th><th></th></tr></thead>
								<tbody>';
							$course_printed = TRUE;
						}

						// Display the students
						echo '<tr>
							<td>' . $row['student_name'] . '</td>
							<td><a href="view_grades.php?uid=' . $row['user_id'] . '&sname=' . $row['student_name'] . '&cid=' . $id . '&cname=' . $row['my_course'] . '">View Grades</a></td>
						</tr>';					
						
					} // END WHILE
					echo '</tbody>
					</table></div></div>';
				} // END OF:    if ( !(mysqli_num_rows($r)>0) )
				

			}  else {  // No valid course_id, kill the script
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
						</div>
					</div>';
					include('footer.php');
					exit();
			} 
			
		} else {  // User is not an instructor
			
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
						</div>
					</div>';
					include('footer.php');
					exit();
		}// End of main IF
		mysqli_close($dbc);
		
		?>

		</div> <!-- End of main page content  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">  -->
