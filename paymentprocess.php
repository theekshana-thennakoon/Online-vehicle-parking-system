<?php
session_start();
$amount = 500;
$merchant_id = "1229520";
$merchant_secret = "MzE4MTgxNTQ1NjMwNjI4NTcwNTEzNDY4MTIxNDQwMjI3NzAwMjQ2Nw==";
$order_id = uniqid();
$currency = "LKR";

$hash = strtoupper(
    md5(
        $merchant_id .
        $order_id .
        number_format($amount, 2, '.', '') .
        $currency .
        strtoupper(md5($merchant_secret))
    )
);
//echo $hash;

$array = [];
$array["amount"] = $amount;
$array["merchant_id"] = $merchant_id;
$array["merchant_secret"] = $merchant_secret;
$array["order_id"] = $order_id;
$array["currency"] = $currency;
$array["items"] = "Vehical parking fee";
$array["hash"] = $hash;
$array["first_name"] = "Theekshana";
$array["last_name"] = "Thennakoon";
$array["email"] = "theekshanathennakoonict@gmail.com";
$array["phone"] = "0771234567";
$array["address"] = "No.1, Galle Road";
$array["city"] = "Colombo";

$jsonObj = json_encode($array);

//echo $hash;
echo $jsonObj;
?>