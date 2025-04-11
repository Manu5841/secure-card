<?php
session_start();
require 'config.php';

// Only allow admins to access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => 'Username and password are required!'
        ];
        header("Location: admin_dashboard.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => 'Password must be at least 6 characters!'
        ];
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check if username exists
    $check_stmt = $conn->prepare("SELECT user_id FROM survey WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => 'Username already exists!'
        ];
        header("Location: admin_dashboard.php");
        exit();
    }

    // Hash password and insert user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("INSERT INTO survey (username, password, role) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("sss", $username, $hashed_password, $role);
    
    if ($insert_stmt->execute()) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'User ' . htmlspecialchars($username) . ' added successfully!'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => 'Failed to add user: ' . htmlspecialchars($conn->error)
        ];
    }
    
    $insert_stmt->close();
    $check_stmt->close();
    $conn->close();
    
    header("Location: admin_dashboard.php");
    exit();
}

// If not POST request, redirect
header("Location: admin_dashboard.php");
exit();
?>