<?php
session_start();
include("./includes/database.php");

$activity_id = $_SESSION["activity_id"] ?? null;
$cookie_name = "user";
$logged_user = $_COOKIE[$cookie_name] ?? null;

if (!isset($_COOKIE[$cookie_name])) {
    header("Location:./login.php");
    exit;
}

// Fix: Fetch all data at once (your original bug fixed)
$get_email_sql = "SELECT email, fname, lname FROM users WHERE id = ?";
$stmt = $conn->prepare($get_email_sql);
$stmt->bind_param("i", $logged_user);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$email = $user_data['email'] ?? null;
$fname = $user_data['fname'] ?? null;
$lname = $user_data['lname'] ?? null;

$email_sent = false;

// Update database (with SQL injection protection)
if ($activity_id) {
    $sql = "UPDATE activity SET paid = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $activity_id);

    if ($stmt->execute()) {
        $_SESSION['user_exit'] = true;
        header("Location: ./slots.php");
    } else {
        error_log("Database update failed for activity_id: {$activity_id}");
    }
} else {
    echo "<script>alert('Invalid payment data.');</script>";
}
