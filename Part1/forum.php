<?php
// *** This script list all threads in a forum.

require('config.inc.php');
include('header.php');
require_once('mysqli_connect.php');
require('pagination_links.php');
?>

		<!--  ****** Start of Page Content ******  -->
    <section id="content">
        <article id="login">
        	<h1 class="page-header">D&J Blog</h1><br />

            <?php
            		if (isset($_SESSION['user_id'])) {  // User is logged in

            				// ****   Setup Pagination   ****
            				$display = 5;  // Limit the number of records shown on each page

            				// Number of pages
            				if (isset($_GET['p']) && is_numeric($_GET['p'])) {  // Determined

            					$pages = $_GET['p'];

            				} else {		// Need to determine

            					// Get a count of the number of records

            					$q = "SELECT COUNT(*)
            							FROM threads";
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
            				show_page_links($pages, $display, $start, $id, "forum");



            				// Create the query for retrieving all message threads.
            				/* 	This information includes:
            				 *		- the user who posted the original message thread
            				 *		- the date the thread was first posted
            				 *		- the date of last reply to the thread
            				 *		- the number of replies in the thread
            				 */
            				$q = "SELECT t.thread_id, t.subject, email, COUNT(post_id) - 1 AS responses,
            				MAX(DATE_FORMAT(p.posted_on, '%e-%b-%y %l:%i %p')) AS last_post, MIN(DATE_FORMAT(p.posted_on, '%e-%b-%y %l:%i %p')) AS first_post
            				FROM threads AS t INNER JOIN posts AS p USING (thread_id) INNER JOIN users AS u ON t.user_id = u.user_id
            				GROUP BY (p.thread_id) ORDER BY last_post DESC
            				LIMIT $start, $display";
            				$r = mysqli_query($dbc, $q);
            				if (mysqli_num_rows($r) > 0) {

            					// Create a table to display the threads
            					echo '
                                    <table class="table table-striped">
                						<thead>
                                            <tr>
                							    <th>Topic</th><th>Posted By</th><th>Date First Posted</th><th>Replies</th><th>Last Reply</th>
                						    </tr></thead><tbody>';

            					// Fetch each thread
            					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            						echo '
                                        <tr>
        									<td><a href="read_thread.php?id=' . $row['thread_id'] . '">' . $row['subject'] . '</td>
        									<td>' . $row['email'] . '</td>
        									<td>' . $row['first_post'] . '</td>
        									<td>' . $row['responses'] . '</td>
        									<td>' . $row['last_post'] . '</td>
            							</tr>' ;
            					}
            					echo '
                                        </tbody>
                                    </table>';
            					mysqli_free_result($r);

            				} else {  // No messages
            						echo '
                                        <div class="row">
                							<div class="col-lg-12">
                								<div class="alert alert-warning"><p align="center">There are currently no messages in this forum.</p></div>
                							</div>
                						</div>';
				                    }

            		} else {  // User is not logged
            				echo '
                                <div class="row">
                					<div class="col-lg-12">
                						<div class="alert alert-warning"><p align="center">This page was accessed in error.</p></div>
                					</div>
                				</div>';
    		              }
            ?>
        </article>
<?php
include('footer.php');
?>
