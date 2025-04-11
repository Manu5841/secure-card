<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Decode JSON request
$data = json_decode(file_get_contents('php://input'), true);

if ($data === null) {
    echo json_encode(["error" => "Invalid JSON data"]);
    exit();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

// Validate required data
if (isset($data['qr_location'])) {
    $qr_location = trim($data['qr_location']);
    
    // Get logged-in user's info
    $submitted_by_id = $_SESSION['user_id'];
    $submitted_by_username = $_SESSION['username'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO QRDATA (location, submitted_by_id, submitted_by_username) VALUES (?, ?, ?)");

    if ($stmt === false) {
        echo json_encode(["error" => "Database statement preparation failed: " . $conn->error]);
        exit();
    }

    // Bind parameters
    $stmt->bind_param("sis", $qr_location, $submitted_by_id, $submitted_by_username);

    // Execute query
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing location data"]);
}

$conn->close();
?>