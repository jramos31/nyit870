<?php
// This script does the form handling when post_form.php posts messages.
// It also displays the form when the user creates a new message thread.
require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
?>

		<!--  ****** Start of Page Content ******  -->
        <section id="content">
   			<article>
        	<h1 class="page-header">D&J Blog</h1><br />

		<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {   // Form Handling

			// Validate thread ID
			if (isset($_POST['tid']) && filter_var($_POST['tid'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
				$tid = $_POST['tid'];
			} else {
				$tid = FALSE;
			}

			// If no thread ID, validate the subject
			if (!$tid && empty($_POST['subject'])) {
				$subject = FALSE;
				echo '<p>Please enter a subject or topic for this post.</p>';
			} elseif (!$tid && !empty($_POST['subject'])) {
				$subject = htmlspecialchars(strip_tags($_POST['subject']));
			} else {  // Thread ID is valid, we don't need subject
				$subject = TRUE;
			}

			// Validate body
			if (!empty($_POST['body'])) {
				$body = htmlentities($_POST['body']);
			} else {
				$body = FALSE;
				echo '<p>Please enter a message to this post.</p>';
			}

			if ($subject && $body) {

				// Adding messages to database

				if (!$tid) {  // No thread ID, so create a new message thread
					$q = "INSERT INTO threads (user_id, subject) VALUES ({$_SESSION['user_id']}, '" . mysqli_real_escape_string($dbc, $subject) ."')";
					$r = mysqli_query($dbc, $q);
					if (mysqli_affected_rows($dbc) == 1) {
						$tid = mysqli_insert_id($dbc);
					} else {
						echo '<p>Your message post could not be processed due to system error.</p>';
					}
				}

				if ($tid) {  // Valid thread, create a new post
					$q = "INSERT INTO posts (thread_id, user_id, message, posted_on) VALUES ($tid, {$_SESSION['user_id']}, '" . mysqli_real_escape_string($dbc, $body) . "', NOW())";
					$r = mysqli_query($dbc, $q);
					if (mysqli_affected_rows($dbc) == 1) {
						echo '<p>Message post has been entered.</p>';
					} else {
						echo '<p>Your message post could not be processed due to system error.</p>';
					}
				}
			} else {
				include('post_form.php');
			}
		} else {
			include('post_form.php');
		}
		?>
			</article>
<?php
include('footer.php');
?>
