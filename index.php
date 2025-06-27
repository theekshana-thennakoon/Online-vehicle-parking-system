<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>
    <?php
    if (!isset($_SESSION['logged_user'])) {
        header("Location:./login.php");
    }
    ?>
</body>

</html>