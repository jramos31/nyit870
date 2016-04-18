<?php
// *** This script returns a record from the homeworks table matching the hw_id passed to it from view_grades.php ***
//     - Only users logged in as instructors/faculty will be able to access this page. 
//     - This script also contains a form entering or changing student grades.

// Include the config and header files and the connection string
require('config.inc.php');
include('header.php');

?>

		<!--  ****** Start of Page Content ******  -->
    <div class="col-sm-2 col-sm-offset-3 col-md-4 col-md-offset-2 main">
        <h1 class="page-header">Enter/Edit Grades</h1><br />
		
		<?php
		// Check for valid hw_id received from view_grades.php
		if ( (isset($_GET['hw'])) && (is_numeric($_GET['hw'])) ) {  		// IF received from view_grades.php

			$h_id = $_GET['hw'];		

			$uid = $_GET['uid'];
			$sname = $_GET['sname'];
			$cid = $_GET['cid'];
			$cname = $_GET['cname'];				
			
		}  elseif ( (isset($_POST['hw'])) && (is_numeric($_POST['hw'])) ) { // IF received from form submission  (hidden input value)

			$h_id = $_POST['hw'];
			
			// These values are necessary for redirecting back to view_grades.php after entering a grade
			$uid = $_POST['uid'];
			$sname = $_POST['sname'];
			$cid = $_POST['cid'];
			$cname = $_POST['cname'];	
			
		} else {  // No valid user_id, kill the script
		
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
				</div>
			</div>';			
			exit();
		}
		
		require_once('mysqli_connect.php');
		
		// Check for form submission.
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			// Array for storing relevant error messages
			$error_msgs = array();
			
			// Make sure the professor enters a valid numerical grade 
			if (empty($_POST['hw_grade'])) {
				$error_msgs[] = '<p align="center">You must enter a grade.</p>';				
			} else {				
				if (!is_numeric($_POST['hw_grade'])) {
					$error_msgs[] = '<p align="center">You must enter a number grade.</p>';
				} else {
					if ( ($_POST['hw_grade'] < 0) OR ($_POST['hw_grade'] > 100) ) {
						$error_msgs[] = '<p align="center">The grade must be between 0 and 100.</p>';
					} else {
						$hw_grade = mysqli_real_escape_string($dbc, trim($_POST['hw_grade']));
					}
				}					
			}
			
			// If no errors
			if (empty($error_msgs))  {// No errors
				
				// get the hw_id value for the records the grade will be placed in
				$q = "SELECT hw_id FROM homeworks WHERE hw_id = $h_id";
				$r = @mysqli_query($dbc, $q);
				
				if (mysqli_num_rows($r) == 0) { // No results
					
					echo '<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-warning">
									<p align="center">The grade information could not be processed due to a system error. We apologize for any inconvenience.</p>
									<p>' . mysqli_error($dbc) . '<br>Query: ' . $q . '</p>     	
								</div>
							</div>
						</div>';
				} else {
					$q = "UPDATE homeworks SET grade='$hw_grade'  WHERE hw_id=$h_id LIMIT 1";
					$r = @mysqli_query ($dbc, $q);
										
					mysqli_close($dbc);
					//$url = BASE_URL . 'view_grades.php?uid=' . $uid . '&sname=' . $sname . '&cid=' . $cid . '&cname=' . $cname . '&hid=' . $h_id;
					$url = BASE_URL . 'student_list.php?id=' . $cid;
					ob_end_clean();   //  Delete unsent buffer data
					header("Location: $url");
					exit();									
				}
			}	else {
				// Display the errors
				echo '<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-warning">
							<p align="center">The following error(s) were found: </p>';
							foreach ($error_msgs as $msg) {
								echo " $msg";
							}
				echo '</div></div></div>';
			}
			
		} // END OF: if ($_SERVER['REQUEST_METHOD'] == 'POST') 
			
		// Display any previous grade in form before making changes
		$q = "SELECT comments, file_path, grade FROM homeworks WHERE hw_id=$h_id";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_num_rows($r) == 1) {  // Valid user id
			
			$row = mysqli_fetch_array($r, MYSQLI_NUM);
					
			//  Display the form			
			echo '<div class="panel panel-default">						
					<div class="panel-heading"><h3 class="panel-title">Student Grade</h3>
                    </div>
					<div class="panel-body">
						<form role="form" action="grade_entry.php" method="post">
							<div class="form-group">
								<input class="form-control" type="text"
									name="hw_grade" size="5" maxlength="5" value="'. $row[2] . '">																
							</div>
							<input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Submit" >
							<input type="hidden" name="hw" value="' . $h_id . '" >
							<input type="hidden" name="uid" value="' . $uid . '" >
							<input type="hidden" name="sname" value="' . $sname . '" >
							<input type="hidden" name="cid" value="' . $cid . '" >
							<input type="hidden" name="cname" value="' . $cname . '" >
						</form>
					</div>
				</div>';

		} else {
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-warning"><p align="center">This page has been accessed in error.</p></div>
				</div>
			</div>';
		}
		mysqli_close($dbc);
		?>
		</div> <!--  ****** End of Page Content ******  -->	
		
		
<?php 	
//include('footer.php'); 
?>