<?php
session_start();
include("./includes/database.php");
date_default_timezone_set('Asia/Colombo');

echo "
<script src=\"script.js\"></script>
<script type=\"text/javascript\" src=\"https://www.payhere.lk/lib/payhere.js\"></script>
";

//Register
if (isset($_POST["register"])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $nic = $_POST["nic"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $rpwd = $_POST["rpwd"];
    $sql = "SELECT * FROM users WHERE email = '{$email}'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['already_email'] = 1;
        echo "<script>window.history.back();</script>";
    } else {
        if ($pwd == $rpwd) {
            $hash = password_hash($pwd, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (fname, lname, nic,phone,email,pwd)
            VALUES ('{$fname}', '{$lname}', '{$nic}', {$phone}, '{$email}', '{$hash}')";

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
                $_SESSION['logged_user'] = $uid;
                header("Location:./slots.php");
            }
        } else {
            $_SESSION['password_not_match'] = 1;
            echo "<script>window.history.back();</script>";
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
            $_SESSION['logged_user'] = $uid;
            header("Location:./slots.php");
        } else {
            $_SESSION['wrong_pwd'] = 1;
            echo "<script>window.history.back();</script>";
        }
    } else {
        $_SESSION['wrong_user'] = 1;
        echo "<script>window.history.back();</script>";
    }
}

//Logout
if (isset($_GET["logout_id"])) {
    unset($_SESSION["logged_user"]);
    header("Location:./");
}

if (isset($_GET["logout_admin_id"])) {
    unset($_SESSION["logged_admin_user"]);
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

//Booking
if (isset($_GET["book_slot"])) {
    $book_slot = $_GET["book_slot"];
    $logged_user = $_SESSION['logged_user'];
    $live_date = date('Y-m-d');
    $live_time = date('H:i:s');

    $sql_stts = "SELECT * FROM parking_slot WHERE id = '{$book_slot}'";
    $result_stts = $conn->query($sql_stts);
    if ($result_stts->num_rows > 0) {
        while ($row_stts = $result_stts->fetch_assoc()) {
            $status = $row_stts["status"];
            if ($status == 1) {
                $sql = "INSERT INTO activity (uid, i_date,i_time,booked)
                VALUES ({$logged_user}, '{$live_date}', '{$live_time}',1)";

                if ($conn->query($sql) === TRUE) {
                    $sql = "INSERT INTO activity_slot (uid,pid, i_date)
                    VALUES ({$logged_user},{$book_slot}, '{$live_date}')";

                    if ($conn->query($sql) === TRUE) {
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

//Admin Login
if (isset($_POST["admin_login"])) {
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    if ($username == "admin") {
        if ($pwd == "Admin1@") {
            $_SESSION['logged_admin_user'] = "Admin";
            header("Location:./admin");
        } else {
            $_SESSION['wrong_pwd'] = 1;
            echo "<script>window.history.back();</script>";
        }
    } else {
        $_SESSION['wrong_user'] = 1;
        echo "<script>window.history.back();</script>";
    }
}


//Park
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the value from the input field
    $inputValue = isset($_POST['entrance_exit_input']) ? $_POST['entrance_exit_input'] : '';

    // Trim the value to remove extra spaces
    $inputValue = trim($inputValue);

    // Display the input value (for testing purposes)
    $entrance_exit_input = htmlspecialchars($inputValue);
    $entrance_exit_input = substr($entrance_exit_input, 5);
    $live_date = date('Y-m-d');
    $live_time = date('H:i:s');

    $sqli = "SELECT * FROM activity WHERE uid = {$entrance_exit_input} and o_date = ''";
    $resulti = $conn->query($sqli);
    if ($resulti->num_rows > 0) {
        while ($rowi = $resulti->fetch_assoc()) {
            $id = $rowi["id"];
            $i_date = $rowi["i_date"];
            $i_time = $rowi["i_time"];

            $inTime = "{$i_date} {$i_time}";
            $outTime = "{$live_date} {$live_time}";
            $timeDifference = calculateTimeDifference($inTime, $outTime);
            $timeDifference = intval($timeDifference);

            if ($timeDifference < 20) {
                $timeDifference = 20;
            }

            $price = $timeDifference + 20;
            $sql = "UPDATE activity SET o_date = '{$live_date}' , o_time = '{$live_time}' ,price = {$price}
            WHERE id = {$id}";
            if ($conn->query($sql) === TRUE) {

                $_SESSION['user_exit'] = 1;
                echo "
                    <script>
                        paymentGateWay()
                    </script>
                ";
                //echo "<script>window.history.back();</script>";
            }
        }
    } else {
        $sql = "INSERT INTO activity (uid, i_date,i_time)
        VALUES ({$entrance_exit_input}, '{$live_date}', '{$live_time}')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['user_parked'] = 1;
            echo "<script>window.history.back();</script>";
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
