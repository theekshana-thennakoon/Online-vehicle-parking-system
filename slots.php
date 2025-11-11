<?php
include("./includes/database.php");
session_start();
$cookie_name = "user";
$userExit = isset($_SESSION['user_exit']);
if (!isset($_COOKIE[$cookie_name])) {
    header("Location:./login.php");
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

    <title>Parking Slots - Smart Parking</title>

    <!-- Custom fonts for this template-->
    <link href="./includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./includes/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        a:hover {
            text-decoration: none;
        }
    </style>


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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 text-center">Parking Slots</h1>
                    </div>

                    <div class="row" id="parking-slots-row">


                    </div>

                    <div class="row" id="display-parking-slot-row">


                    </div>

                    <!-- <div class="row" id="show-payment-method-row">


                    </div> -->

                    <div class="my-3">

                        <div class="row">
                            <div class="col">
                                <i class="fa fa-square text-success" aria-hidden="true">
                                    Available Slots
                                </i>
                            </div>
                            <div class="col">
                                <i class="fa fa-square text-primary" aria-hidden="true">
                                    Your Booked Slots
                                </i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="fa fa-square text-danger" aria-hidden="true">
                                    You / Other Parked Slots
                                </i>
                            </div>
                            <div class="col">
                                <i class="fa fa-square text-secondary" aria-hidden="true">
                                    Disabled Slots
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-center mb-4">
                        <a href="./qr.php" class="btn btn-dark text-center text-white">
                            <h1 class="h5 px-5 py-2 mb-0 text-center">Park Me / Exit me</h1>
                        </a>
                    </div>

                    <hr>

                    <div class="mt-5 align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 text-center">Book parking slot</h1>
                        <h1 class="h6 mb-0 text-gray-800 text-center">
                            If you need to book the parking slot, you can book it click following button
                            <br><br>
                            <a href="./booking.php" class="btn btn-dark text-white px-4 py-3">Book parking slot</a>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="./includes/vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./includes/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./includes/js/sb-admin-2.min.js"></script>
    <script>
        // Function to refresh the content inside the row div
        function refreshParkingSlots() {
            $.ajax({
                url: 'refresh_parking_slots.php', // A PHP file to fetch the updated data
                method: 'GET',
                success: function(data) {
                    $('#parking-slots-row').html(data); // Replace the content inside the row div
                }
            });
        }

        // Set interval to refresh every 2 seconds
        setInterval(refreshParkingSlots, 2000);
    </script>
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
        setInterval(displayParkingSlots, 3000);
    </script>


    <!-- SweetAlert -->
    <script src="./includes/sweetalert.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-database-compat.js"></script>

    <!-- Firebase Initialization -->
    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCV2TegSk-r8seMnc4KmBJxDCnreX9yLBw",
            authDomain: "parking-gate-d54d5.firebaseapp.com",
            databaseURL: "https://parking-gate-d54d5-default-rtdb.firebaseio.com",
            projectId: "parking-gate-d54d5",
            storageBucket: "parking-gate-d54d5.firebasestorage.app",
            messagingSenderId: "650184790827",
            appId: "1:650184790827:web:941dec87e5819509ffeb6e",
            measurementId: "G-FFNZ3S80Z8"
        };

        // Initialize Firebase
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const database = firebase.database();

        // Gate status update function
        function updateGateStatus(status) {
            firebase.database().ref('gate_status/current').set({
                status: status,
                timestamp: firebase.database.ServerValue.TIMESTAMP
            }).then(() => {
                console.log("Gate status updated to: " + status);
            }).catch(error => {
                console.error("Error updating gate status: ", error);
            });
        }

        // Handle session messages
        $(document).ready(function() {
            <?php if ($userExit): ?>
                updateGateStatus(1);
                Swal.fire({
                    icon: 'success',
                    title: 'Thank you',
                    text: 'Come Again!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                <?php
                // Remove session after updating gate status
                unset($_SESSION['user_exit']);
                ?>
            <?php endif; ?>
        });
    </script>

</body>

</html>