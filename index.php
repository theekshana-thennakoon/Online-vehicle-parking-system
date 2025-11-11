<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>
    <?php
    $cookie_name = "user";
    if (!isset($_COOKIE[$cookie_name])) {
        echo "not set";
        header("Location:./login.php");
    } else {
        header("Location:./slots.php");
    }
    ?>
</body>

</html>