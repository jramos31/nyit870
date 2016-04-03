<?php
// ****** Define some constant values ******

// Administrator's email address
define('EMAIL', 'dicksonporras@gmail.com');

// Site URL, the root domain for this development site
define('BASE_URL', 'http://localhost:8080/');

// Location of where students' homework files are uploaded to
define('HW_DIR', 'uploads_homeworks');											  // The Directory              -  Relative Path
define('HW_DOCS', getcwd() . DIRECTORY_SEPARATOR . HW_DIR);  // Path to the directory     -  Absolute Path

// Location of documents uploaded by course instructors
define('ANNOUNCE_DIR', 'uploads_announcements');														// The Directory  -  Relative Path
define('ANNOUNCE_DOCS', getcwd() . DIRECTORY_SEPARATOR . ANNOUNCE_DIR);     // Path to the directory  -  Absolute Path
define('ASSIGN_DIR', 'uploads_assignments');															   // The Directory   -  Relative Path
define('ASSIGN_DOCS', getcwd() . DIRECTORY_SEPARATOR . ASSIGN_DIR);              // Path to the directory   -  Absolute Path

// Location of MySQL connection script
define('MYSQL_CONN', 'mysqli_connect.php');

// Set the local timezone
date_default_timezone_set('US/Eastern');

// Site status flag
define('LIVE', FALSE);  // The site status setting determine how errors are handled

// ***************************************************



// ****** Error Handling ******
//  - When the site is still in development, this error handler will run a stack trace and
//    displays a detailed error message in the browser
//  - When the site goes LIVE, error messages will be NOT be sent to browser for users to see,
//    it will sent to the administrator's email address assigned to the EMAIL constant.

// Create the error handler:
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {

 // Build the error message:
 $message = "An error occurred in script '$e_file' on line $e_line: $e_message\n";

 // Add the date and time:
 $message .= "Date/Time: " . date('n-j-y H:i:s') . "\n";

 if (!LIVE) {  // Development (print the error).

	 // Show the error message:
	 echo '<div class="has-error">' . nl2br($message);

	 // Add the variables and a backtrace:
	 echo '<pre>' . print_r($e_vars, 1) . "\n";
	 debug_print_backtrace();
	 echo '</pre></div>';

 } else {  // Don't show the error:

	 // Send an email to the admin:
	 $body = $message . "\n" . print_r($e_vars, 1);
	 mail(EMAIL, 'Site Error!', $body, 'From: email@example.com');

	 // Only print an error message if the error isn't a notice:
	 if ($e_number != E_NOTICE) {
		 echo '<div class="has-error">A system error occurred. We apologize for the inconvenience.</div><br />';
	 }
 } // End of !LIVE IF.

}  // End of my_error_handler() definition.

// Use my error handler:
set_error_handler('my_error_handler');
// ***************************************************
?>
