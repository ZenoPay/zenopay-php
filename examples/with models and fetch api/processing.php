<?php
require_once 'zenopay.php'; // Adjust the path according to where the ZenoPay class is stored

use ZenoPay\ZenoPay;

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $buyer_name = isset($_POST['buyer_name']) ? $_POST['buyer_name'] : '';
    $buyer_email = isset($_POST['buyer_email']) ? $_POST['buyer_email'] : '';
    $buyer_phone = isset($_POST['buyer_phone']) ? $_POST['buyer_phone'] :'';
    $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;

    // Initialize the ZenoPay class with account details
    $account_id = 'zpXXXXX'; // replace this with your zenopay for business acount id
    $api_key = 'your_api_key';
    $secret_key = 'your_secret_key';
    $api_endpoint = 'https://api.zeno.africa'; // Replace with the actual API endpoint

    $zenoPay = new ZenoPay($account_id, $api_key, $secret_key, $api_endpoint);

    // Process the payment
    $response = $zenoPay->processPayment($buyer_email, $buyer_name, $buyer_phone, $amount);

    // Return the response as JSON
    header('Content-Type: application/json');

    if ($response['result'] === 'success') {
        echo json_encode(['result' => 'success', 'message' => $response['message']]);
    } else {
        echo json_encode(['result' => 'error', 'error' => $response['error']]);
    }
}
