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

if (isset($data['type']) && $data['type'] === 'platform_fee' && isset($data['orderId']) && isset($data['platformFee'])) {
    $user_id = $_SESSION["user_id"];
    $order_id_original = $data['orderId']; // This is the original order ID from the orders table
    $amount = $data['platformFee'];

    try {
        // Create an order for Razorpay (amount needs to be in paise, so multiply by 100)
        $razorpay_order = $api->order->create([
            'amount' => $amount * 100,  // Convert to paise (INR smallest unit)
            'currency' => 'INR',
            'receipt' => 'platform_fee_' . uniqid()
        ]);

        // Get the Razorpay order ID
        $razorpay_order_id = $razorpay_order->id;

        // Store relevant data in session
        $_SESSION['platform_fee_original_order_id'] = $order_id_original;
        $_SESSION['platform_fee_amount'] = $amount;
        $_SESSION['platform_fee_razorpay_order_id'] = $razorpay_order_id;

        // Send the JSON response with the order_id and necessary data
        header('Content-Type: application/json');
        echo json_encode([
            'orderId' => $razorpay_order_id,
            'success' => true
        ]);

    } catch (Exception $e) {
        // If there's an error, send a JSON response with the error message
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Failed to create platform fee order. Please try again.',
            'details' => $e->getMessage()
        ]);
    }
} else {
    // If the required data is not received, send a JSON error response
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid data received for platform fee']);
}
?>