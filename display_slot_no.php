<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet" />
    <title>Document</title>
</head>

<body>
    <!-- Custom scripts for all pages-->
    <script src="./includes/js/sb-admin-2.min.js"></script>

    <?php
    include("./includes/database.php");

    session_start();
    $cookie_name = "user";
    if (!isset($_COOKIE[$cookie_name])) {
        header("Location:./login.php");
    } else {
        $logged_user = $_COOKIE[$cookie_name];
    }

    $sql = "SELECT * FROM activity WHERE uid = {$logged_user}";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pid = $row["pid"];

            // echo "
            //         <script>
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Successfully',
            //                 text: 'You need to park your vehicle on slot {$pid}!',
            //                 showConfirmButton: false, // Removes the OK button
            //                 timer: 2000, // Alert will disappear after 2 seconds (2000ms)
            //                 timerProgressBar: true // Adds a progress bar to show the countdown
            //             });
            //         </script>";

            // $update_sql = "UPDATE activity SET display_status = 'Yes' WHERE uid = {$logged_user} AND pid = {$pid}";
            // if ($conn->query($update_sql) === TRUE) {
            //     // Successfully updated the display status

            // } else {
            //     echo "Error updating record: " . $conn->error;
            // }
        }
    }
    ?>
</body>

</html>