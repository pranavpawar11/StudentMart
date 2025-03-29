<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
require_once('php/conn.php'); // Database connection

// Razorpay API key and secret
$api_key = 'rzp_test_8k9Y3Mmk6y9sy0';
$api_secret = 'cgbCQ1yvbRMK3QM9z2jPhf0G';

$api = new Api($api_key, $api_secret);

// Assuming the payment success data comes via POST from Razorpay callback
$transactionData = $_POST; // POST data from Razorpay callback

$razorpay_order_id = $transactionData['razorpay_order_id']; // Razorpay order ID
$payment_id = $transactionData['razorpay_payment_id']; // Razorpay payment ID
$payment_signature = $transactionData['razorpay_signature']; // Razorpay payment signature
$user_id = $_SESSION['user_id']; // User ID from session

// Retrieve session data
$amount = $_SESSION['platform_fee_amount'];
$original_order_id = $_SESSION['platform_fee_original_order_id']; 

// Log the transaction data for debugging
error_log("Platform Fee Transaction Data: " . print_r($transactionData, true));

try {
    // Verify the payment signature
    $api->utility->verifyPaymentSignature($transactionData);

    // Payment signature verification successful
    // Now, insert the transaction into the database
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, order_id, payment_status, amount, subscription_id, product_id, transaction_id, payment_date, payment_type) 
                           VALUES (?, ?, ?, ?, NULL, NULL, ?, NOW(), 'platform_fee')");
    $stmt->execute([$user_id, $razorpay_order_id, 'success', $amount, $payment_id]);
    
    // Update the original order to mark platform fee as paid
    $stmt = $pdo->prepare("UPDATE orders SET platform_fee_paid = 1, platform_fee_amount = ? WHERE order_id = ?");
    $stmt->execute([$amount, $original_order_id]);
    
    // Clear session variables
    unset($_SESSION['platform_fee_original_order_id']);
    unset($_SESSION['platform_fee_amount']);
    unset($_SESSION['platform_fee_razorpay_order_id']);
    
    $_SESSION['success_message'] = "Platform fee paid successfully!";
    
    // Redirect back to the pending orders page
    header('Location: dashboard.php');
    exit;
    
} catch (Exception $e) {
    // Signature mismatch or payment failed
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, order_id, payment_status, amount, subscription_id, product_id, transaction_id, payment_date, payment_type) 
                           VALUES (?, ?, ?, ?, NULL, NULL, ?, NOW(), 'platform_fee')");
    $stmt->execute([$user_id, $razorpay_order_id, 'failed', $amount, $payment_id]);
    
    $_SESSION['error_message'] = "Platform fee payment failed: " . $e->getMessage();
    
    // Redirect back to the pending orders page
    header('Location: dashboard.php');
    exit;
}
?>