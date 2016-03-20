<?php
// *** Main page for the site  ***

// Include the configuration and header files
require('config.inc.php');
include('header.php');
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Homeboard</h1>
            <?php
            // Welcome the user (welcome them by name if they're logged in)
            echo '<p><h4>Welcome';
            if (isset($_SESSION['first_name'])) {
                echo ", {$_SESSION['first_name']}";
            }
            echo '!</h4></p>';
            ?>
            <div class="row placeholders">
                <div class="col-xs-6 col-sm-3 placeholder">
                    <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/512/advertising.png" class="img-responsive" alt="Anouncement Image">
                    <h4>Announcements</h4>
                </div>
                <div class="col-xs-6 col-sm-3 placeholder">
                    <a href="view_courses.php"><img src="http://104.236.10.194/wp-content/uploads/2015/08/Test-1.jpg1.png" class="img-responsive" alt="Courses Image"></a>
                    <h4><a href="view_courses.php">Courses</a></h4>
                </div>
                <div class="col-xs-6 col-sm-3 placeholder">
                    <img src="http://www.devopstesting.com/wp-content/uploads/2014/08/Software-Testing-Services.png" class="img-responsive" alt="Online Exam Image">
                    <h4>Online Exams</h4>
                </div>
                <div class="col-xs-6 col-sm-3 placeholder">
                    <a href="view_grades.php"><img src="http://manch.co.in/site_media/img/homepage/test-prep-icon.png" class="img-responsive" alt="Grades Image"></a>
                    <h4><a href="view_grades.php">Grades</a></h4>
                </div>
            </div>
        </div>
<?php include('footer.php'); ?>
