<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        payment - Smart Parking
    </title>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <script src="script.js"></script>
</head>

<body>
    <?php
    include("./includes/database.php");
    session_start();

    $cookie_name = "user";
    $logged_user = $_COOKIE[$cookie_name] ?? null;

    if (!$logged_user) {
        echo "<p>User not logged in.</p>";
        exit;
    }

    $sql = "SELECT * FROM activity 
            WHERE uid = {$logged_user} 
              AND i_date != '' 
              AND o_date != '' 
              AND price != 0 
              AND paid = 0 
            LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["price"] = $row["price"];
        $_SESSION["activity_id"] = $row["id"];

        echo '<script>paymentGateWay();</script>';
    } else {
        echo "<p>No pending payments found.</p>";
    }
    ?>
</body>

</html>