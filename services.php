<?php
require 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Our Services - SecureGuard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Global Reset and Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: 'Inter', sans-serif;
            color: #333;
            background-color: #f4f6f9;
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
            color:white;
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

        /* Page Content */
        h1 {
            font-size: 32px;
            color: #1d3557;
            margin-bottom: 30px;
            text-align: center;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .service {
            background-color: #f1f9ff;
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.2s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .service:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .service h3 {
            font-size: 20px;
            color: #457b9d;
            margin-bottom: 10px;
        }

        .service p {
            font-size: 15px;
            line-height: 1.6;
        }

        .page-content {
            margin-top: 60px;
            margin-bottom: 40px;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
        }

        /* Footer */
        .footer {
            background-color: #222;
            color: #fff;
            text-align: center;
            padding: 1.5rem 0;
        }
    </style>
</head>
<body>

    <!-- Navigation Header -->
    <header class="navbar">
        <div class="container">
            <h1 class="logo">SecureGuard</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="home.php">Home</a></li>
                    
                    <li><a href="services.php" class="active">Services</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Services Content -->
    <div class="container page-content">
        <h1>What We Offer</h1>
        <div class="services-grid">
            <div class="service">
                <h3>QR Code Scanning</h3>
                <p>Scan QR codes directly using your device camera. Instantly retrieve location-based or embedded information in real-time.</p>
            </div>
            <div class="service">
                <h3>Image Upload Scanning</h3>
                <p>Upload an image containing a QR code and let our system decode and extract the data for you.</p>
            </div>
            <div class="service">
                <h3>Location Tracking</h3>
                <p>Every scan captures associated location data and links it to your profile for tracking and review.</p>
            </div>
            <div class="service">
                <h3>Secure User Authentication</h3>
                <p>Access is restricted to registered users, ensuring that all actions and submissions are securely tied to an identity.</p>
            </div>
            <div class="service">
                <h3>Data Submission & Reports</h3>
                <p>Scanned data can be submitted for validation, reporting, and analytics within the system.</p>
            </div>
            <div class="service">
                <h3>Responsive Across Devices</h3>
                <p>Our platform is fully responsive — optimized for mobile, tablet, and desktop experiences.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <p>&copy; <?php echo date("Y"); ?> SecureGuard. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
