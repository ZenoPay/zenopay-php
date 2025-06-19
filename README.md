# ZenoPay Order Creation and Status Check Scripts

This repository contains PHP scripts for interacting with the ZenoPay API. The scripts demonstrate how to create an order and check the status of an order.

## Table of Contents

- [Order Creation Script](#order-creation-script)
  - [Overview](#overview)
  - [Script Components](#script-components)
    - [API Endpoint](#api-endpoint)
    - [Order Data](#order-data)
    - [cURL Configuration](#curl-configuration)
    - [Error Logging Function](#error-logging-function)
  - [Error Handling](#error-handling)
  - [Example Usage](#example-usage)
  - [Notes](#notes)
- [Order Status Check Script](#order-status-check-script)
  - [Overview](#overview-1)
  - [Script Components](#script-components-1)
    - [API Endpoint](#api-endpoint-1)
    - [Post Data](#post-data)
    - [cURL Configuration](#curl-configuration-1)
  - [Error Handling](#error-handling-1)
  - [Example Usage](#example-usage-1)
  - [Notes](#notes-1)

## Order Creation Script

### Overview

This script sends a POST request to the ZenoPay API to create an order. It includes a basic error logging function to capture any issues during the request.

### Script Components

#### API Endpoint

- **URL**: `https://api.zeno.africa`

  This is the endpoint for creating an order.

#### Order Data

The following data is sent in the POST request:

```php
$url = "https://api.zeno.africa";

// Data to send for creating the order 
$orderData = [
    'buyer_email' => 'CUSTOMER_EMAIL',
    'buyer_name' => 'CUSTOMER_NAME',
    'buyer_phone' => 'CUSTOMER_PHONE_NUMBER',
    'amount' => 10000, // AMOUNT_TO_BE_PAID
    'webhook_url' => 'https://example.com/webhook',
    'metadata' => json_encode([
        "product_id" => "12345",
        "color" => "blue",
        "size" => "L",
        "custom_notes" => "Please gift-wrap this item."
    ])
];

```

- **`create_order`** (integer): Set to `1` to initiate order creation.
- **`buyer_email`** (string): Customer's email address.
- **`buyer_name`** (string): Customer's full name.
- **`buyer_phone`** (string): Customer's phone number.
- **`amount`** (integer): The amount to be paid (in smallest currency unit, e.g., cents).


#### cURL Configuration



### Request Format

To initiate a payment transaction, send a **POST** request to the API with a JSON payload that includes the following parameters:

| Parameter        | Type    | Description                                        | Example               |
|------------------|---------|----------------------------------------------------|-----------------------|
| `buyer_name`     | string  | Full name of the buyer                             | `"John Doe"`          |
| `buyer_phone`    | string  | Phone number of the buyer                          | `"255712345678"`     |
| `buyer_email`    | string  | Email address of the buyer                         | `"johndoe@example.com"`|
| `amount`         | float   | Amount to be paid by the buyer in the transaction  | `1500.00`              |



The script uses cURL to make the POST request:

```php
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $queryString,
    ],
];
```



#### Error Logging Function

Logs errors to a file:

```php
function logError($message) 
{
    file_put_contents('error_log.txt', $message . "\n", FILE_APPEND);
}
```

### Error Handling

To enhance error handling:

1. **Check cURL Errors**:
   ```php
   if ($response === false) {
       logError('cURL Error: ' . curl_error($ch));
   }
   ```

2. **Check HTTP Status Code**:
   ```php
   $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   if ($httpCode != 200) {
       logError('HTTP Error: ' . $httpCode);
   }
   ```

### Example Usage

1. **Update Order Data**: Replace placeholder values with actual information.
2. **Save the Script**: Save the file as `create_order.php` or another preferred filename.
3. **Run the Script**: Execute it via command line or web server.

### Notes

- Ensure that `error_log.txt` is writable by the script.
- Handle sensitive information such as API keys and secret keys securely.

## Order Status Check Script

### Overview

This script checks the status of an order by sending a POST request to the ZenoPay API endpoint for order status.

### Script Components

#### API Endpoint

- **URL**: `https://api.zeno.africa/order-status`

  This is the endpoint for checking the status of an order.

#### Post Data

The following data is sent in the POST request:

```php
$endpointUrl = "https://api.zeno.africa/order-status";

// Order ID that you want to check the status for
$order_id = "66d5e374ccaab";

// Data to be sent in the POST request
$postData = [
    'check_status' => 1,
    'order_id' => $order_id,
    'api_key' => 'YOUR_API_KEY',
    'secret_key' => 'YOUR_SECRET_KEY'
];
```

- **`check_status`** (integer): Set to `1` to request the status.
- **`order_id`** (string): The ID of the order whose status you want to check.
- **`api_key`** (string): Your API key for authentication.
- **`secret_key`** (string): Your secret key for authentication.

#### cURL Configuration

The script uses cURL to perform the POST request:

```php
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $queryString,
    ],
];

if (curl_errno($ch)) {
    echo json_encode([
        "status" => "error",
        "message" => 'cURL error: ' . curl_error($ch)
    ]);
} else {
    $responseData = json_decode($response, true);
    if ($responseData['status'] === 'success') {
        echo json_encode([
            "status" => "success",
            "order_id" => $responseData['order_id'],
            "message" => $responseData['message'],
            "payment_status" => $responseData['payment_status']
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $responseData['message']
        ]);
    }
}
curl_close($ch);
```



### Error Handling

1. **Check cURL Errors**:
   ```php
   if (curl_errno($ch)) {
       echo json_encode([
           "status" => "error",
           "message" => 'cURL error: ' . curl_error($ch)
       ]);
   }
   ```

2. **Handle Response**:
   Decode and format the JSON response based on the status:

   ```php
   if ($responseData['status'] === 'success') {
       echo json_encode([
           "status" => "success",
           "order_id" => $responseData['order_id'],
           "message" => $responseData['message'],
           "payment_status" => $responseData['payment_status']
       ]);
   } else {
       echo json_encode([
           "status" => "error",
           "message" => $responseData['message']
       ]);
   }
   ```


   ## Webhook Handling

After a payment is processed, ZenoPay may send a **webhook** to notify you about the status of the transaction. You can use the following PHP code to handle the webhook and log the incoming data for further processing or debugging.

### Webhook PHP Example

```php
<?php

// Webhook handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data from the incoming webhook
    $data = file_get_contents('php://input');
    
    // Log the raw data with a timestamp
    file_put_contents('weblogs.txt', "[" . date('Y-m-d H:i:s') . "] WebHook Data: ".$data."\n", FILE_APPEND);
}

?>



WebHook Data: {"order_id":"6757c69cddfa6","payment_status":"COMPLETED","reference":"0882061614"}
```

### How the Webhook Works:
1. **Receiving Webhook Data**: This script listens for incoming **POST** requests (which ZenoPay sends for webhooks

) and reads the raw data from the request body using `file_get_contents('php://input')`.
   
2. **Logging Webhook Data**: The webhook data is logged to a file (`weblogs.txt`) along with a timestamp for reference. This log will help you debug and track transaction statuses or other data sent via the webhook.


### Example Usage

1. **Update Post Data**: Replace placeholder values with actual data.
2. **Save the Script**: Save the file as `check_order_status.php` or another preferred filename.
3. **Run the Script**: Execute it via command line or web server.

### Notes

- Ensure that `error_log.txt` is writable if you are using error logging.
- Secure sensitive information such as API keys and secret keys.

---

Feel free to modify or expand this README as needed for your specific requirements.
