<?php
// Firebase Realtime Database URL
$firebaseUrl = 'https://parking-gate-d54d5-default-rtdb.firebaseio.com/Sensor.json';

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Execute cURL request
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Get current timestamp
$timestamp = date('H:i:s');

// Assign status values to PHP variables
$s1_s2_status = isset($data['S1_S2']) && $data['S1_S2'] == 1 ? 'active' : 'inactive';
$s3_s4_status = isset($data['S3_S4']) && $data['S3_S4'] == 1 ? 'active' : 'inactive';
$s5_s6_status = isset($data['S5_S6']) && $data['S5_S6'] == 1 ? 'active' : 'inactive';
$s7_s8_status = isset($data['S7_S8']) && $data['S7_S8'] == 1 ? 'active' : 'inactive';

// Get sensor values or default to 0 if not set
$s1_s2_value = isset($data['S1_S2']) ? $data['S1_S2'] : 0;
$s3_s4_value = isset($data['S3_S4']) ? $data['S3_S4'] : 0;
$s5_s6_value = isset($data['S5_S6']) ? $data['S5_S6'] : 0;
$s7_s8_value = isset($data['S7_S8']) ? $data['S7_S8'] : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Sensors - PHP Version</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .sensor-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .sensor-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .sensor-card.active {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .sensor-card.inactive {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .sensor-value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .status {
            color: #fff;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .status.active {
            background-color: #28a745;
        }

        .status.inactive {
            background-color: #dc3545;
        }

        .last-update {
            font-size: 12px;
            color: #6c757d;
            margin-top: 10px;
        }

        .refresh-button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .refresh-button:hover {
            background-color: #0069d9;
        }
    </style>
</head>

<body>
    <h1>Parking Sensor Status</h1>
    <p>Current status from Firebase (PHP version)</p>

    <div class="sensor-container" id="sensor-display">
        <div class="sensor-card <?php echo $s1_s2_status; ?>">
            <h3>S1_S2</h3>
            <div class="sensor-value"><?php echo $s1_s2_value; ?></div>
            <span class="status <?php echo $s1_s2_status; ?>">
                <?php echo strtoupper($s1_s2_status); ?>
            </span>
            <div class="last-update">Updated: <?php echo $timestamp; ?></div>
        </div>

        <div class="sensor-card <?php echo $s3_s4_status; ?>">
            <h3>S3_S4</h3>
            <div class="sensor-value"><?php echo $s3_s4_value; ?></div>
            <span class="status <?php echo $s3_s4_status; ?>">
                <?php echo strtoupper($s3_s4_status); ?>
            </span>
            <div class="last-update">Updated: <?php echo $timestamp; ?></div>
        </div>

        <div class="sensor-card <?php echo $s5_s6_status; ?>">
            <h3>S5_S6</h3>
            <div class="sensor-value"><?php echo $s5_s6_value; ?></div>
            <span class="status <?php echo $s5_s6_status; ?>">
                <?php echo strtoupper($s5_s6_status); ?>
            </span>
            <div class="last-update">Updated: <?php echo $timestamp; ?></div>
        </div>

        <div class="sensor-card <?php echo $s7_s8_status; ?>">
            <h3>S7_S8</h3>
            <div class="sensor-value"><?php echo $s7_s8_value; ?></div>
            <span class="status <?php echo $s7_s8_status; ?>">
                <?php echo strtoupper($s7_s8_status); ?>
            </span>
            <div class="last-update">Updated: <?php echo $timestamp; ?></div>
        </div>
    </div>


    <button class="refresh-button" onclick="window.location.reload()">Refresh Data</button>

    <script>
        // Auto-refresh every 5 seconds (5000 milliseconds)
        setTimeout(function() {
            window.location.reload();
        }, 5000);
    </script>
</body>

</html>