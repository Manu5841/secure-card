<?php
// Include the PHP QR Code library
include('qrlib.php');  // Make sure this path is correct

// Check if 'username' is set in the POST request
if (isset($_POST['username'])) {
    $username = $_POST['username'];
} else {
    die("Username is missing!");
}

// Generate the QR code with the username
$qrData = $username;
$qrFilePath = 'qrcodes/' . $username . '.png';  // Path to save the generated QR code
QRcode::png($qrData, $qrFilePath, QR_ECLEVEL_L, 10, 2);

echo "QR code generated successfully for username: $username!";
?>
