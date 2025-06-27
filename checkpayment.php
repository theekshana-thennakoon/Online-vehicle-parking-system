<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <script src="script.js"></script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

    <?php
    include("./includes/database.php");
    session_start();

    $logged_user = $_SESSION['logged_user'];
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

            // echo "
    
            //     <script>
            //         paymentGateWay()
            //     </script>
            // ";
        }
    }
    ?>
</body>

</html>