<?php
// *** This script retrieves all the records from the users table  ***
//     - Only users logged in as administrators will be able
//     - to access this page.

// Include the config and header files and the connection string
require('config.inc.php');
include('header.php');

require_once('mysqli_connect.php');
?>


		<!--  ****** Start of Page Content ******  -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Current Users</h1><br />
		  
		  <?php 
		  
			// Build the retrieval query
			$q = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d %Y') AS dr, user_id FROM users ORDER BY registration_date ASC";
			$r = @mysqli_query($dbc, $q);

			// Number of rows returned by the query 
			$num = mysqli_num_rows($r);

			if ($num > 0) {  // Rows were returned 
	
				// Display the number of registered users
				echo '<h3 class="sub-header"><p>There are currently ' . $num . ' Registered Users</h3>
					<div class="table-responsive">
						<table class="table table-striped">';
				
							// Table Header
							echo '<thead>
								<tr>
									<th>Edit</th>
									<th>Delete</th>
									<th>Last Name</th>
									<th>First Name</th>
									<th>Date Registered</th>
								</tr>
							</thead>
							<tbody>';
							
							// Display the records in the table body
							while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
								echo '<tr>
									<td><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
									<td><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
									<td>' . $row['last_name'] . '</td>
									<td>' . $row['first_name'] . '</td>
									<td>' . $row['dr'] . '</td>
								</tr>';
							}
						
						// Close the table and free the memory
							echo '</tbody>
						</table>';
						mysqli_free_result($r);
						
			} else { // No rows were returned 
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning"><p align="center">There are no registered users.</p></div>
					</div>
				</div>';
			}
			
			mysqli_close($dbc);
		  ?>  
              
        </div>
		<!--  ****** End of Page Content ******  -->
		
<?php include('footer.php'); ?>