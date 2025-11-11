<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Entrance & Exit</title>

    <!-- Custom fonts for this template -->
    <link href="../includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../includes/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../includes/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-database-compat.js"></script>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include("./topbar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Entrance & Exit Dashboard</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form class="user" action="../x.php" method="post" id="qrForm">
                                <input type="text" class="form-control form-control-user" id="exampleInputPassword"
                                    placeholder="Scan your QR code" name="entrance_exit_input" autofocus>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include("../includes/footer.php"); ?>
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
                    <a class="btn btn-dark" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../includes/vendor/jquery/jquery.min.js"></script>
    <script src="../includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../includes/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../includes/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../includes/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../includes/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../includes/js/demo/datatables-demo.js"></script>

    <!-- SweetAlert -->
    <script src="../includes/sweetalert.js"></script>

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

        // QR code input handler
        document.getElementById('exampleInputPassword').addEventListener('input', function() {
            const inputValue = this.value.trim();
            if (inputValue) {
                document.getElementById('qrForm').submit();
            }
        });

        // Handle session messages
        $(document).ready(function() {
            <?php if (isset($_SESSION['user_parked'])): ?>
                updateGateStatus(1);
                Swal.fire({
                    icon: 'success',
                    title: 'Parked',
                    text: 'Successfully parked!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                <?php unset($_SESSION['user_parked']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_exit'])): ?>
                updateGateStatus(1);
                Swal.fire({
                    icon: 'success',
                    title: 'Exit',
                    text: 'Successfully Exit!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                <?php
                unset($_SESSION["fname"]);
                unset($_SESSION["lname"]);
                unset($_SESSION["email"]);
                unset($_SESSION["amount"]);
                unset($_SESSION["user_exit"]);
                ?>
            <?php endif; ?>
        });
    </script>

    <?php
    if (isset($_SESSION['wrong_user'])) {
        echo "<script>
        Swal.fire({
        icon: 'error',
        title: 'Wrong user',
        text: 'You are not authorized to park here!',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
        });
        </script>";
        unset($_SESSION['wrong_user']);
    }
    ?>
</body>

</html>