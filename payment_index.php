<?php
session_start(); // Start the session to access session variables
require_once('php/conn.php'); // Include the database connection file
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$api_key = 'rzp_test_8k9Y3Mmk6y9sy0';
$api_secret = 'cgbCQ1yvbRMK3QM9z2jPhf0G';
$api = new Api($api_key, $api_secret);

// Check if the incoming data is valid JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Debug: Log the received data
error_log(print_r($data, true));

if (isset($data['type']) && isset($data['price'])) {
    $user_id = $_SESSION["user_id"];
    $amount = $data['price'];

    try {
        // Create an order for Razorpay (amount needs to be in paise, so multiply by 100)
        $order = $api->order->create([
            'amount' => $amount * 100,  // Convert to paise (INR smallest unit)
            'currency' => 'INR',
            'receipt' => 'order_receipt_' . uniqid()
        ]);

        // Get the Razorpay order ID
        $order_id = $order->id;

        // Store relevant data in session based on the type of purchase
        if ($data['type'] == 'subscription') {
            $_SESSION['subscription_id'] = $data['subscriptionId'];
        } elseif ($data['type'] == 'product') {
            $_SESSION['product_id'] = $data['productId'];
        }

        $_SESSION['amount'] = $amount; // Save the price into session

        // Send the JSON response with the order_id and necessary data
        header('Content-Type: application/json');
        echo json_encode(['orderId' => $order_id]);

    } catch (Exception $e) {
        // If there's an error, send a JSON response with the error message
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create order. Please try again.']);
    }
} else {
    // If the required data is not received, send a JSON error response
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid data received']);
}
?>
