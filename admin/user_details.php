<?php
session_start();
$cookie_name = "admin";
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

    <title>User Details</title>

    <!-- Custom fonts for this template -->
    <link href="../includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../includes/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../includes/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <script src="../includes/sweetalert.js"></script>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("../includes/sidebar.php"); ?>
        <!-- End of Sidebar -->

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
                    <h1 class="h3 mb-2 text-gray-800">User Details</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">Details of Users</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>NIC</th>
                                            <th>Contact no</th>
                                            <th>Status</th>
                                            <th>Change Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>NIC</th>
                                            <th>Contact no</th>
                                            <th>Status</th>
                                            <th>Change Status</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $sql = "SELECT * FROM users WHERE email != 'admin@mail.com'";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $ps_id = $row["id"];
                                                $ps_fname = $row["fname"];
                                                $ps_lname = $row["lname"];
                                                $nic = $row["nic"];
                                                $phone = $row["phone"];
                                                $status = $row["status"];
                                                if ($status == 1) {
                                                    $status = "Enable";
                                                } else {
                                                    $status = "Disable";
                                                }


                                                echo "
                                                    <tr>
                                                        <td><h1 class=\"h6\">{$ps_fname}</h1></td>
                                                        <td><h1 class=\"h6\">{$ps_lname}</h1></td>
                                                        <td><h1 class=\"h6\">{$nic}</h1></td>
                                                        <td><h1 class=\"h6\">0{$phone}</h1></td>
                                                        <td><h1 class=\"h6\">{$status}</h1></td>
                                                        <td><a href = '../x.php?change_user_status={$ps_id}' class = 'btn btn-dark'>Change Status</a></td>
                                                    </tr>
                                                    ";
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
    <?php
    if (isset($_SESSION['change_status'])) {
        echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Done...',
                            text: 'Successfully changed the User status!',
                        }).then(() => {
                            //window.history.back(); 
                        });
                    </script>";
        unset($_SESSION["change_status"]);
    }
    ?>
</body>

</html>