<?php
// Database connection settings
$host = 'localhost';
$db   = 'studenp_db';
$user = 'root';
$pass = '';

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            // Create DB connection
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare and execute SQL insert
            $sql = "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message
            ]);

            // Show SweetAlert success message
            echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        text: 'Thank you, $name! Your message was saved successfully.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'contact.php';
                    });
                </script>
            </body>
            </html>";
        } catch (PDOException $e) {
            // Show error message with SweetAlert
            echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Database Error',
                        text: '". addslashes($e->getMessage()) ."',
                        confirmButtonText: 'Back'
                    }).then(() => {
                        window.location.href = 'contact.php';
                    });
                </script>
            </body>
            </html>";
        }
    } else {
        // Missing input fields
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    text: 'Please fill in all fields before submitting.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'contact.php';
                });
            </script>
        </body>
        </html>";
    }
} else {
    header("Location: contact.php");
    exit();
}
?>
