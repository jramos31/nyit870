<?php

// This script displays the form used for posting messages.
// It can be accessed when it's included in read_thread.php.



// Form can only be displayed if user is logged in.
if (isset($_SESSION['user_id'])) {
	
	echo '<form action="post.php" method="post" accept-charset="utf-8">';
	
	// Replying
	if (isset($tid) && $tid) {
		
		echo '<h3>Post a reply</h3>
		         <input name="tid" type="hidden" value="' . $tid . '">';
				 
	} else { // New thread, show topic/subject field
	
		echo '<h3>Post a new thread</h3>
				<p>Topic: <input name="subject" type="text" size="60" maxlength="100" ';
				
		if (isset($subject)) {
			echo "value=\"$subject\" ";
		}
		echo '></p>';
		
	}
	
	echo '<p>Message: <textarea name="body" rows="10" cols="60">';		
	if (isset($body)) {
		echo $body;
	}
	echo '</textarea></p>';
	
	echo '<input name="submit" type="submit" value="Submit">
			</form>';
} else {
	echo '<p>You must be logged in to post messages.</p>';
}

?>