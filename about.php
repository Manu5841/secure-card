<?php
require 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>About</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Reset and global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
        }

        /* Navbar */
        .navbar {
            background-color: #222;
            color: #fff;
            padding: 1rem 0;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color:white
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links .active {
            color: #00c9a7;
        }

        /* Content Styles */
        .content {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 28px;
            color: #1d3557;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <header class="navbar">
        <div class="container">
            <h1 class="logo">SecureGuard</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="about.php" class="active">About</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Page Content -->
    <div class="container content">
        <h1>About This System</h1>
        <p>
            This QR Code Scanning System is designed to streamline the process of location tracking and verification using QR codes. Users can either scan a QR code directly via their device camera or upload an image of a QR code to extract the location data.
        </p>
        <p>
            The platform offers an intuitive interface and ensures secure data submission by integrating user authentication. Each location scanned is recorded along with the user's identity, allowing for efficient reporting and tracking.
        </p>
        <p>
            Whether you're managing site visits, verifying field activities, or just organizing locations, this system provides a reliable, modern solution for your needs.
        </p>
        <p>
            Built using PHP, JavaScript, and modern frontend design, this system prioritizes simplicity, functionality, and user experience.
        </p>
    </div>
</body>
</html>
