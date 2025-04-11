<?php
require 'config.php';
require 'auth_check.php';

// Ensure only admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if user_id is passed via GET
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];

    // Fetch the user's scanned information
    $sql = "SELECT user_id, username, role, location FROM survey WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "No user found.";
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo "User ID not specified.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Scanned Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Scanned Information for <?php echo htmlspecialchars($user['username']); ?></h2>
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($user['user_id']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($user['location']); ?></p>
                <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
