<?php

namespace ZenoPay;

class ZenoPay
{
    private $account_id;
    private $api_key;
    private $secret_key;
    private $api_endpoint;

    public function __construct($account_id, $api_key, $secret_key, $api_endpoint)
    {
        $this->account_id = $account_id;
        $this->api_key = $api_key;
        $this->secret_key = $secret_key;
        $this->api_endpoint = $api_endpoint;
    }

    public function processPayment($buyer_email, $buyer_name, $buyer_phone, $amount)
    {
        $data = array(
            'create_order' => 1,
            'buyer_email'  => $buyer_email,
            'buyer_name'   => $buyer_name,
            'buyer_phone'  => $buyer_phone,
            'amount'       => $amount,
            'account_id'   => $this->account_id,
            'api_key'      => $this->api_key,
            'secret_key'   => $this->secret_key,
        );

        error_log('Request Data: ' . http_build_query($data));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            error_log('cURL error: ' . $error_msg);
            curl_close($ch);
            return array(
                'result' => 'failure',
                'error'  => $error_msg,
            );
        } else {
            curl_close($ch);
            $responseData = json_decode($response, true);
            error_log('API Response: ' . print_r($responseData, true));
            return array(
                'result' => 'success',
                'message'  => $responseData['message'],
            );
        }
    }

    function logError($message)
    {
        // Function to log errors
        file_put_contents('error_log.txt', $message . "\n", FILE_APPEND);
    }
}
