<?php

$orderId = '3rer407fe-3ee8-4525-456f-ccb95de38-H'; // Replace with your order_id

$ch = curl_init("https://zenoapi.com/api/payments/order-status?order_id={$orderId}");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'x-api-key: YOUR_API_KEY', // Replace with your key
    ],
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!empty($data['resultcode']) && $data['resultcode'] === '000') {
    $order = $data['data'][0];
    echo "✅ Order Status: {$order['payment_status']}\n";
    echo "Amount: {$order['amount']}\n";
    echo "Transaction ID: {$order['transid']}\n";
    echo "Reference: {$order['reference']}\n";
} else {
    echo "❌ Error: " . ($data['message'] ?? 'Unknown error') . "\n";
}
