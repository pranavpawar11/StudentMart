<?php
// Include Razorpay SDK
require('razorpay-php/Razorpay.php'); // Make sure you have installed Razorpay PHP SDK

use Razorpay\Api\Api;

header('Content-Type: application/json');

// Your Razorpay API credentials
$razorpayKeyId = 'rzp_test_umea8flScA3xwG'; // Replace with your Razorpay Key ID
$razorpayKeySecret = 'oNXtPtWSI3XbxkEaUqtBAGI4'; // Replace with your Razorpay Key Secret

// Initialize Razorpay API
$api = new Api($razorpayKeyId, $razorpayKeySecret);

// Get subscription details from the POST request
$subscriptionId = $_POST['subscription_id']; // Assume subscription ID is passed
$amount = $_POST['amount'] * 100; // Amount in paise (convert INR to paise)

// Create an order
$orderData = [
    'amount' => $amount, // Total amount (in paise)
    'currency' => 'INR',
    'payment_capture' => 1, // Auto capture
    'notes' => ['subscription_id' => $subscriptionId] // Add subscription ID in notes
];

try {
    $order = $api->order->create($orderData); // Create the order with Razorpay
    $response = [
        'success' => true,
        'order_id' => $order['id'] // Send back order ID
    ];
    echo json_encode($response);
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    echo json_encode($response);
}
?>
