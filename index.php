<?php
// Include the database connection file
require 'config.php';
require 'auth_check.php'; // Include the session check
require_once 'qrencode.php';


// Start the session to check login state
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Display the role of the logged-in user
$user_role = $_SESSION['role'];

// Welcome message based on user role
if ($user_role === 'admin') {
    $welcome_message = "Welcome, Admin!";
} else {
    $welcome_message = "Welcome, User!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Security System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $welcome_message; ?></h1>

        <p>Your connection to the database was successful!</p>

        <?php if ($user_role === 'admin'): ?>
            <p><a href="admin_dashboard.php">Go to Admin Dashboard</a></p>
        <?php else: ?>
            <p><a href="scan_qr.php">Go to QR Code Scan Page</a></p>
        <?php endif; ?>

        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
