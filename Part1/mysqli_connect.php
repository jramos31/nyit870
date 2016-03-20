<?php
/*
 * This file establishes a connection the site's MySQL database.
 * It will also handle database connection errors
 */
// Define constant values for database access info
DEFINE('DB_USER', 'root');
DEFINE('DB_PASSWORD', 'root');
DEFINE('DB_HOST', 'localhost:3306'); // Port 3306 is the default port number MySQL/MariaDB listens to in XAMPP.
DEFINE('DB_NAME', 'DPJR_DB');

// Make the database connection
$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Trigger an error if a database connection couldn't be made
if (!$dbc) {
	trigger_error('Could not connect to MySQL: ' . mysqli_connect_error() );
} else {
	mysqli_set_charset($dbc, 'utf8'); // Connection was successful, so set the encoding
}
