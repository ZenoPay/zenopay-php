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

// Build the query string from the data array
$queryString = http_build_query($orderData);

// Create a context for the HTTP request
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $queryString,
    ],
];
$context = stream_context_create($options);

// Perform the POST request
$response = file_get_contents($url, false, $context);

// Check if the request was successful
if ($response === FALSE) {
    logError("Error: Unable to connect to the API endpoint.");
}

// Output the response
echo $response;

// Function to log errors
function logError($message)
{
    // Function to log errors
    file_put_contents('error_log.txt', $message . "\n", FILE_APPEND);
}

?>
