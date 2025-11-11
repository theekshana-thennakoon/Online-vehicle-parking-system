<?php
session_start();
include("./includes/database.php");

$cookie_name = "user";
$logged_user = $_COOKIE[$cookie_name] ?? null;

if (!$logged_user) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$sql = "SELECT * FROM activity 
        WHERE uid = {$logged_user} 
          AND i_date != '' 
          AND o_date != '' 
          AND price != 0 
          AND paid = 0 
        LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $amount = (float)$row["price"];
} else {
    $amount = 0;
}

// === PayHere Config ===
$merchant_id = "1229520"; // Sandbox Merchant ID
$merchant_secret = "MzE4MTgxNTQ1NjMwNjI4NTcwNTEzNDY4MTIxNDQwMjI3NzAwMjQ2Nw=="; // Plaintext secret key, not Base64
$order_id = uniqid("ORD_");
$currency = "LKR";

// === Generate Hash ===
$hash = strtoupper(
    md5(
        $merchant_id .
            $order_id .
            number_format($amount, 2, '.', '') .
            $currency .
            strtoupper(md5($merchant_secret))
    )
);

// === Return JSON ===
$data = [
    "sandbox" => true,
    "merchant_id" => $merchant_id,
    "order_id" => $order_id,
    "items" => "Vehical parking fee",
    "amount" => number_format($amount, 2, '.', ''),
    "currency" => $currency,
    "hash" => $hash,
    "first_name" => "Theekshana",
    "last_name" => "Thennakoon",
    "email" => "theekshanathennakoonict@gmail.com",
    "phone" => "0771234567",
    "address" => "No.1, Galle Road",
    "city" => "Colombo"
];

header('Content-Type: application/json');
echo json_encode($data);
