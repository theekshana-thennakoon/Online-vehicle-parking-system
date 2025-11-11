<?php
session_start();
$cookie_name = "admin";
if (isset($_COOKIE[$cookie_name])) {
    header("Location:./");
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

    <title>Login - Smart Parking</title>

    <!-- Custom fonts for this template-->
    <link href="../includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../includes/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-dark">
    <script src="../includes/sweetalert.js"></script>
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="https://cdn.pixabay.com/photo/2021/11/13/19/28/cars-6792173_640.jpg" alt="">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-dark mb-4">
                                            <i class="fas fa-car"></i>
                                            Smart Parking
                                        </h1>
                                        <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Admin Login</h1>
                                    </div>
                                    <form class="user" action="../x.php" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputText" aria-describedby="emailHelp"
                                                placeholder="Enter Username" name="username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" name="pwd">
                                        </div>
                                        <button type="submit" name="admin_login" class="btn btn-dark btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
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
    <?php
    if (isset($_SESSION['wrong_user'])) {
        echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Enter Correct Admin Email Address!',
                    }).then(() => {
                        //window.history.back(); // Navigate back to the previous page
                    });
                </script>";
        unset($_SESSION["wrong_user"]);
    }

    if (isset($_SESSION['wrong_pwd'])) {
        echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Enter Correct Admin Password!',
                    }).then(() => {
                        //window.history.back(); // Navigate back to the previous page
                    });
                </script>";
        unset($_SESSION["wrong_pwd"]);
    }
    ?>
</body>

</html>