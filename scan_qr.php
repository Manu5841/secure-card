<?php
require 'auth_check.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Scan QR Code</title>

    <!-- External Libraries -->
    <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        nav {
            background: #1d3557;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        nav ul {
            list-style: none;
            text-align: right;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline-block;
            margin: 0 20px;
        }

        nav ul li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #a8dadc;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 15px;
            min-height: 90vh;
            flex-direction: column;
        }

        .scanner-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #1d3557;
            font-size: 22px;
            margin-bottom: 20px;
        }

        video {
            width: 100%;
            border-radius: 8px;
            display: none;
            margin-bottom: 20px;
        }

        input[type="file"] {
            display: block;
            margin: 20px auto;
            font-size: 14px;
        }

        button {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #startScanBtn {
            background: #007bff;
            color: white;
        }

        #startScanBtn:hover {
            background: #0056b3;
        }

        #stopScanBtn {
            background: #dc3545;
            color: white;
        }

        #stopScanBtn:hover {
            background: #a71d2a;
        }

        #submitBtn {
            background-color: #28a745;
            color: white;
            margin-top: 20px;
            display: none;
        }

        #submitBtn:hover {
            background-color: #218838;
        }

        #result {
            margin-top: 15px;
            color: #20c997;
            font-weight: 600;
            font-size: 16px;
        }

        @media (max-width: 480px) {
            .scanner-container {
                padding: 20px;
            }

            nav ul li {
                margin: 0 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Hidden session data -->
    <input type="hidden" id="loggedInUserId" value="<?php echo htmlspecialchars($_SESSION['user_id'] ?? ''); ?>">
    <input type="hidden" id="loggedInUsername" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>">

    <div class="container">
        <div class="scanner-container">
            <h2>Scan or Upload QR Code</h2>
            <button id="startScanBtn" onclick="startScan()">Start Scanning</button>
            <video id="video" autoplay></video>
            <button id="stopScanBtn" onclick="stopScan()" style="display: none;">Stop Scanning</button>
            <input type="file" id="upload" accept="image/*" />
            <div id="result"></div>
            <button id="submitBtn" onclick="submitData()">Submit QR Code</button>
        </div>
    </div>

    <script>
        let video = document.getElementById('video');
        let resultDiv = document.getElementById('result');
        let qrData = null;
        let scanning = false;
        let canvasElement = document.createElement('canvas');
        let canvas = canvasElement.getContext('2d');

        function startScan() {
            scanning = true;
            document.getElementById('startScanBtn').style.display = 'none';
            document.getElementById('video').style.display = 'block';
            document.getElementById('stopScanBtn').style.display = 'block';

            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(stream => {
                video.srcObject = stream;
                video.setAttribute('playsinline', true);
                video.play();
                tick();
            });
        }

        function stopScan() {
            scanning = false;
            const stream = video.srcObject;
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            document.getElementById('stopScanBtn').style.display = 'none';
            document.getElementById('startScanBtn').style.display = 'block';
            video.style.display = 'none';
        }

        function tick() {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    qrData = code.data.split(",");
                    resultDiv.innerText = `Scanned Data: ${code.data}`;
                    document.getElementById('submitBtn').style.display = 'block';
                    stopScan();
                }
            }

            requestAnimationFrame(tick);
        }

        document.getElementById('upload').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = event => {
                const image = new Image();
                image.src = event.target.result;
                image.onload = () => {
                    canvasElement.width = image.width;
                    canvasElement.height = image.height;
                    canvas.clearRect(0, 0, canvasElement.width, canvasElement.height);
                    canvas.drawImage(image, 0, 0);

                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        qrData = code.data.split(",");
                        resultDiv.innerText = `Uploaded QR Data: ${code.data}`;
                        document.getElementById('submitBtn').style.display = 'block';
                    } else {
                        resultDiv.innerText = 'No QR code found in the uploaded image.';
                        Swal.fire('Invalid', 'No QR code detected.', 'warning');
                    }
                };
            };
            reader.readAsDataURL(file);
        });

        function submitData() {
            if (qrData && qrData.length >= 1) {
                const qr_location = qrData[0].trim();
                const userId = document.getElementById('loggedInUserId').value;
                const username = document.getElementById('loggedInUsername').value;

                fetch('save_scan.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ qr_location, submitted_by_id: userId, submitted_by_username: username })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire('Success', 'Location submitted successfully.', 'success');
                    document.getElementById('submitBtn').style.display = 'none';
                    resultDiv.innerText = '';
                    qrData = null;
                    document.getElementById('upload').value = '';
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Failed to submit location.', 'error');
                });
            } else {
                Swal.fire('Warning', 'No valid QR data to submit.', 'warning');
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (typeof jsQR === "undefined") {
                alert("QR scanning library not loaded.");
            }
        });
    </script>
</body>
</html>
