<?php
require 'config.php';
require 'auth_check.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $user_id = $_POST['user_id'] ?? null;
        
        switch ($action) {
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM survey WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                break;
                
            case 'promote':
                $stmt = $conn->prepare("UPDATE survey SET role = 'admin' WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                break;
                
            case 'demote':
                $stmt = $conn->prepare("UPDATE survey SET role = 'user' WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                break;
                
            case 'reset_password':
                $new_password = password_hash("temp123", PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE survey SET password = ? WHERE user_id = ?");
                $stmt->bind_param("si", $new_password, $user_id);
                $stmt->execute();
                break;
        }
    }
}

// Get current view
$show_table = isset($_GET['table']) ? $_GET['table'] : 'users';

// Fetch data
if ($show_table === 'qrdata') {
    $query = "SELECT * FROM QRDATA ORDER BY scanned_at DESC";
    $table_title = "QR Code Scan Data";
} else {
    $query = "SELECT * FROM survey ORDER BY user_id DESC";
    $table_title = "User Management";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .fixed-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #343a40;
            padding: 15px 0;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .content {
            margin-top: 20px;
            padding-bottom: 50px;
        }
        .action-buttons .btn {
            margin: 2px;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .badge-admin {
            background-color: #dc3545;
        }
        .badge-user {
            background-color: #28a745;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .nav-tabs {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="fixed-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Admin Dashboard</h4>
                <div>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container content">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= $show_table === 'users' ? 'active' : '' ?>" href="?table=users">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $show_table === 'qrdata' ? 'active' : '' ?>" href="?table=qrdata">
                    <i class="fas fa-qrcode"></i> QR Scan Data
                </a>
            </li>
        </ul>

        <!-- Add New User Button (only shown in user management view) -->
        <?php if ($show_table === 'users'): ?>
            <div class="mb-3">
    <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
        <i class="fas fa-user-plus"></i> Add New User
    </button>

    <a href="generate.php" class="btn btn-success">
        <i class="fas fa-file-alt"></i> View Report
    </a>
</div>

        <?php endif; ?>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= $table_title ?></h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <?php if ($show_table === 'qrdata'): ?>
                                    <th>ID</th>
                                    <th>Location</th>
                                    <th>Scanned At</th>
                                    <th>User ID</th>
                                    <th>Username</th>
                                <?php else: ?>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <?php if ($show_table === 'qrdata'): ?>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['location']) ?></td>
                                        <td><?= htmlspecialchars($row['scanned_at']) ?></td>
                                        <td><?= htmlspecialchars($row['submitted_by_id']) ?></td>
                                        <td><?= htmlspecialchars($row['submitted_by_username']) ?></td>
                                    <?php else: ?>
                                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td>
                                            <span class="badge <?= $row['role'] === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                                                <?= ucfirst(htmlspecialchars($row['role'])) ?>
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
                                                <?php if ($row['role'] === 'admin'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="demote">
                                                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                                        <button type="submit" class="btn btn-warning btn-sm" title="Demote to User">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="promote">
                                                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                                        <button type="submit" class="btn btn-success btn-sm" title="Promote to Admin">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="reset_password">
                                                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                                    <button type="submit" class="btn btn-info btn-sm" title="Reset Password">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">Current User</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="add_user.php" id="addUserForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newUsername">Username</label>
                        <input type="text" class="form-control" id="newUsername" name="username" required>
                        <small class="form-text text-muted">Must be unique</small>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Password</label>
                        <input type="password" class="form-control" id="newPassword" name="password" required>
                        <small class="form-text text-muted">Minimum 6 characters</small>
                    </div>
                    <div class="form-group">
                        <label for="userRole">Role</label>
                        <select class="form-control" id="userRole" name="role">
                            <option value="user">Regular User</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    // Show success/error messages from add_user.php
    <?php if (isset($_SESSION['message'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['message_type'] ?>',
            title: '<?= $_SESSION['message'] ?>',
            timer: 3000
        });
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>
});
</script>
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?");
        }
        
        // Show success/error messages from session
        <?php if (isset($_SESSION['message'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['message_type'] ?>',
                title: '<?= $_SESSION['message'] ?>',
                timer: 3000
            });
            <?php 
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>
    </script>
</body>
</html>