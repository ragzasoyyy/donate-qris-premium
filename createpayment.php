<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Konfigurasi
$apikey = "Mjf954zLrgGxVl9MJ7suUaPNdP8ZuQLp32qp7rh79wcPMlXiRD8e5d1Ye1kvCj8FVcmpZpCxhDyQOWnNvk4sj9L1ZX0efP5D7Yn2";
$amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;

if ($amount < 1000) {
    echo json_encode(["success" => false, "message" => "Jumlah minimal adalah Rp1.000"]);
    exit;
}

$merchant_ref = "DONASI-" . time();
$expired_time = time() + (24 * 60 * 60); // 24 jam
$signature = hash('sha256', $merchant_ref . $amount . $apikey);

$data = [
    "amount" => $amount,
    "method" => "qris",
    "merchant_ref" => $merchant_ref,
    "customer_name" => "Donatur Umum",
    "customer_email" => "donatur@example.com",
    "customer_phone" => "081234567890",
    "order_items" => [
        [
            "sku" => "donasi",
            "name" => "Donasi QRIS",
            "price" => $amount,
            "quantity" => 1
        ]
    ],
    "expired_time" => $expired_time,
    "signature" => $signature
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://atlantich2h.com/deposit/instant-deposit",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: $apikey"
    ],
]);

$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

if ($response) {
    echo $response;
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal koneksi ke Atlantic",
        "error" => $error
    ]);
}
