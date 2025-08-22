<?php

$orderData = [
    'order_id'    => uniqid('', true),
    'buyer_email' => 'customer@example.com',
    'buyer_name'  => 'John Doe',
    'buyer_phone' => '0744963858', // Tanzanian format
    'amount'      => 1000,         // Amount in TZS
];

$ch = curl_init('https://api.zenopay.com/mobile_money_tanzania'); // Replace with ZP_BASE_URL
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'x-api-key: YOUR_API_KEY', // Replace with your key
    ],
    CURLOPT_POSTFIELDS     => json_encode($orderData),
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!empty($data['status']) && $data['status'] === 'success') {
    echo "✅ Order created! Order ID: {$data['order_id']}\n";
} else {
    echo "❌ Error: " . ($data['message'] ?? 'Unknown error') . "\n";
}
