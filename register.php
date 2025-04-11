<?php
require 'config.php';
// require 'auth_check.php'; // Include the session check
session_start();

$message = ""; // To store the message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $location = $_POST['location'];

    $sql = "INSERT INTO survey (username, password, role, location) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $password, $role, $location);

    if ($stmt->execute()) {
        // Check if the current logged-in user is an admin
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $message = "You added a user successfully!";
            $redirect = 'user_list.php';
        } else {
            $message = "Registration successful!";
            $redirect = 'login.php';
        }
    } else {
        $message = "Error: " . $stmt->error;
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
    <title>Register</title>

    <!-- Internal CSS -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #222;
            padding: 1rem 0;
        }

        .navbar .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
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

        .nav-links a:hover {
            color: #00c9a7;
        }

        .container {
            width: 90%;
            max-width: 500px;
            margin: 3rem auto;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 2rem;
        }

        .card h2 {
            margin-bottom: 1.5rem;
            text-align: center;
            color: #00c9a7;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #00c9a7;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #009e84;
        }

        p {
            text-align: center;
            margin-top: 1rem;
        }

        p a {
            color: #00c9a7;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

    <!-- Navigation -->
    <header class="navbar">
        <div class="container">
            <h1 class="logo">SecureGuard</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="home.php">Home</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Register Form -->
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2>Register</h2>
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select id="role" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <button type="submit">Register</button>
                </form>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Show SweetAlert2 message -->
    <?php if (!empty($message)) : ?>
        <script>
            Swal.fire({
                icon: '<?php echo strpos($message, "successful") !== false ? "success" : "error"; ?>',
                title: '<?php echo $message; ?>',
                timer: 2000,
                showConfirmButton: false,
                willClose: () => {
                    window.location.href = '<?php echo $redirect ?? "login.php"; ?>';
                }
            });
        </script>
    <?php endif; ?>

</body>
</html>
