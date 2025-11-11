<?php
session_start();
include("./includes/database.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];
}

if (isset($_POST['verify_email'])) {
    $token = $_POST['token'];

    // Update the user's email verification status in the database
    $update_query = "UPDATE users SET status = 1 WHERE email = '{$token}'";
    if (mysqli_query($conn, $update_query)) {
        $uid_query = "SELECT id FROM users WHERE email = '{$token}'";
        $result = mysqli_query($conn, $uid_query);
        $row = mysqli_fetch_assoc($result);
        $uid = $row['id'];

        $cookie_name = "user";
        $cookie_value = $uid;
        setcookie($cookie_name, $cookie_value, time() + (3600), "/"); // 3600 = 1 hour
        header("Location:./slots.php");
        exit();
    } else {
        echo "<script>
            swal({
                title: 'Error!',
                text: 'There was an error verifying your email. Please try again.',
                icon: 'error',
            });
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Verify Email - Smart Parking</title>

    <!-- Custom fonts for this template-->
    <link href="./includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./includes/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-dark">
    <script src="./includes/sweetalert.js"></script>
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <img src="https://cdn.pixabay.com/photo/2021/11/13/19/28/cars-6792173_640.jpg" alt="">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-dark mb-4">
                                    <i class="fas fa-car"></i>
                                    Smart Parking
                                </h1>
                                <h1 class="h4 text-gray-900 mb-4">Verify Your Email</h1>
                            </div>
                            <form class="user" action="#" method="post">

                                <input type="hidden" name="token" value="<?php echo $token; ?>">
                                <button type="submit" name="verify_email" class="btn btn-success btn-user btn-block" id="submitBtn">
                                    Verify Email
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="./includes/vendor/jquery/jquery.min.js"></script>
    <script src="./includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./includes/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./includes/js/sb-admin-2.min.js"></script>
</body>

</html>