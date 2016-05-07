<?php
// This script list allows administrative users to assign a student user 
// to a course in the database. 
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
?>

		<!--  ****** Start of Page Content ******  -->
        <section id="content">
   			<article>
        		<h1 class="page-header">Add Student to Course</h1>

        		<?php
        		 // Verify that the user is an administrator
                 if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == '2') {  // If user is ADMIN
                         // Declare variables for text field values
                         
                         
                        // Check for form submission
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            
                            // Array for holding relevent error messages
                            $error_msgs = array();
                           
                            // Drop downlist values
                            $stu_usr_id = $_POST['student'];
                            $course_id = $_POST['course'];                            
                            
                            // Make sure the student isn't already assigned to that course
                            $q = "SELECT stud_id FROM students
                                  WHERE user_id = $stu_usr_id AND course_id = $course_id";
                            $r = @mysqli_query($dbc, $q);
                            
                            if (mysqli_num_rows($r) == 0) { // No results
                                //echo '<p>Put an Insert statement here!<p>';
                                $q = "INSERT INTO students (user_id, course_id)
                                      VALUES ($stu_usr_id, $course_id)";
                                $r = mysqli_query($dbc, $q);
                                if (mysqli_affected_rows($dbc) == 1) {   
                                    echo '         
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-success">
                                                <p align="center">Student Added!<br>
                                                    <a href="add_student_course.php">Click here to Return</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div></article></section>';
                                    include('footer.php');
                                    exit();
                                    
                                } else {
                                    $error_msgs[] = '<p align="center">This record could not be entered due to system error.</p>';
                                }                                
                                
                            } else {
                                $error_msgs[] = '<p align="center">The student is already assigned to this course.</p>';
                            }
                            
                            
                            if (!empty($error_msgs)) {
                                // Display the errors
                                echo '
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning">
                                                <p align="center">The following error(s) were found: </p>';
                                                foreach ($error_msgs as $msg) {
                                                    echo " $msg";
                                                }
                                echo '
                                            </div>
                                        </div>
                                    </div>';
                            }
                        } // End of: if ($_SERVER['REQUEST_METHOD'] == 'POST')
                        
                        
                        //******   HTML Form   ****************
                        
                        // Build the query to fetch the list of students for the drop down list
                        $q = "SELECT user_id, first_name, last_name FROM users
                              WHERE user_level=0";
                        $r = mysqli_query($dbc, $q);

                        if (!(mysqli_num_rows($r)>0)) { // No assignments
        					echo '<div class="row">
        						<div class="col-lg-12">
        							<div class="alert alert-warning"><p align="center">No students listed.</p></div>
        						</div>
        					</div>';
                            exit();
        				}  else {
                            
                            // *** Show Form ***
                            echo '<div class="row">
                                    <div class="col-md-6 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Add a Student to a Course</h3>
                                            </div>
                                            <div class="panel-body">
                                                <form role="form" action="add_student_course.php" method="post">
                                                <div class="form-group">
                                                    <label>Select a Student:</label>
                                                        <select name="student">';
                                                        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                                            echo '<option value="' . $row['user_id'] . '">' . $row['first_name'] . " " . $row['last_name'] . '</option>';                                                               
                                                        }
                                                    echo '</select>
                                                    
                                                </div>';
                                                    
                                                // Build the query to fetch the list of courses for the drop down list
                                                $q = "SELECT course_id, course_num, course_title, section_num, semester, first_name, last_name FROM courses 
                                                      INNER JOIN users ON prof_id=user_id";
                                                $r = mysqli_query($dbc, $q);    
                                                echo '                  
                                                <div class="form-group">                                          
                                                    
                                                    <Label>Select a Course:</label>
                                                    <select name="course">';
                                                    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                                        echo '<option value="' . $row['course_id'] . '">' . $row['semester'] . " - " . $row['course_num'] . " " . $row['course_title'] . " " . $row['section_num'] . '</option>';                                                           
                                                    }
                                                    echo '</select>
                                                           
                                                </div>                                                
                                                <input type="submit" name="submit" value="Add This Student to this Course" class="btn btn-lg btn-success btn-block">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';                            
                        } // End of: if (!(mysqli_num_rows($r)>0))                          
        				//****** END OF:   HTML Form   ****************

        		} else {  // Not an ADMIN
                        echo '<div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
                            </div>
                        </div>';
                        include('footer.php');
                        exit();
        		} // END OF: if ($_SESSION['user_level'] is a ADMIN)
        		?>
        </article>

<?php
include('footer.php');
?>