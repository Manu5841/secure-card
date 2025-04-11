<?php
require 'config.php';
session_start();

$message = ""; // To store messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM survey WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                $message = "Login successful! Redirecting to admin dashboard...";
                $redirect_url = "admin_dashboard.php";
            } else {
                $message = "Login successful! Redirecting to QR scanning page...";
                $redirect_url = "scan_qr.php";
            }
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "User not found!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Basic center styling */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            height: calc(100vh - 60px); /* subtract nav height */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background-color: #fff;
            padding: 30px 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
        }

        .card-body h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label, input {
            width: 100%;
            display: block;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        p {
            text-align: center;
            margin-top: 10px;
        }

        p a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <div class="navbar">
        <div><strong>SecureGuard</strong></div>
        <div>
            <a href="home.php">Home</a>
            <a href="register.php">Register</a>
        </div>
    </div>

    <!-- Login Form -->
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2>Login</h2>
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($message)) : ?>
        <script>
            Swal.fire({
                icon: '<?php echo strpos($message, "successful") !== false ? "success" : "error"; ?>',
                title: '<?php echo $message; ?>',
                timer: 2000,
                showConfirmButton: false,
                willClose: () => {
                    <?php if (isset($redirect_url)) : ?>
                        window.location.href = '<?php echo $redirect_url; ?>';
                    <?php endif; ?>
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
