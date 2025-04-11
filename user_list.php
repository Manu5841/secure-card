<?php
require 'config.php';
require 'auth_check.php'; // Include the session check

// Redirect if the user is not logged in or is not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle delete, deactivate, make admin, and view details actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['user_id'])) {
    $user_id = (int) $_POST['user_id'];  // Casting to ensure it's an integer
    $action = $_POST['action'];

    if ($action == "delete") {
        // Delete the user from the database
        $stmt = $conn->prepare("DELETE FROM survey WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully!', 'user_id' => $user_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting user: ' . htmlspecialchars($stmt->error)]);
        }
        $stmt->close();
    } elseif ($action == "deactivate") {
        // Deactivate the user
        $stmt = $conn->prepare("UPDATE survey SET status = 'inactive' WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User deactivated successfully!', 'user_id' => $user_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deactivating user: ' . htmlspecialchars($stmt->error)]);
        }
        $stmt->close();
    } elseif ($action == "makeAdmin") {
        // Promote the user to admin role
        $stmt = $conn->prepare("UPDATE survey SET role = 'admin' WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User promoted to admin successfully!', 'user_id' => $user_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error promoting user: ' . htmlspecialchars($stmt->error)]);
        }
        $stmt->close();
    } elseif ($action == "viewDetails") {
        // Fetch user details
        $stmt = $conn->prepare("SELECT user_id, username, role,  location FROM survey WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode(['status' => 'success', 'data' => $user]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
        $stmt->close();
    }
    exit();
}

// Fetch all users from the database
$sql = "SELECT user_id, username, role FROM survey";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Fixed header styling */
        .fixed-header {
            position: fixed;
            top: 2px;
            width: 100%;
            z-index: 1000;
            background-color: rgb(92, 117, 141);
            padding: 15px 0;
            color: white;
            border-bottom: 2px solid #ccc; /* Separation line */
        }
        .fixed-header a {
            color: white;
            margin-right: 20px;
        }
        .content {
            margin-top: 100px; /* Adjust to accommodate the fixed header height */
        }
    </style>
</head>
<body>

    <!-- Fixed Header Section -->
    <div class="fixed-header">
        <div class="container">
            <h4 class="float-left">Admin Dashboard</h4>
            <a href="admin_dashboard.php" class="float-right">Back to Admin Dashboard</a>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2>User List</h2>
                
                <div id="message"></div> <!-- Message area for success or error -->

                <?php if ($result && $result->num_rows > 0): ?>
                    <table class="table table-bordered table-hover mt-4" id="userTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr id="user-<?php echo $row['user_id']; ?>">
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                    <td>
                                        <!-- View Details Button -->
                                        <button class="btn btn-primary btn-sm" onclick="viewDetails(<?php echo $row['user_id']; ?>)">View Details</button>
                                        
                                        <!-- Delete User -->
                                        <button class="btn btn-danger btn-sm" onclick="performAction(<?php echo $row['user_id']; ?>, 'delete')">Delete</button>

                                        <!-- Deactivate User -->
                                        <button class="btn btn-warning btn-sm" onclick="performAction(<?php echo $row['user_id']; ?>, 'deactivate')">Deactivate</button>

                                        <!-- Make Admin (only if the user is not already an admin) -->
                                        <?php if ($row['role'] !== 'admin'): ?>
                                            <button class="btn btn-info btn-sm" onclick="performAction(<?php echo $row['user_id']; ?>, 'makeAdmin')">Make Admin</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No users found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal to Display User Details -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" role="dialog" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>User ID:</strong> <span id="modalUserId"></span></p>
                    <p><strong>Username:</strong> <span id="modalUsername"></span></p>
                    <p><strong>Role:</strong> <span id="modalRole"></span></p>
                    <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function performAction(user_id, action) {
        $.ajax({
            type: "POST",
            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
            data: {
                user_id: user_id,
                action: action
            },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#message').html('<p class="alert alert-success">' + res.message + '</p>');
                    if (action === 'delete') {
                        $('#user-' + user_id).remove();  // Remove the user row from the table
                    }
                } else {
                    $('#message').html('<p class="alert alert-danger">' + res.message + '</p>');
                }
                // Set a timer to clear the message after 2 seconds
                setTimeout(function() {
                    $('#message').html('');
                }, 2000);  
            },
            error: function() {
                $('#message').html('<p class="alert alert-danger">Error performing action.</p>');
                // Set a timer to clear the error message after 2 seconds
                setTimeout(function() {
                    $('#message').html('');
                }, 2000);
            }
        });
    }

    function viewDetails(user_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
            data: {
                user_id: user_id,
                action: 'viewDetails'
            },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#modalUserId').text(res.data.user_id);
                    $('#modalUsername').text(res.data.username);
                    $('#modalRole').text(res.data.role);
                    $('#modalLocation').text(res.data.location);
                    $('#userDetailsModal').modal('show');
                } else {
                    alert('Error fetching user details: ' + res.message);
                }
            }
        });
    }
    </script>
</body>
</html>
