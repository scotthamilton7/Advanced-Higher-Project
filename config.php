<?php
// Database configuration - Update these settings for your local server environment (MAMP/WAMP)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // MAMP users may need to change this to 'root'
define('DB_NAME', 'project');

// Establish the database connection
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (mysqli_connect_errno()) {
    die("<h2>Database Connection Error</h2>" . mysqli_connect_error());
}
?>