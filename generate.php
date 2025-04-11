<?php
require 'config.php';
require 'auth_check.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle CSV report generation
if (isset($_GET['action']) && $_GET['action'] === 'download_report') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="logged_in_users.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['id', 'location', 'scanned_at']);

    $query = "SELECT id, scanned_at FROM qrdata WHERE scanned_at IS NOT NULL ORDER BY scanned_at DESC";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Fetch logged-in users
$query = "SELECT id, location, scanned_at FROM qrdata WHERE scanned_at IS NOT NULL ORDER BY scanned_at DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged-in Users Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-3">Logged-in Users Report</h2>
        
        <div class="mb-3">
            <a href="?action=download_report" class="btn btn-success">
                <i class="fas fa-download"></i> Download Report
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>id</th>
                        
                        <th>location</th>
                        <th>scanned_at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            
                            <td><?= ucfirst(htmlspecialchars($row['location'])) ?></td>
                            <td><?= htmlspecialchars($row['scanned_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
