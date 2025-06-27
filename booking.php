<?php
include("./includes/database.php");
session_start();
if (!isset($_SESSION['logged_user'])) {
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

    <title> Book a parking slot - Speed Parking</title>

    <!-- Custom fonts for this template-->
    <link href="./includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./includes/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <script src="./includes/sweetalert.js"></script>
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
                        <h1 class="h3 mb-0 text-gray-800 text-center">Book a parking slot</h1>
                    </div>

                    <div class="row" id="parking-slots-row">
                        <!-- slots display here -->

                    </div>

                    <!-- <form action="./x.php" method="post">
                        <div class="form-group mt-3">
                            <label for="exampleFormControlSelect1">Select parking slot to book</label>
                            <select class="form-control" id="exampleFormControlSelect1">
                                <?php
                                $sqlp = "SELECT * FROM parking_slot";
                                $resultp = $conn->query($sqlp);

                                if ($resultp->num_rows > 0) {
                                    while ($rowp = $resultp->fetch_assoc()) {
                                        $ps_id = $rowp["id"];
                                        $psname = $rowp["name"];
                                        $status = $rowp["status"];
                                        if ($status == 0) {
                                            $stts = "disabled";
                                            $stts_color = "style = color:#ddd;";
                                        } else {
                                            $stts = "";
                                            $stts_color = "";
                                        }
                                        echo "<option value = '{$ps_id}' $stts $stts_color>Slot {$psname}</option>";
                                    }
                                }
                                ?>

                            </select>
                            <button type="submit" class="btn btn-primary my-3 float-right">Book Now</button>
                        </div>
                    </form> -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white mt-5">
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

        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Select "Yes" below if you are sure that you are booking that parking slot.
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a id="confirmBooking" class="btn btn-primary">Yes</a>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function setSlotId(slotId) {
                // Update the href attribute of the "Yes" button in the modal
                const confirmButton = document.getElementById('confirmBooking');
                confirmButton.href = `./x.php?book_slot=${slotId}`;
            }
        </script>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap core JavaScript-->
        <script src="./includes/vendor/jquery/jquery.min.js"></script>
        <script src="./includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="./includes/vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="./includes/js/sb-admin-2.min.js"></script>

        <script>
            // Funct refresh the content inside the row div
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

        <?php
        if (isset($_SESSION['slot_booked'])) {
            $slot_booked = $_SESSION['slot_booked'];
            echo "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Successfully booked Parking slot {$slot_booked}!',
                    }).then(() => {
                        //window.history.back(); // Navigate back to the previous page
                    });
                </script>";
            unset($_SESSION["slot_booked"]);
        }

        if (isset($_SESSION['slot_cant_booked'])) {
            $slot_cant_booked = $_SESSION['slot_cant_booked'];
            echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'You cant book Parking slot {$slot_cant_booked} because it is disabled!',
                    }).then(() => {
                        //window.history.back(); // Navigate back to the previous page
                    });
                </script>";
            unset($_SESSION["slot_cant_booked"]);
        }
        ?>
</body>

</html>