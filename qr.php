<?php
include("./includes/database.php");
session_start();
$cookie_name = "user";
if (!isset($_COOKIE[$cookie_name])) {
    header("Location:./login.php");
} else {
    $logged_user = $_COOKIE[$cookie_name];
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

    <title>QR Codes - Smart Parking</title>

    <!-- Custom fonts for this template-->
    <link href="./includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./includes/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- CSS to disable text selection and context menu -->
    <style>
        body {
            -webkit-touch-callout: none;
            /* iOS Safari */
            -webkit-user-select: none;
            /* Safari */
            -khtml-user-select: none;
            /* Konqueror HTML */
            -moz-user-select: none;
            /* Firefox */
            -ms-user-select: none;
            /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently supported by Chrome and Opera */
        }

        /* Disable image dragging */
        img {
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
        }
    </style>

</head>

<body id="page-top" oncontextmenu="return false;">

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
                    <!-- <div class="row" id="show-payment-method-row">


                    </div> -->
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 text-center">Your QR Code</h1>
                    </div>
                    <center>

                        <img src='<?php echo "includes/img/qr/ps_00{$content}.png"; ?>' alt="QR Code"
                            style="border:3px solid #696D77;">
                        <h1 class="h4 mt-3 mb-0 text-dark font-weight-bold text-center">
                            <?php echo "{$user_fname} {$user_lname}"; ?>
                        </h1>
                    </center>
                    <div class="mt-5 align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 text-center">Book parking slot</h1>
                    </div>
                    <div class="mt-3 align-items-center justify-content-between mb-4">
                        <h1 class="h6 mb-0 text-gray-800 text-center">
                            If you need to book the parking slot, you can book it click following button
                            <br><br>
                            <a href="./booking.php" class="btn btn-dark text-white">Book parking slot</a>
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
                        <span>Copyright &copy; The Smart Parking</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


    <div class="row" id="display-parking-slot-row">


    </div>

    <div class="row" id="show-payment-method-row">


    </div>


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
                    <a class="btn btn-dark" href="login.html">Logout</a>
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
        function refreshParkingSlots() {
            $.ajax({
                url: 'displaypatmentpopup.php', // A PHP file to fetch the updated data
                method: 'GET',
                success: function(data) {
                    $('#show-payment-method-row').html(data); // Replace the content inside the row div
                }
            });
        }

        // Set interval to refresh every 2 seconds
        setInterval(refreshParkingSlots, 2000);
    </script>


    <!-- JavaScript to disable right-click and long-press -->
    <script>
        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable long-press on mobile
        document.addEventListener('touchstart', function(e) {
            // Prevent long-press context menu on all elements
            if (e.touches && e.touches.length > 0) {
                // Set a timer to prevent the context menu
                var touch = e.touches[0];
                var startTime = new Date().getTime();

                var timer = setTimeout(function() {
                    // This will be called after a long press
                    e.preventDefault();
                    clearTimeout(timer);
                }, 800); // 800ms is typically when the context menu appears

                // Store the timer on the touch object so we can clear it if needed
                touch.timer = timer;
            }
        });

        document.addEventListener('touchend', function(e) {
            // Clear any pending long-press timers
            if (e.changedTouches && e.changedTouches.length > 0) {
                for (var i = 0; i < e.changedTouches.length; i++) {
                    var touch = e.changedTouches[i];
                    if (touch.timer) {
                        clearTimeout(touch.timer);
                    }
                }
            }
        });

        document.addEventListener('touchmove', function(e) {
            // Clear any pending long-press timers when user moves finger
            if (e.touches && e.touches.length > 0) {
                for (var i = 0; i < e.touches.length; i++) {
                    var touch = e.touches[i];
                    if (touch.timer) {
                        clearTimeout(touch.timer);
                    }
                }
            }
        });
    </script>



    <script>
        // Function to refresh the content inside the row div
        function displayParkingSlots() {
            $.ajax({
                url: 'display_slot_no.php', // A PHP file to fetch the updated data
                method: 'GET',
                success: function(data) {
                    $('#display-parking-slot-row').html(data); // Replace the content inside the row div
                }
            });
        }

        // Set interval to refresh every 2 seconds
        setInterval(displayParkingSlots, 2000);
    </script>


    <script src="./includes/vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./includes/vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>