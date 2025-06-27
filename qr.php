<?php
include("./includes/database.php");
session_start();
if (!isset($_SESSION['logged_user'])) {
    header("Location:./login.php");
} else {
    $logged_user = $_SESSION['logged_user'];
    $content = $logged_user;
    $sql_user = "SELECT * FROM users WHERE id = '{$logged_user}'";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        while ($row_user = $result_user->fetch_assoc()) {
            $user_fname = $row_user["fname"];
            $user_lname = $row_user["lname"];
        }
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

    <title>QR Codes - Speed Parking</title>

    <!-- Custom fonts for this template-->
    <link href="./includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./includes/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        //include("sidebar.php");
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include("./includes/topbar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row" id="show-payment-method-row">


                    </div>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 text-center">Your QR Code</h1>
                    </div>
                    <center>

                        <img src='<?php echo "includes/img/qr/ps_00{$content}.png"; ?>' alt="QR Code"
                            style="border:3px solid blue;">
                        <h1 class="h4 mt-3 mb-0 text-primary font-weight-bold text-center">
                            <?php echo "{$user_fname} {$user_lname}"; ?>
                        </h1>
                    </center>
                    <div class="mt-5 d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 text-center">Book parking slot</h1>
                    </div>
                    <div class="mt-3 d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h6 mb-0 text-gray-800 text-center">
                            If you need to book the parking slot, you can book it click following button
                            <br><br>
                            <a href="./booking.php" class="btn btn-primary text-white">Book parking slot</a>
                        </h1>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; The Speed Parking</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
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
    <script src="script.js"></script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

    <script>
        // Function to refresh the content inside the row div
        function refreshCheckPayment() {
            $.ajax({
                url: 'checkpayment.php', // A PHP file to fetch the updated data
                method: 'GET',
                success: function(data) {
                    $('#show-payment-method-row').html(data); // Replace the content inside the row div
                }
            });
        }

        // Set interval to refresh every 2 seconds
        setInterval(refreshCheckPayment, 2000);
    </script>
</body>

</html>