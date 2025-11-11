<?php

include("./includes/database.php");
session_start();
$cookie_name = "user";
?>

<?php
//echo "banuka abeysinghe";
$sql_check_is_paid = "SELECT * FROM activity WHERE uid = {$_COOKIE[$cookie_name]} and i_date != ''and o_date != '' and price != 0 and paid = 0";
$result_check_is_paid = $conn->query($sql_check_is_paid);
if ($result_check_is_paid->num_rows > 0) {
    while ($row_check_is_paid = $result_check_is_paid->fetch_assoc()) {
        $activity_id = $row_check_is_paid["id"];
        $i_date = $row_check_is_paid["i_date"];
        $i_time = $row_check_is_paid["i_time"];
        $o_date = $row_check_is_paid["o_date"];
        $o_time = $row_check_is_paid["o_time"];
        $_SESSION['price'] = $price = $row_check_is_paid["price"];

        echo "
            <script>
            Swal.fire({
                icon: 'info',
                title: 'Payment...',
                text: 'You need to pay LKR {$price} for your parking!',
                confirmButtonText: 'Proceed to Payment',
                showCloseButton: true,
                closeButtonAriaLabel: 'Close dialog'
            }).then((result) => {
                if (result.isConfirmed) {
                // Redirect to payment page
                window.location.href = 'checkpayment.php';
                }
            });
            </script>";
    }
}
?>


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

//Get sensor values or default to 0 if not set
// $s1_s2_value = isset($data['S1_S2']) ? $data['S1_S2'] : 0;
// $s3_s4_value = isset($data['S3_S4']) ? $data['S3_S4'] : 0;
// $s5_s6_value = isset($data['S5_S6']) ? $data['S5_S6'] : 0;
// $s7_s8_value = isset($data['S7_S8']) ? $data['S7_S8'] : 0;

$s1_value = isset($data['S1']) ? $data['S1'] : 0;
$s2_value = isset($data['S2']) ? $data['S2'] : 0;
$s3_value = isset($data['S3']) ? $data['S3'] : 0;
$s4_value = isset($data['S4']) ? $data['S4'] : 0;
$s5_value = isset($data['S5']) ? $data['S5'] : 0;
$s6_value = isset($data['S6']) ? $data['S6'] : 0;
$s7_value = isset($data['S7']) ? $data['S7'] : 0;
$s8_value = isset($data['S8']) ? $data['S8'] : 0;

$get_enabled_slots = "SELECT * FROM parking_slot WHERE status = 1";
$result_enabled_slots = $conn->query($get_enabled_slots);
if ($result_enabled_slots->num_rows > 0) {
    while ($row_enabled_slots = $result_enabled_slots->fetch_assoc()) {
        $get_slot_id = $row_enabled_slots['id'];

        $check_is_booked = "SELECT * FROM activity WHERE pid = {$get_slot_id} and o_date = ''";
        $result_check_is_booked = $conn->query($check_is_booked);
        if ($result_check_is_booked->num_rows > 0) {
            while ($row = $result_check_is_booked->fetch_assoc()) {
                $uid = $row['uid'];
                $booked = $row['booked'];
                //$display_status = $row['display_status'];
                if ($booked == 1) {
                    $update_slot_sql = "UPDATE parking_slot SET p_status = 1 WHERE id = {$get_slot_id}";
                    $conn->query($update_slot_sql);
                } else {
                    $slot_value = "s{$get_slot_id}_value";
                    if ($$slot_value == 1) {
                        $update_slot_sql = "UPDATE parking_slot SET p_status = 1 WHERE id = {$get_slot_id}";
                        $conn->query($update_slot_sql);
                    } else {
                        $update_slot_sql = "UPDATE parking_slot SET p_status = 0 WHERE id = {$get_slot_id}";
                        $conn->query($update_slot_sql);
                    }
                }
            }
        } else {
            $slot_value = "s{$get_slot_id}_value";
            if ($$slot_value == 1) {
                $update_slot_sql = "UPDATE parking_slot SET p_status = 1 WHERE id = {$get_slot_id}";
                $conn->query($update_slot_sql);
            } else {
                $update_slot_sql = "UPDATE parking_slot SET p_status = 0 WHERE id = {$get_slot_id}";
                $conn->query($update_slot_sql);
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Updated Parking Layout</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        a:hover {
            color: #fff;
            text-decoration: none;
        }

        .road {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: stretch;
            width: 100vw;
            height: 50vh;
            position: relative;
        }

        /* Center Divider - main road look */
        .center-divider {
            width: 100px;
            background-color: #222;
            /* asphalt gray */
            height: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-evenly;
            z-index: 1;
        }

        .dashed-line {
            width: 6px;
            height: 40px;
            background-color: white;
            border-radius: 2px;
        }


        /* Vertical Text Styling */
        .vertical-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            /* optional: for top-to-bottom */
            font-size: 16px;
            font-weight: bold;
            color: #444;
            letter-spacing: 2px;
            text-align: center;
        }


        .left-side,
        .right-side {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            padding: 10px;
            height: 100%;
            z-index: 1;
            background-color: #222;
        }

        .left-side {
            align-items: flex-end;
            margin-right: 40px;
        }

        .right-side {
            align-items: flex-start;
            margin-left: 40px;
        }

        .parking-slot {
            width: 180px;
            height: 50px;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1rem;
        }


        .center-divider .vertical-text {
            transform: rotate(90deg);
            transform-origin: left center;
            width: 100vh;
            margin-left: 50%;
            font-weight: bold;
            margin-top: 50vh;
        }

        @media (max-width: 768px) {
            .parking-slot {
                width: 120px;
                height: 40px;
                font-size: 0.9rem;
            }

            .center-divider {
                width: 40px;
            }

            .left-side,
            .right-side {
                margin: 0 10px;
            }
        }
    </style>

</head>

<body>
    <script src="./includes/sweetalert.js"></script>
    <!-- <div class="sensor-container" id="sensor-display">
        <p>S1_S2 <?php echo $s1_s2_value; ?></p>
        <p>S3_S4 <?php echo $s3_s4_value; ?></p>
        <p>S5_S6 <?php echo $s5_s6_value; ?></p>
        <p>S7_S8 <?php echo $s7_s8_value; ?></p>
    </div> -->



    <script src="script.js"></script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

    <!-- <?php
            $cookie_name = "user";
            $logged_user = $_COOKIE[$cookie_name];
            $sql_check_is_paid = "SELECT * FROM activity WHERE uid = {$logged_user} and i_date != ''and o_date != '' and price != 0 and paid = 0";
            $result_check_is_paid = $conn->query($sql_check_is_paid);

            if ($result_check_is_paid->num_rows > 0) {
                while ($row_check_is_paid = $result_check_is_paid->fetch_assoc()) {
                    $activity_id = $row_check_is_paid["id"];
                    $i_date = $row_check_is_paid["i_date"];
                    $i_time = $row_check_is_paid["i_time"];
                    $o_date = $row_check_is_paid["o_date"];
                    $o_time = $row_check_is_paid["o_time"];
                    $price = $row_check_is_paid["price"];

                    //         echo "

                    //     <script>
                    //         paymentGateWay()
                    //     </script>
                    // ";
                }
            }
            ?> -->
</body>

</html>