<?php
include("./includes/database.php");
session_start();
$cookie_name = "user";
if (!isset($_COOKIE[$cookie_name])) {
    header("Location:./login.php");
} else {
    $logged_user = $_COOKIE[$cookie_name];
    $sql_user = "SELECT * FROM users WHERE id = '{$logged_user}'";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        while ($row_user = $result_user->fetch_assoc()) {
            $user_id = $row_user["id"];
            $user_fname = $row_user["fname"];
            $user_lname = $row_user["lname"];
            $user_email = $row_user["email"];
            $user_nic = $row_user["nic"];
            $user_phone = $row_user["phone"];
            $pro_pic = $row_user["pro_pic"] ? "includes/img/pro_pic/{$row_user["pro_pic"]}" : "includes/img/user_logo.png";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="./includes/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./includes/css/sb-admin-2.min.css" rel="stylesheet">
    <title>Profile</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin-bottom: 15px;
        }

        .profile-body {
            padding: 25px;
        }

        .info-item {
            margin-bottom: 20px;
            text-align: center;
        }

        .info-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #343a40;
        }

        .logout-btn {
            background-color: #343a40;
            border: none;
            padding: 10px 30px;
            font-weight: 500;
            margin-top: 20px;
        }

        .user-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .app-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['change_pro_pic'])) {
        echo "<div class='alert alert-success'>Profile picture updated successfully!</div>";
        echo "<script>setTimeout(function(){ location.reload(); }, 1500);</script>";
        unset($_SESSION['change_pro_pic']);
    }
    ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="app-title">Smart Parking</div>
                        <img src="<?php echo $pro_pic; ?>" alt="Profile" class="profile-img">
                        <br>
                        <form action="./x.php" method="post" enctype="multipart/form-data">
                            <label class="btn btn-dark" for="profile_pic">Change Profile Picture</label>
                            <br>

                            <img id="preview_img" src="<?php echo $pro_pic; ?>" alt="Preview" class="profile-img mb-2" style="display:none;">
                            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="display: none;" required>
                            <script>
                                document.getElementById('profile_pic').addEventListener('change', function(event) {
                                    const [file] = event.target.files;
                                    if (file) {
                                        const preview = document.getElementById('preview_img');
                                        preview.src = URL.createObjectURL(file);
                                        preview.style.display = 'inline-block';
                                    }
                                });
                            </script>
                            <button type="submit" name="update_pro_pic" class="btn btn-dark">Upload</button>
                        </form>
                        <div class="user-name h4"><?php echo "{$user_fname} {$user_lname}"; ?></div>
                    </div>
                    <div class="profile-body">
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo $user_email; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">NIC</div>
                            <div class="info-value"><?php echo $user_nic; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">0<?php echo $user_phone; ?></div>
                        </div>
                        <!-- <div class="info-item">
                            <div class="info-label">Logs</div>
                            <div class="info-value">
                                <table style="width: 100%; border-collapse: collapse;border: 1px solid #ddd;">
                                    <tr>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Slot No</th>
                                    </tr>
                                    <?php

                                    $sql_logs = "SELECT * FROM activity WHERE uid = {$logged_user}";
                                    $result_logs = $conn->query($sql_logs);
                                    if ($result_logs->num_rows > 0) {
                                        while ($row_logs = $result_logs->fetch_assoc()) {
                                            echo "<tr>
                                                <td style='border: 1px solid #ddd;'>{$row_logs['i_date']} {$row_logs['i_time']}</td>
                                                <td style='border: 1px solid #ddd;'>{$row_logs['o_date']} {$row_logs['o_time']}</td>
                                                <td style='border: 1px solid #ddd;'>{$row_logs['pid']}</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>No logs found</td></tr>";
                                    }
                                    ?>


                                </table>
                            </div>
                        </div> -->
                        <div class="text-center">
                            <a href="./slots.php" class="btn btn-secondary logout-btn"><i class="fa fa-home" aria-hidden="true"></i> Go Home</a>
                            <a href="./x.php?logout_id=1" class="btn btn-dark logout-btn">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>