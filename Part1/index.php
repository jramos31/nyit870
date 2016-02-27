<?php
// *** Main page for the site  ***

// Include the configuration and header files 
require('config.inc.php');
include('header.php');

?>

		<!--  ****** Start of Page Content ******  -->
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
			
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>Announcements</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>Courses</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>Online Exams</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4><a href="view_grades.php">Grades</a></h4>
              <span class="text-muted">Something else</span>
            </div>
          </div>          
        </div>
		<!--  ****** End of Page Content ******  -->
		
<?php include('footer.php'); ?>