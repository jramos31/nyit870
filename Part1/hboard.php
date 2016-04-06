<?php
// *** Main page for the site  ***
// Include the configuration and header files
require('config.inc.php');
include('header.php');
?>

        <section id="content" class="column-right">
           <article>
                <h2>Homeboard</h2>
                <?php
                // Welcome the user (welcome them by name if they're logged in)
                echo '<p><h3>Logged in as: ';
                if (isset($_SESSION['first_name'])) {
                    echo " {$_SESSION['first_name']}";
                }
                echo '</h3></p>';
                ?>
                <div class="row placeholders">
                    <div class="col-xs-6 col-sm-3 placeholder">
                        <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/512/advertising.png" class="img-responsive" alt="Anouncement Image">
                        <?php if ( isset($_SESSION['user_id'])  && ($_SESSION['user_level'] == '0') ){  // User must be a student that's logged in
	                           echo '<a href="announcement_list_all.php?id= '. $_SESSION['user_id'] . '">ANNOUNCEMENTS</a>';
                           } else {
	                           echo '<a href="announcement_list_all.php" class="button button-reversed">ANNOUNCEMENTS</a>';
                        }
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-3 placeholder">
                        <a href="view_courses.php"><img src="http://104.236.10.194/wp-content/uploads/2015/08/Test-1.jpg1.png" class="img-responsive" alt="Courses Image"></a>
                        <a href="view_courses.php" class="button button-reversed">COURSES</a>
                    </div>
                    <div class="col-xs-6 col-sm-3 placeholder">
                        <img src="http://www.gordiandynamics.com/wp-content/uploads/2015/11/webinar-icon-grey.png" class="img-responsive" alt="Online Exam Image">
                        <a href="#" class="button button-reversed">DISCUSSION</a>
                    </div>
                    <div class="col-xs-6 col-sm-3 placeholder">
                        <a href="view_grades.php"><img src="http://manch.co.in/site_media/img/homepage/test-prep-icon.png" class="img-responsive" alt="Grades Image"></a>
                        <a href="view_grades.php" class="button button-reversed">GRADES</a>
                    </div>
                </article>
<?php include('footer.php'); ?>
