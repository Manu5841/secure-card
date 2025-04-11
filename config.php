<?php
// config.php - This file establishes the database connection
$servername = "localhost"; // Change if needed
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "studenp_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
