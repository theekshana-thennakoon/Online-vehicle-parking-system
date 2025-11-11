<?php
include("../includes/database.php");
if (!isset($_SESSION['logged_user'])) {
    //header("Location:./login.php");
} else {
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Topbar Search -->
        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <div class="sidebar-brand-icon h3 mt-3">
                    <i class="fas fa-car text-dark text-bold"></i>
                </div>
                <div class="h3 sidebar-brand-text text-dark text-bold mx-3 mt-3 mr-5">Smart Parking</div>
            </div>
        </form>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle " aria-haspopup="true">
                    <div class="sidebar-brand-icon h3 mt-3">
                        <i class="fas fa-car text-dark text-bold"></i>
                    </div>
                    <div class="h3 sidebar-brand-text text-dark text-bold mx-3 mt-3 mr-5">Smart Parking</div>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                    aria-labelledby="searchDropdown">

                </div>
            </li>

            <!-- Nav Item - Messages -->

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo "Admin"; ?></span>
                    <img class="img-profile rounded-circle" src="../includes/img/user_logo.png">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="../x.php?logout_admin_id=1">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

        </ul>

    </nav>
</body>

</html>