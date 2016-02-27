<?php
// *** This script list anouncements posted by course's instructor ***
//     - The page will only list the announcements pertaining to a specific course
//     - to access this page because it must accessed through
//       the view_courses.php page.

require('config.inc.php');
include('header.php');

require_once('mysqli_connect.php');
?>

		<!--  ****** Start of Page Content ******  -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<h1 class="page-header">Class Announcements</h1><br />
			
		<?php 
		// Check for a valid course_id
		if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {  		// received from view_courses.php 
			
			$id = $_GET['id'];
			
			
			// Build the query
			$q = "SELECT a.subject, a.content, a.date_posted, c.course_num, c.course_title, c.section_num 
			      FROM announcements AS a INNER JOIN courses AS c ON a.course_id=c.course_id 
				  WHERE c.course_id=$id 
				  ORDER BY a.date_posted ASC";
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
					echo "<p>Date: &nbsp; &nbsp; &nbsp; {$messages['date_posted']}<br>
					         Subject: &nbsp; {$messages['subject']}<br>
							 Message: &nbsp; {$messages['content']}<br><br></p>";  
					
				}
				echo '</div></div>';
				
			}
			
			// Professor will also be able to post new course announcements 
			if ($_SESSION['user_level'] == '1') {
				echo '<div class="row">
				<div class="col-md-6 col-md-offset-2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Post New Announcement</h3>
						</div>
						<div class="panel-body">
							<form role="form" action="announcement_post.php" method="post">
								<div class="form-group">
									<label>Subject:</label>
									<input class="form-control" placeholder="Enter a subject" 
										name="subject" size="60" maxlength="100" type="text" value="">									
									<br>
									<label>Announcement:</label>
									<textarea class="form-control" name="body" row="3"></textarea>
									
									<input name="course_id" type="hidden" value="' . $id .'">
								</div>
								
								<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-success btn-block">
							</form>
						</div>
					</div>
				</div></div>';
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
		
