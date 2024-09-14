<?php

// URL of the API endpoint
$url = "https://api.zeno.africa";

// Data to send for creating the order 
$orderData = [
    'create_order' => 1,
    'buyer_email' => 'CUSTOMER_EMAIL',
    'buyer_name' => 'CUSTOMER_NAME',
    'buyer_phone' => 'CUSTOMER_PHONE_NUMBER',
    'amount' => 10000, #AMOUNT_TO_BE_PAID
    'account_id' => 'YOUR_ACCOUNT_ID', 
    'api_key' => 'YOUR API ', 
    'secret_key' => 'YOUR SECRET KEY'
];

// Initialize cURL session for creating the order
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($orderData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch); 

echo ($response); 

curl_close($ch); 


function logError($message) 
{
    // Function to log errors
    file_put_contents('error_log.txt', $message . "\n", FILE_APPEND);
}
