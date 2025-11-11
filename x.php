<?php
session_start();
include("./includes/database.php");
date_default_timezone_set('Asia/Colombo');

echo "
<script src=\"script.js\"></script>
<script type=\"text/javascript\" src=\"https://www.payhere.lk/lib/payhere.js\"></script>
";
?>
<html>

<head>
    <title>Parking System</title>
</head>

<body>
    <?php
    //Register
    if (isset($_POST["register"])) {
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $nic = $_POST["nic"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $pwd = $_POST["pwd"];
        $rpwd = $_POST["rpwd"];
        $sql = "SELECT * FROM users WHERE email = '{$email}' or nic = '{$nic}'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION['already_email'] = 1;
            header("Location:./register.php");
        } else {
            if ($pwd == $rpwd) {
                $hash = password_hash($pwd, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (fname, lname, nic,phone,email,pwd,status)
            VALUES ('{$fname}', '{$lname}', '{$nic}', {$phone}, '{$email}', '{$hash}',1)";

                if ($conn->query($sql) === TRUE) {
                    $sql_user = "SELECT * FROM users WHERE email = '{$email}'";
                    $result_user = $conn->query($sql_user);
                    if ($result_user->num_rows > 0) {
                        while ($row_user = $result_user->fetch_assoc()) {
                            $uid = $row_user["id"];
                            $sql = "UPDATE users SET qr = 'img/qr/ps_00{$uid}' WHERE id = {$uid}";
                            if ($conn->query($sql) === TRUE) {
                            }
                        }
                    }
                    // Include the PHP QR Code library
                    include('includes/phpqrcode/qrlib.php');

                    // File path where the QR code image will be saved
                    $file = "includes/img/qr/ps_00{$uid}.png";

                    // Generate the QR code and save it as an image
                    QRcode::png("ps_00{$uid}", $file, QR_ECLEVEL_L, 10, 2);

                    //$cookie_name = "user";
                    //$cookie_value = $uid;
                    //setcookie($cookie_name, $cookie_value, time() + (3600), "/"); // 3600 = 1 hour
                    // Replace with your actual Formspree form ID
                    $email_sent = false;

                    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $formspree_endpoint = 'https://formspree.io/f/mdkynwjd';

                        // Formspree special fields for auto-reply
                        // create a verification token and save it for the user
                        $email_token = $email;

                        // build verification link

                        $verify_link = "https://onlinesmartparking.great-site.net/verify_email.php?token={$email_token}";

                        // Formspree payload to send verification link
                        $email_data = [
                            '_replyto' => $email,
                            '_subject' => 'Verify Your Smart Parking Email',
                            'customer_name' => "{$fname} {$lname}",
                            'verification_link' => $verify_link,
                            'message' => "Hello {$fname},\n\nPlease verify your email address by clicking the link below:\n{$verify_link}\n\nIf you did not register, ignore this email."
                        ];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $formspree_endpoint);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($email_data));
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        $response = curl_exec($ch);
                        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);

                        $email_sent = ($http_code == 200);
                    }
                    header("Location:./slots.php");
                }
            } else {
                $_SESSION['password_not_match'] = 1;
                header("Location:./register.php");
            }
        }
    }

    //Login
    if (isset($_POST["login"])) {
        $email = $_POST["email"];
        $pwd = $_POST["pwd"];
        $sql_user = "SELECT * FROM users WHERE email = '{$email}' and status = 1";
        $result_user = $conn->query($sql_user);
        if ($result_user->num_rows > 0) {
            while ($row_user = $result_user->fetch_assoc()) {
                $hash = $row_user["pwd"];
                $uid = $row_user["id"];
            }

            if (password_verify($pwd, $hash)) {
                $cookie_name = "user";
                $cookie_value = $uid;
                setcookie($cookie_name, $cookie_value, time() + (3600), "/"); // 3600 = 1 hour
                header("Location:./");
            } else {
                $_SESSION['wrong_pwd'] = 1;
                //header("Location:./login.php");
            }
        } else {
            $_SESSION['wrong_user'] = 1;
            //header("Location:./login.php");
        }
        header("Location:./");
    }

    //Logout
    if (isset($_GET["logout_id"])) {
        $cookie_name = "user";
        setcookie($cookie_name, "", time() - (3600), "/");
        header("Location:./");
    }

    //Admin Logout
    if (isset($_GET["logout_admin_id"])) {
        $cookie_name = "admin";
        $cookie_value = "Admin";
        setcookie($cookie_name, "", time() - (3600), "/");
        header("Location:./admin");
    }

    //Change slot Status
    if (isset($_GET["change_status"])) {
        $change_status = $_GET["change_status"];
        $sql_stts = "SELECT * FROM parking_slot WHERE id = '{$change_status}'";
        $result_stts = $conn->query($sql_stts);
        if ($result_stts->num_rows > 0) {
            while ($row_stts = $result_stts->fetch_assoc()) {
                $status = $row_stts["status"];
                if ($status == 1) {
                    $status = 0;
                } else {
                    $status = 1;
                }
                $sql = "UPDATE parking_slot SET status = {$status} WHERE id = {$change_status}";
                if ($conn->query($sql) === TRUE) {
                    $_SESSION['change_status'] = 1;
                    header("Location:./admin/change_slot_status.php");
                }
            }
        }
    }

    //Booking slot
    if (isset($_GET["book_slot"])) {
        $book_slot = $_GET["book_slot"];
        $cookie_name = "user";
        $logged_user = $_COOKIE[$cookie_name];
        $live_date = date('Y-m-d');
        $live_time = date('H:i:s');
        $live_date = isset($_GET['date']) ? $_GET['date'] : $live_date;
        $live_time = isset($_GET['time']) ? $_GET['time'] : $live_time;

        $sql_is_already_booked = "SELECT * FROM parking_slot WHERE id = '{$book_slot}' and p_status = 1";
        $result_sql_is_already_booked = $conn->query($sql_is_already_booked);
        if ($result_sql_is_already_booked->num_rows > 0) {
            $sqli = "SELECT * FROM activity WHERE pid = {$book_slot} and uid = {$logged_user} and o_date = ''";
            $resulti = $conn->query($sqli);
            if ($resulti->num_rows > 0) {
                while ($rowi = $resulti->fetch_assoc()) {
                    $delsql = "DELETE FROM activity WHERE uid = '{$logged_user}' and pid = '{$book_slot}' and booked = 1";
                    if ($conn->query($delsql) === TRUE) {
                        $updatesql = "UPDATE parking_slot SET p_status = 0 WHERE id = {$book_slot}";
                        $conn->query($updatesql);
                        if ($conn->query($updatesql) === TRUE) {
                            $_SESSION['slot_already_deleted'] = $book_slot;
                            echo "<script>window.history.back();</script>";
                        }
                    }
                }
            } else {
                $_SESSION['already_booked'] = $book_slot;
                echo "<script>window.history.back();</script>";
            }
        } else {
            $sql_stts = "SELECT * FROM parking_slot WHERE id = '{$book_slot}'";
            $result_stts = $conn->query($sql_stts);
            if ($result_stts->num_rows > 0) {

                $sql_sttsalready = "SELECT * FROM activity WHERE uid = '{$logged_user}' and booked = 1";
                $result_sttsalready = $conn->query($sql_sttsalready);
                if ($result_sttsalready->num_rows > 0) {
                    while ($row_sttsalready = $result_sttsalready->fetch_assoc()) {
                        $pid = $row_sttsalready["pid"];
                        if ($pid == $book_slot) {
                            $delsql = "DELETE FROM activity WHERE uid = '{$logged_user}' and pid = '{$book_slot}' and booked = 1";
                            if ($conn->query($delsql) === TRUE) {
                                $updatesql = "UPDATE parking_slot SET p_status = 0 WHERE id = {$book_slot}";
                                $conn->query($updatesql);
                                if ($conn->query($updatesql) === TRUE) {
                                    $_SESSION['slot_already_deleted'] = $book_slot;
                                    echo "<script>window.history.back();</script>";
                                }
                            }
                        } else {
                            $_SESSION['cant_book_more_than_one_slot'] = 1;
                            echo "<script>window.history.back();</script>";
                        }
                    }
                } else {
                    while ($row_stts = $result_stts->fetch_assoc()) {
                        $status = $row_stts["status"];
                        if ($status == 1) {
                            $sql = "INSERT INTO activity (uid,pid, i_date,i_time,booked)
                VALUES ({$logged_user}, {$book_slot}, '{$live_date}', '{$live_time}',1)";

                            if ($conn->query($sql) === TRUE) {
                                $updatesql = "UPDATE parking_slot SET p_status = 1 WHERE id = {$book_slot}";
                                $conn->query($updatesql);
                                if ($conn->query($updatesql) === TRUE) {
                                    $_SESSION['slot_booked'] = $book_slot;
                                    echo "<script>window.history.back();</script>";
                                }
                            }
                        } else {
                            $_SESSION['slot_cant_booked'] = $book_slot;
                            echo "<script>window.history.back();</script>";
                        }
                    }
                }
            }
        }
    }

    //cancel booking
    if (isset($_GET["cancel_book_id"])) {
        $cookie_name = "user";
        $logged_user = $_COOKIE[$cookie_name];
        $book_slot = $_GET["cancel_book_id"];
        $select_sql = "SELECT * FROM activity WHERE uid = {$logged_user} and id = {$book_slot} and booked = 1";
        $result_select = $conn->query($select_sql);
        if ($result_select->num_rows > 0) {
            while ($row_select = $result_select->fetch_assoc()) {
                $pid = $row_select["pid"];
            }
            $delsql = "DELETE FROM activity WHERE uid = '{$logged_user}' and id = '{$book_slot}' and booked = 1";
            if ($conn->query($delsql) === TRUE) {
                $updatesql = "UPDATE parking_slot SET p_status = 0 WHERE id = {$pid}";
                $conn->query($updatesql);
                if ($conn->query($updatesql) === TRUE) {
                    $_SESSION['slot_already_deleted'] = $book_slot;
                    echo "<script>window.history.back();</script>";
                }
            }
        }
    }

    //cancel booking from admin
    if (isset($_GET["remove_booking"])) {
        $book_slot = $_GET["remove_booking"];
        $select_sql = "SELECT * FROM activity WHERE id = {$book_slot} and booked = 1";
        $result_select = $conn->query($select_sql);
        if ($result_select->num_rows > 0) {
            while ($row_select = $result_select->fetch_assoc()) {
                $pid = $row_select["pid"];
            }
            $delsql = "DELETE FROM activity WHERE id = '{$book_slot}' and booked = 1";
            if ($conn->query($delsql) === TRUE) {
                $updatesql = "UPDATE parking_slot SET p_status = 0 WHERE id = {$pid}";
                $conn->query($updatesql);
                if ($conn->query($updatesql) === TRUE) {
                    $_SESSION['slot_already_deleted'] = $book_slot;
                    header("Location:./admin/view_booking.php");
                }
            }
        }
    }

    //Change user Status
    if (isset($_GET["change_user_status"])) {
        $change_status = $_GET["change_user_status"];
        $sql_stts = "SELECT * FROM users WHERE id = '{$change_status}'";
        $result_stts = $conn->query($sql_stts);
        if ($result_stts->num_rows > 0) {
            while ($row_stts = $result_stts->fetch_assoc()) {
                $status = $row_stts["status"];
                if ($status == 1) {
                    $status = 0;
                } else {
                    $status = 1;
                }
                $sql = "UPDATE users SET status = {$status} WHERE id = {$change_status}";
                if ($conn->query($sql) === TRUE) {
                    $_SESSION['change_status'] = 1;
                    header("Location:./admin/user_details.php");
                }
            }
        }
    }


    //Change profile photo
    if (isset($_POST["update_pro_pic"])) {
        $cookie_name = "user";
        $logged_user = $_COOKIE[$cookie_name];
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $profile_pic = $_FILES['profile_pic']['name'];

            $sql = "UPDATE users SET pro_pic = '{$profile_pic}' WHERE id = {$logged_user}";
            if ($conn->query($sql) === TRUE) {

                if (!file_exists('includes/img/pro_pic')) {
                    mkdir('includes/img/pro_pic', 0777, true);
                }
                $target_dir = "includes/img/pro_pic/";
                $target_file = $target_dir . basename($profile_pic);
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
                    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
                }
                $_SESSION['change_pro_pic'] = 1;
                echo "<script>window.history.back();</script>";
            }
        }
    }

    //Admin Login
    if (isset($_POST["admin_login"])) {
        $username = $_POST["username"];
        $pwd = $_POST["pwd"];

        $sql_user = "SELECT * FROM users WHERE email = '{$username}' and u_type = 1 and status = 1";
        $result_user = $conn->query($sql_user);
        if ($result_user->num_rows > 0) {
            while ($row_user = $result_user->fetch_assoc()) {
                $hash = $row_user["pwd"];
                $uid = $row_user["id"];
            }

            if (password_verify($pwd, hash: $hash)) {
                $cookie_name = "admin";
                $cookie_value = "Admin";
                setcookie($cookie_name, $cookie_value, time() + (3600), "/"); // 3600 = 1 hour
            } else {
                $_SESSION['wrong_pwd'] = 1;
            }
        } else {
            $_SESSION['wrong_user'] = 1;
        }

        header("Location:./admin");
    }


    //Park
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the value from the input field
        if (isset($_POST['entrance_exit_input'])) {
            $inputValue = $_POST['entrance_exit_input'];

            // Trim the value to remove extra spaces
            $inputValue = trim($inputValue);

            // Display the input value (for testing purposes)
            $entrance_exit_input = htmlspecialchars($inputValue);
            $entrance_exit_input = substr($entrance_exit_input, 5);

            $sql_check = "SELECT * FROM users WHERE id = '{$entrance_exit_input}'";
            $result_check = $conn->query($sql_check);
            if ($result_check->num_rows > 0) {

                $live_date = date('Y-m-d');
                $live_time = date('H:i:s');

                $sqli = "SELECT * FROM activity WHERE uid = {$entrance_exit_input} and o_date = ''";
                $resulti = $conn->query($sqli);
                if ($resulti->num_rows > 0) {
                    while ($rowi = $resulti->fetch_assoc()) {
                        $id = $rowi["id"];
                        $uid = $rowi["uid"];
                        $i_date = $rowi["i_date"];
                        $i_time = $rowi["i_time"];
                        $pid = $rowi["pid"];
                        $is_booked = $rowi["booked"];

                        $inTime = "{$i_date} {$i_time}";
                        $outTime = "{$live_date} {$live_time}";
                        $timeDifference = calculateTimeDifference($inTime, $outTime);
                        $timeDifference = intval($timeDifference);

                        if ($timeDifference < 60) {
                            $timeDifference = 60;
                        }

                        $price = $timeDifference / 60 * 100;
                        if ($is_booked != 1) {
                            $sql = "UPDATE activity SET o_date = '{$live_date}' , o_time = '{$live_time}' ,price = {$price} ,booked = 0
                        WHERE id = {$id}";
                            if ($conn->query($sql) === TRUE) {

                                $checksql = "SELECT * FROM activity WHERE pid = {$pid} and o_date = ''";
                                $result_check = $conn->query($checksql);
                                if ($result_check->num_rows > 0) {
                                    while ($row_check = $result_check->fetch_assoc()) {
                                        $uid = $row_check["uid"];
                                        $o_date = $row_check["o_date"];
                                        $o_time = $row_check["o_time"];
                                        $price = $row_check["price"];
                                        // Process the checkout information as needed
                                    }
                                } else {
                                    $updatesql = "UPDATE parking_slot SET p_status = 0 WHERE id = {$pid}";
                                    $conn->query($updatesql);
                                }

                                //$_SESSION['user_exit'] = 1;
                                // echo "
                                //     <script>
                                //         paymentGateWay()
                                //     </script>
                                // ";
                            }
                        } else {
                            $sql = "UPDATE activity SET booked = 0 WHERE uid = {$entrance_exit_input} and o_date = '' and booked = 1";
                            if ($conn->query($sql) === TRUE) {
                                $_SESSION['user_parked'] = 1;
                            }
                        }
                        header("Location:./admin/entrance_exitc.php");
                    }
                } else {

                    $sql_parking_slot = "SELECT * FROM parking_slot WHERE status = 1";
                    $result_parking_slot = $conn->query($sql_parking_slot);
                    if ($result_parking_slot->num_rows > 0) {
                        while ($row_parking_slot = $result_parking_slot->fetch_assoc()) {
                            $pid = $row_parking_slot["id"];
                            $p_status = $row_parking_slot["p_status"];
                            if ($p_status == 0) {
                                $p_slot_id = $pid;
                            } else {
                                $sql_is_i_activity = "SELECT * FROM activity WHERE pid = {$pid} and o_date = '' and booked = 1";
                                $result_is_i_activity = $conn->query($sql_is_i_activity);
                                if ($result_is_i_activity->num_rows > 0) {
                                    while ($row_is_i_activity = $result_is_i_activity->fetch_assoc()) {
                                        $i_date = $row_is_i_activity["i_date"];
                                        $i_time = $row_is_i_activity["i_time"];
                                        $inTime = "{$i_date} {$i_time}";
                                        $liveTime = "{$live_date} {$live_time}";
                                        $timeDifference = calculateTimeDifference($inTime, $liveTime);

                                        //if ($timeDifference > 60) {
                                        //$p_slot_id = $pid;
                                        $sql_parking_slot2 = "SELECT * FROM parking_slot WHERE id = {$pid}";
                                        $result_parking_slot2 = $conn->query($sql_parking_slot2);
                                        if ($result_parking_slot2->num_rows > 0) {
                                            while ($row_parking_slot2 = $result_parking_slot2->fetch_assoc()) {
                                                $p_status2 = $row_parking_slot2["p_status"];
                                                if ($p_status2 == 0) {
                                                    $p_slot_id = $pid;

                                                    // if ($timeDifference > 60) {
                                                    //     $p_slot_id = $pid;
                                                    // }
                                                }
                                            }
                                        }
                                        //}
                                    }
                                }
                            }
                            if (isset($p_slot_id) && $p_slot_id != 0) {
                                // echo $p_slot_id;
                                // die();
                                $sql = "INSERT INTO activity (uid,pid, i_date,i_time)
                    VALUES ({$entrance_exit_input},{$p_slot_id}, '{$live_date}', '{$live_time}')";
                                if ($conn->query($sql) === TRUE) {
                                    $parking = "UPDATE parking_slot SET p_status = 1 WHERE id = {$p_slot_id}";
                                    if ($conn->query($parking) === TRUE) {
                                        $_SESSION['user_parked'] = 1;
                                        header("Location:./admin/entrance_exitc.php");
                                    }

                                    // $_SESSION['user_parked'] = 1;
                                    // header("Location:./admin/entrance_exitc.php");
                                }
                                break;
                            }
                        }
                    }
                }
            } else {
                $_SESSION['wrong_user'] = 1;
                header("Location:./admin/entrance_exitc.php");
            }
        }
    }




    function calculateTimeDifference($inTime, $outTime)
    {
        $inDateTime = new DateTime($inTime);
        $outDateTime = new DateTime($outTime);

        // Calculate difference
        $interval = $inDateTime->diff($outDateTime);

        // Convert to total minutes
        $totalMinutes = ($interval->h * 60) + $interval->i;

        return $totalMinutes;
    }
    ?>

</body>