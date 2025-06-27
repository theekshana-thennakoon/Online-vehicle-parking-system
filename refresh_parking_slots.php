<?php
include("./includes/database.php");
session_start();

$sql = "SELECT * FROM parking_slot";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ps_id = $row["id"];
        $ps_name = $row["name"];
        $ps_status = $row["status"];
        if ($ps_status == 0) {
            $stts = "disabled";
            $ps_color = "#aaa";
        } else {
            $stts = "";
            $sqli = "SELECT * FROM activity_slot WHERE pid = {$ps_id}";
            $resulti = $conn->query($sqli);
            if ($resulti->num_rows > 0) {
                while ($rowi = $resulti->fetch_assoc()) {
                    $sqli = "SELECT * FROM activity_slot WHERE pid = {$ps_id} and o_date = ''";
                    $resulti = $conn->query($sqli);
                    if ($resulti->num_rows > 0) {
                        while ($rowi = $resulti->fetch_assoc()) {
                            $uid = $rowi["uid"];
                            if ($_SESSION['logged_user'] == $uid) {
                                $ps_color = "#5382E4";
                            } else {
                                $ps_color = "red";
                            }
                            $stts = "disabled";
                        }
                    } else {
                        $ps_color = "green";
                    }
                }
            } else {
                $ps_color = "green";
            }
        }

        echo "
        <div class=\"col-3 mb-4\">
            <div class=\"card shadow h-100 py-2\" style=\"border: 2px solid {$ps_color}; background: {$ps_color};\" $stts>
                <a class=\"\" href=\"#\" data-toggle=\"modal\" data-target=\"#logoutModal\" 
                onclick=\"setSlotId('{$ps_id}')\">
                    <div class=\"card-body\">
                        <div class=\"row no-gutters align-items-center\">
                            <div class=\"col mr-2 text-center\">
                                <div class=\"h6 mb-0 font-weight-bold text-center text-white\">{$ps_name}</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        ";
    }
}
?>