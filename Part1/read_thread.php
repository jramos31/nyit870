<?php
// *** This script displays all the messages in a single thread.
include ('header.php');
require_once('mysqli_connect.php');
require('pagination_links.php');
?>

		<!--  ****** Start of Page Content ******  -->
    <section id="content">
        <article>
            <h1 class="page-header">D&J Blog</h1><br />
            <?php
            // We must check for a valid thread ID
            $tid = FALSE;
            if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {

            	$tid = $_GET['id'];

            	// ****   Setup Pagination   ****

            	$display = 5;  // Limit the number of records shown on each page

            	// Number of pages
            	if (isset($_GET['p']) && is_numeric($_GET['p'])) {  // Determined

            		$pages = $_GET['p'];

            	} else {		// Need to determine

            		// Get a count of the number of records

            		$q = "SELECT COUNT(*)
            				FROM posts
            				WHERE thread_id=$tid";
            		$r = @mysqli_query($dbc, $q);
            		$row = @mysqli_fetch_array($r, MYSQLI_NUM);
            		$records = $row[0];

            		// Calculate number of pages
            		if ($records > $display) {  // More than 1 page
            			$pages = ceil($records/$display);
            		} else {
            			$pages = 1;
            		}
            	}

            	// Determine starting point of the results
            	if (isset($_GET['s']) && is_numeric($_GET['s'])) {
            		$start = $_GET['s'];
            	} else {
            		$start = 0;
            	}
            	// **** End - Setup Pagination  ****************

            	// The function is defined in pagination_links.php
            	show_page_links($pages, $display, $start, $tid, "read_thread");




            	// Query for retrieving all thread messages
            	$q = "SELECT t.subject, p.message, email, DATE_FORMAT(p.posted_on, '%e-%b-%y %l:%i %p') AS posted
            	         FROM threads AS t LEFT JOIN posts AS p USING (thread_id)
            			 INNER JOIN users AS u ON p.user_id = u.user_id
            			 WHERE t.thread_id = $tid ORDER BY p.posted_on ASC
            			 LIMIT $start, $display";
            	$r = mysqli_query($dbc, $q);
            	if (!(mysqli_num_rows($r) > 0)) {
            		$tid = FALSE;  //  Invalid thread ID
            	} elseif ($tid) {



            		$topic_printed = FALSE;  // Flag variable, because we only need to display the thread topic once

            		// Fetch the messages:
            		while ($messages = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            			if(!$topic_printed) {

            				// Print the thread topic:
            				echo "<div class='thread-border'><h2>Subject: <u>{$messages['subject']}</u></h2></div>\n";
            				$topic_printed = TRUE;
            			}

            			// Print each message:
            			echo "<div class='thread-border'><p>{$messages['email']} ({$messages['posted']})<br>{$messages['message']}</p><br></div>\n";
            		}


            		// Display form for posting a message
            		include('post_form.php');

            	} else {
            		echo '
                        <div class="row">
            			    <div class="col-lg-12">
    				            <div class="alert alert-warning">
                                    <p align="center">This page was accessed in error.</p>
                                </div>
            			    </div>
		                </div>';
            	}
            } // END OF:  if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1)) )
            ?>
        </article>

<?php include('footer.php'); ?>
