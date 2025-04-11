<?php
require 'config.php';
// require 'auth_check.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureGuard - Home</title>

    <style>
        /* Reset and global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f9f9f9;
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

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('security.jpg') center/cover no-repeat;
            height: 80vh;
            display: flex;
            align-items: center;
            color: #fff;
        }

        .hero-content {
            max-width: 600px;
        }

        .hero h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .btn-primary {
            display: inline-block;
            background-color: #00c9a7;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #009e84;
        }

        /* Services Section */
        .services {
            background-color: #fff;
            padding: 4rem 0;
            text-align: center;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .service-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h4 {
            margin-bottom: 1rem;
            color: #00c9a7;
        }

        /* Footer */
        .footer {
            background-color: #222;
            color: #fff;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h2 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .service-cards {
                flex-direction: column;
                align-items: center;
            }
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
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h2>Your Security, Our Priority</h2>
            <p>Ensuring real time and updated tracking of seurity officers.Maximises efficency</p>
            <a href="register.php" class="btn-primary">Get Started</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <h3 class="section-title">Our Solutions</h3>
            <div class="service-cards">
                <div class="card">
                    <h4>Access Control</h4>
                    <p>Manage who enters your premises with smart access systems.</p>
                </div>
                <div class="card">
                    <h4>24/7 Monitoring</h4>
                    <p>Real-time surveillance and alerts from our centralized center.</p>
                </div>
                <div class="card">
                    <h4>Smart Alerts</h4>
                    <p>Receive instant alerts on unauthorized access or suspicious activity.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <p>&copy; <?php echo date("Y"); ?> SecureGuard. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
