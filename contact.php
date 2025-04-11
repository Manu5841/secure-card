<?php
require 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SecureGuard - Contact Us</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 800px;
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
            display: flex;
            align-items:left;
    
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

        /* Contact Form */
        .contact-section {
            background-color: #fff;
            margin: 60px auto;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }

        h1 {
            font-size: 30px;
            color: #1d3557;
            margin-bottom: 25px;
            text-align: center;
        }

        p.description {
            text-align: center;
            margin-bottom: 40px;
            color: #555;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        input, textarea {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
        }

        button {
            padding: 12px;
            background-color: #1d3557;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        button:hover {
            background-color: #457b9d;
        }

        /* Footer */
        .footer {
            background-color: #222;
            color: #fff;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
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
                   
                    
                    <li><a href="contact.php" class="active">Contact</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Contact Form Section -->
    <div class="container contact-section">
        <h1>Contact Us</h1>
        <p class="description">Have questions or feedback? We'd love to hear from you.</p>

        <form action="send_message.php" method="POST">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name" />

            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email" />

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" required placeholder="Type your message here..."></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> SecureGuard. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
