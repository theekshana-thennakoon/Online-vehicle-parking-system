<?php
session_start();

// Handle session messages before any HTML output
$userParked = isset($_SESSION['user_parked']);
$userExit = isset($_SESSION['user_exit']);
$wrongUser = isset($_SESSION['wrong_user']);

// Clear session variables after reading
if ($userParked) unset($_SESSION['user_parked']);
if ($userExit) {
    unset($_SESSION["fname"]);
    unset($_SESSION["lname"]);
    unset($_SESSION["email"]);
    unset($_SESSION["amount"]);
    unset($_SESSION["user_exit"]);
}
if ($wrongUser) unset($_SESSION['wrong_user']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrance & Exit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            overflow: hidden;
        }

        .scanner-container {
            position: relative;
            width: 100%;
            height: 300px;
            overflow: hidden;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #qr-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .scan-line {
            position: absolute;
            height: 5px;
            width: 100%;
            background-color: #4a6bff;
            top: 0;
            animation: scan 2s linear infinite;
            box-shadow: 0 0 10px rgba(74, 107, 255, 0.8);
            z-index: 10;
        }

        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .corner-tl {
            top: 0;
            left: 0;
            border-width: 5px 0 0 5px;
        }

        .corner-tr {
            top: 0;
            right: 0;
            border-width: 5px 5px 0 0;
        }

        .corner-bl {
            bottom: 0;
            left: 0;
            border-width: 0 0 5px 5px;
        }

        .corner-br {
            bottom: 0;
            right: 0;
            border-width: 0 5px 5px 0;
        }

        .scan-region {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 200px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            z-index: 5;
        }

        .result-container {
            padding: 20px;
            text-align: center;
        }

        #result {
            margin: 15px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #eee;
            min-height: 60px;
            word-break: break-all;
        }


        .status {
            margin-top: 10px;
            color: #666;
            font-style: italic;
        }

        .permission-request {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            text-align: center;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 20;
        }

        .permission-request p {
            margin-bottom: 20px;
        }

        .camera-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #4a6bff;
        }

        .loading {
            color: white;
            font-size: 18px;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .success-message {
            background-color: #4BB543;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }

        @keyframes scan {
            0% {
                top: 0;
            }

            100% {
                top: 100%;
            }
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
            }

            .scanner-container {
                height: 250px;
            }

            .scan-region {
                width: 80%;
                height: 180px;
            }
        }
    </style>
    <link href="../includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../includes/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../includes/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-database-compat.js"></script>
</head>

<body>
    <div class="container">
        <div class="mt-3">
            <h1 class="h2 text-center">Entrance & Exit Dashboard</h1>
            <p class="text-center">Position a QR code inside the frame to scan</p>
        </div>

        <div class="scanner-container" id="scanner-container">
            <!-- Video element for camera stream -->
            <video id="qr-video" playsinline></video>

            <div class="scan-region"></div>
            <div class="scanner-overlay">
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>
                <div class="scan-line"></div>
            </div>

            <!-- Loading indicator -->
            <div class="loading" id="loading">Starting camera...</div>

            <!-- Permission request UI (initially hidden) -->
            <div class="permission-request" id="permission-request" style="display: none;">
                <div class="camera-icon">📷</div>
                <h3>Camera Access Required</h3>
                <p>We need access to your camera to scan QR codes.</p>
                <button id="request-permission-btn" class="btn">Allow Camera Access</button>
            </div>
        </div>

        <div class="result-container">
            <h3>Scan Result</h3>
            <div id="result" style="display: none;">Scanning will begin automatically...</div>
            <div class="success-message" id="success-message">
                Data successfully sent to server!
            </div>
            <button id="start-button" class="btn" style="display: none;">Start Scanner</button>
            <button id="stop-button" class="btn btn-stop"></button>
            <div class="status" id="status">Starting camera...</div>
        </div>
    </div>

    <!-- Hidden form for auto-submission -->
    <form id="data-form" action="../x.php" method="POST" style="display: none;">
        <input type="hidden" id="scanned-data" name="entrance_exit_input" value="">
    </form>

    <script src="../includes/vendor/jquery/jquery.min.js"></script>
    <script src="../includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../includes/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../includes/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../includes/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../includes/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../includes/js/demo/datatables-demo.js"></script>

    <!-- SweetAlert -->
    <script src="../includes/sweetalert.js"></script>

    <!-- Firebase Initialization -->
    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCV2TegSk-r8seMnc4KmBJxDCnreX9yLBw",
            authDomain: "parking-gate-d54d5.firebaseapp.com",
            databaseURL: "https://parking-gate-d54d5-default-rtdb.firebaseio.com",
            projectId: "parking-gate-d54d5",
            storageBucket: "parking-gate-d54d5.firebasestorage.app",
            messagingSenderId: "650184790827",
            appId: "1:650184790827:web:941dec87e5819509ffeb6e",
            measurementId: "G-FFNZ3S80Z8"
        };

        // Initialize Firebase
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const database = firebase.database();

        // Gate status update function
        function updateGateStatus(status) {
            firebase.database().ref('gate_status/current').set({
                status: status,
                timestamp: firebase.database.ServerValue.TIMESTAMP
            }).then(() => {
                console.log("Gate status updated to: " + status);
            }).catch(error => {
                console.error("Error updating gate status: ", error);
            });
        }

        // Handle session messages
        $(document).ready(function() {
            <?php if ($userParked): ?>
                updateGateStatus(1);
                Swal.fire({
                    icon: 'success',
                    title: 'Parked',
                    text: 'Successfully parked!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            <?php endif; ?>

            <?php if ($userExit): ?>
                updateGateStatus(1);
                Swal.fire({
                    icon: 'success',
                    title: 'Exit',
                    text: 'Successfully Exit!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            <?php endif; ?>

            <?php if ($wrongUser): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Wrong user',
                    text: 'You are not authorized to park here!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            <?php endif; ?>
        });
    </script>

    <!-- Include jsQR library for QR code scanning -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resultContainer = document.getElementById('result');
            const startButton = document.getElementById('start-button');
            const stopButton = document.getElementById('stop-button');
            const statusIndicator = document.getElementById('status');
            const permissionRequest = document.getElementById('permission-request');
            const requestPermissionBtn = document.getElementById('request-permission-btn');
            const scannerContainer = document.getElementById('scanner-container');
            const video = document.getElementById('qr-video');
            const loadingIndicator = document.getElementById('loading');
            const successMessage = document.getElementById('success-message');
            const dataForm = document.getElementById('data-form');
            const scannedDataInput = document.getElementById('scanned-data');

            let isScanning = false;
            let stream = null;
            let canvasElement = null;
            let canvasContext = null;
            let dataSent = false;

            // Function to initialize the scanner
            function initScanner() {
                // Create a canvas for capturing video frames
                canvasElement = document.createElement('canvas');
                canvasContext = canvasElement.getContext('2d');
                scannerContainer.appendChild(canvasElement);
                canvasElement.style.display = 'none';

                // Start scanning automatically
                setTimeout(startScanner, 500);
            }

            // Function to start scanning
            function startScanner() {
                if (isScanning) return;

                statusIndicator.textContent = "Requesting camera permission...";
                loadingIndicator.textContent = "Starting camera...";

                // Use the MediaDevices API to access the camera
                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    })
                    .then(function(mediaStream) {
                        stream = mediaStream;
                        video.srcObject = mediaStream;
                        video.setAttribute('playsinline', true); // required for iOS

                        video.onloadedmetadata = function() {
                            video.play();
                            statusIndicator.textContent = "Scanning...";
                            loadingIndicator.style.display = 'none';
                            startButton.style.display = 'none';
                            stopButton.style.display = 'inline-block';
                            isScanning = true;
                            hidePermissionRequest();
                            requestAnimationFrame(tick);
                        };
                    })
                    .catch(function(err) {
                        statusIndicator.textContent = "Error accessing camera: " + err.message;
                        loadingIndicator.style.display = 'none';
                        showPermissionRequest();
                    });
            }

            // Function to stop scanning
            function stopScanner() {
                if (!isScanning) return;

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }

                video.srcObject = null;
                isScanning = false;
                statusIndicator.textContent = "Scanner stopped";
                startButton.style.display = 'inline-block';
                stopButton.style.display = 'none';
                loadingIndicator.style.display = 'none';
            }

            // Function to submit data to server
            function submitData(data) {
                if (dataSent) return; // Prevent multiple submissions

                dataSent = true;
                scannedDataInput.value = data;

                // Show success message
                successMessage.style.display = 'block';
                statusIndicator.textContent = "Data submitted successfully!";

                // Submit the form
                dataForm.submit();

                // Stop the scanner after a short delay
                setTimeout(stopScanner, 2000);
            }

            // Function to process each video frame
            function tick() {
                if (!isScanning) return;

                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    // Set canvas dimensions to match video
                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;

                    // Draw video frame to canvas
                    canvasContext.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

                    // Get image data from canvas
                    const imageData = canvasContext.getImageData(0, 0, canvasElement.width, canvasElement.height);

                    // Try to detect QR code
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code) {
                        // QR code detected!
                        resultContainer.innerHTML = `
                            <strong>Content:</strong> ${code.data}<br>
                        `;

                        // Submit the data automatically
                        submitData(code.data);

                        // Draw a rectangle around the QR code (for visualization)
                        drawRect(code.location);
                    }
                }

                // Continue processing frames
                requestAnimationFrame(tick);
            }

            // Function to draw a rectangle around the QR code
            function drawRect(location) {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');

                // Draw the video frame
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Draw rectangle
                ctx.strokeStyle = "#4a6bff";
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.moveTo(location.topLeftCorner.x, location.topLeftCorner.y);
                ctx.lineTo(location.topRightCorner.x, location.topRightCorner.y);
                ctx.lineTo(location.bottomRightCorner.x, location.bottomRightCorner.y);
                ctx.lineTo(location.bottomLeftCorner.x, location.bottomLeftCorner.y);
                ctx.closePath();
                ctx.stroke();
            }

            // Show the permission request UI
            function showPermissionRequest() {
                permissionRequest.style.display = 'flex';
                statusIndicator.textContent = "Camera permission required";
            }

            // Hide the permission request UI
            function hidePermissionRequest() {
                permissionRequest.style.display = 'none';
            }

            // Set up event listeners
            startButton.addEventListener('click', startScanner);
            stopButton.addEventListener('click', stopScanner);

            // Request permission when the button is clicked
            requestPermissionBtn.addEventListener('click', function() {
                hidePermissionRequest();
                loadingIndicator.style.display = 'block';
                startScanner();
            });

            // Initialize the scanner when page loads
            initScanner();
        });
    </script>

</body>

</html>