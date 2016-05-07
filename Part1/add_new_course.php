<?php
// This script list allows administrative users to add a new course to the database 
// and simultaneously assign an instructor for that course. 
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
?>

		<!--  ****** Start of Page Content ******  -->
        <section id="content">
   			<article>
        		<h1 class="page-header">Add New Courses</h1>

        		<?php
        		 // Verify that the user is an administrator
                 if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == '2') {  // If user is ADMIN
                         // Declare variables for text field values
                         $course_num = '';
                         $course_title = '';
                         $section_num = '';
                         $location = '';
                         $start_time = '';
                         $end_time = '';
                         $class_days = '';
                         
                        // Check for form submission
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            
                            // Array for holding relevent error messages
                            $error_msgs = array();
                           
                            // Make sure no text fields were left blank:
                            if (empty($_POST['course_num'])) {                                
                                $error_msgs[] = '<p align="center">Please enter the Course Number.</p>';     
                            } else {
                                $course_num = $_POST['course_num'];
                            }
                            
                            if (empty($_POST['course_title'])) {
                                $error_msgs[] = '<p align="center">Please enter the Course Title.</p>';
                            } else {
                                $course_title = $_POST['course_title'];
                            }
                            
                            if (empty($_POST['section_num'])) {
                                $error_msgs[] = '<p align="center">Please enter the Section Number.</p>';
                            } else {
                                $section_num = $_POST['section_num'];
                            }
                            
                            if (empty($_POST['location'])) {
                                $error_msgs[] = '<p align="center">Please enter the Class Location.</p>';
                            } else {
                                $location = $_POST['location'];
                            }
                            
                            if (empty($_POST['start_time']) OR empty($_POST['end_time'])) {
                                $error_msgs[] = '<p align="center">You must enter both start and end times.</p>';
                            } else {
                                $start_time = $_POST['start_time'];
                                $start_time .= ' ';
                                $start_time .= $_POST['start_am_or_pm'];
                                
                                $end_time = $_POST['end_time'];
                                $end_time .= ' ';
                                $end_time .= $_POST['end_am_or_pm'];
                            }
                            
                            if (empty($_POST['days'])) {
                                $error_msgs[] = '<p align="center">You must select at least one day.</p>';
                            } else {
                                $my_counter = count($_POST['days']);
                                for ($i = 0; $i < $my_counter; $i++) {
                                  $class_days .= $_POST['days'][$i] . ' ';
                                }  
                                $class_days = trim($class_days);                          
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
                            
                            // All fields have been filled:
                            if ($course_num && $course_title && $section_num && $start_time && $end_time && $class_days) {  
                                // Store the values from the drop down lists
                                $id = $_POST['instructor']; // Assign the instructor for this course
                                $campus = $_POST['campus'];
                                $semester = $_POST['semester'];                             
                                
                                // Covert the start and end times into MySQL time datatype before inserting into the database:
                                $dt_start = DateTime::createFromFormat('H:i A', $start_time);
                                $start_time_formatted = $dt_start->format('H:i:s');
                                $dt_end = DateTime::createFromFormat('H:i A', $end_time);
                                $end_time_formatted = $dt_end->format('H:i:s');
                                
                                // Check to makes sure the course and section isn't already assigned
                                $q = "SELECT course_id FROM courses WHERE course_num = '$course_num' AND section_num = '$section_num' AND semester = '$semester'";
                                $r = @mysqli_query($dbc, $q);

                                if (mysqli_num_rows($r) == 0) { // No results                            
                                
                                    $q = "INSERT INTO courses (course_num, course_title, section_num, campus, location, start_time, end_time, days, semester, prof_id)
                                        VALUES ('" . mysqli_real_escape_string($dbc, $course_num) . "', '" . mysqli_real_escape_string($dbc, $course_title) . "', 
                                                '" . mysqli_real_escape_string($dbc, $section_num) . "', '" . mysqli_real_escape_string($dbc, $campus) . "',
                                                '" . mysqli_real_escape_string($dbc, $location) . "', '$start_time_formatted', '$end_time_formatted',  
                                                '" . mysqli_real_escape_string($dbc, $class_days) . "', '$semester', '$id')";
                                    
                                    $r = mysqli_query($dbc, $q);
                                        
                                    if (mysqli_affected_rows($dbc) == 1) {   
                                        echo '         
                                        <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-success">
                                                <p align="center">This operation was successful.</p>
                                            </div>
                                        </div>
                                    </div>';
                                    } else {
                                        echo '
                                        <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning">
                                                <p align="center">This record could not be entered due to system error.</p>
                                            </div>
                                        </div>
                                    </div>';
                                    }
                                                
                                } else {
                                    echo '
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning">
                                                <p align="center">This course has already been assigned. Select a different course or section or semester.</p>
                                            </div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo '
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="alert alert-warning">
                                                <p align="center">This operation could not be completed due to system error.</p>
                                            </div>
                                        </div>
                                    </div>';
                            } // End of: if ($course_num && $course_title && $section_num && $start_time && $end_time && $class_days)
                            
                        } // End of: if ($_SERVER['REQUEST_METHOD'] == 'POST')
                        
                        
                        //******   HTML Form   ****************
                        
                        // Build the query to fetch instructors for the drop down list
                        $q = "SELECT user_id, first_name, last_name FROM users
                              WHERE user_level=1";
                        $r = mysqli_query($dbc, $q);

                        if (!(mysqli_num_rows($r)>0)) { // No assignments
        					echo '<div class="row">
        						<div class="col-lg-12">
        							<div class="alert alert-warning"><p align="center">No instructors listed.</p></div>
        						</div>
        					</div>';
                            exit();
        				}  else {
                            
                            // *** Show Form ***
                            echo '<div class="row">
                                    <div class="col-md-6 col-md-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Add A New Course To the Database</h3>
                                            </div>
                                            <div class="panel-body">
                                                <form role="form" action="add_new_course.php" method="post">
                                                    <div class="form-group">                                                        
                                                        
                                                        <label>Campus:
                                                            <select name="campus">
                                                                <option value="M">Manhattan</option>
                                                                <option value="OW">Old Westbury</option>
                                                            </select>
                                                        </label>                                                        
                                                        <label>Semester:
                                                            <select name="semester">
                                                                <option value="Fall 2015">Fall 2015</option>
                                                                <option value="Spring 2016">Spring 2015</option>
                                                                <option value="Fall 2016">Fall 2016</option>
                                                            </select>
                                                        </label>
                                                        <br>
                                                        <Label>Assign an instructor for this course:
                                                        <select name="instructor">';
                                                        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                                            echo '<option value="' . $row['user_id'] . '">' . $row['first_name'] . " " . $row['last_name'] . '</option>';    
                                                        }
                                                        echo '</select>
                                                        </label>                                                        
                                                        <br>                                                        
                                                        <label>Course Number (i.e. CSCI XXX):</label>
                                                        <input class="form-control" placeholder="CSCI XXX"
                                                            name="course_num" type="text" value="">
                                                        <br>                                                        
                                                        <label>Course Title:</label>
                                                        <input class="form-control" placeholder=""
                                                            name="course_title" type="text" value="">
                                                        <br>                                                        
                                                        <label>Section Number (i.e. M01, M02, W01, etc):</label>
                                                        <input class="form-control" placeholder=""
                                                            name="section_num" type="text" value="">
                                                        <br>
                                                        <label>Class Location (Building and Room #):</label>
                                                        <input class="form-control" name="location" type="text" value="">
                                                        <br>
                                                        <label>Start Time:</label>
                                                        <input placeholder="12:00"
                                                            name="start_time" type="text" value="">
                                                        <input name="start_am_or_pm" type="radio" value="AM" checked>A.M.
                                                        <input name="start_am_or_pm" type="radio" value="PM">P.M.    
                                                        <br><br>
                                                        <label>End Time:</label>
                                                        <input placeholder="12:00"
                                                            name="end_time" type="text" value="">
                                                        <input name="end_am_or_pm" type="radio" value="AM" checked>A.M.
                                                        <input name="end_am_or_pm" type="radio" value="PM">P.M.
                                                        <br>
                                                        <label>Days:</label>
                                                        <input name="days[]" type="checkbox" value="M">Mon
                                                        <input name="days[]" type="checkbox" value="T">Tue
                                                        <input name="days[]" type="checkbox" value="W">Wed
                                                        <input name="days[]" type="checkbox" value="TH">Thu
                                                        <input name="days[]" type="checkbox" value="F">Fri
                                                        <input name="days[]" type="checkbox" value="SAT">Sat     
                                                    </div>
                                                    <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-success btn-block">
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