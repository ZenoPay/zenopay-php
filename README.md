Certainly! Here's a README.md format for your ZenoPay PHP package documentation:

```markdown
# ZenoPay PHP Package

## Overview

The `ZenoPay` PHP package allows you to integrate ZenoPay payment processing into your PHP applications. This package provides an easy-to-use interface for creating and managing payment transactions securely through the ZenoPay API.

## Installation

### Composer

To include the `ZenoPay` package in your project using Composer, add it to your `composer.json`:

```json
{
  "require": {
    "your-vendor/zenopay": "^1.0"
  }
}
```

Then run:

```sh
composer install
```

### Manual Installation

If you are not using Composer, include the `ZenoPay` class in your project:

```php
require_once 'path/to/ZenoPay.php';
```

## Configuration

You need to initialize the `ZenoPay` class with your ZenoPay account details.

### Constructor

```php
public function __construct($account_id, $api_key, $secret_key, $api_endpoint);
```

**Parameters:**

- **$account_id**: Your ZenoPay account ID.
- **$api_key**: Your ZenoPay API key.
- **$secret_key**: Your ZenoPay secret key.
- **$api_endpoint**: The API endpoint URL for processing payments.

## Methods

### `processPayment`

```php
public function processPayment($buyer_email, $buyer_name, $buyer_phone, $amount);
```

**Parameters:**

- **$buyer_email**: The email address of the buyer.
- **$buyer_name**: The name of the buyer.
- **$buyer_phone**: The phone number of the buyer.
- **$amount**: The amount to be charged.

**Returns:**

- **Array**: The method returns an associative array with the following keys:
  - **result**: A string indicating the result of the request. Possible values are `'success'` or `'failure'`.
  - **error**: A string containing the error message if the request fails. Only present if `result` is `'failure'`.
  - **redirect**: A URL to which the user should be redirected to complete the payment. Only present if `result` is `'success'`.

**Example:**

```php
// Initialize the ZenoPay instance
$zenopay = new ZenoPay('your_account_id', 'your_api_key', 'your_secret_key', 'https://api.zenopay.com');

// Process a payment
$response = $zenopay->processPayment('buyer@example.com', 'John Doe', '1234567890', 100.00);

if ($response['result'] == 'success') {
    // Redirect the user to the payment page
    header('Location: ' . $response['redirect']);
    exit();
} else {
    // Handle the error
    echo 'Payment failed: ' . $response['error'];
}
```

## Error Handling

The `processPayment` method uses cURL for making API requests. If a cURL error occurs, it will be logged, and the method will return an error message. Ensure that your PHP installation has the cURL extension enabled.

## Logging

The package logs request and response data for debugging purposes. You can find these logs in your PHP error log file.

## Security

- Ensure that sensitive information such as `api_key` and `secret_key` is stored securely.
- Use HTTPS to protect data during transmission.

## Troubleshooting

- **cURL Errors**: If you encounter cURL errors, check your network connectivity and ensure that the API endpoint is correct.
- **Invalid API Responses**: Verify that the API endpoint and credentials are correct.
