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

$order_id = $transactionData['razorpay_order_id']; // Razorpay order ID
$payment_id = $transactionData['razorpay_payment_id']; // Razorpay payment ID
$payment_signature = $transactionData['razorpay_signature']; // Razorpay payment signature
$user_id = $_SESSION['user_id']; // User ID from session

// Retrieve session data
$amount = $_SESSION['amount'];
$subscription_id = isset($_SESSION['subscription_id']) ? $_SESSION['subscription_id'] : null;
$product_id = isset($_SESSION['product_id']) ? $_SESSION['product_id'] : null;
unset($_SESSION['subscription_id']);
unset($_SESSION['product_id']);
// Log the transaction data for debugging
error_log("Transaction Data: " . print_r($transactionData, true));

// Verify the payment signature
$api->utility->verifyPaymentSignature($transactionData);

// Payment signature verification
if ($payment_signature) {
    // Signature is valid
    // Now, insert the transaction into the database

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, order_id, payment_status, amount, subscription_id, product_id, transaction_id, payment_date) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $order_id, 'success', $amount, $subscription_id, $product_id, $payment_id]);

    if ($subscription_id) {
        // Insert into user subscriptions table if applicable
        $start_date = date('Y-m-d'); // Today's date
        $duration = 30; // Subscription duration in days
        $end_date = date('Y-m-d', strtotime("+$duration days"));

        $stmt = $pdo->prepare("INSERT INTO user_subscriptions (user_id, subscription_id, start_date, end_date, created_at) 
                               VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $subscription_id, $start_date, $end_date]);

        // Update user subscription status to active
        $stmt = $pdo->prepare("UPDATE user SET subscription_status = ? WHERE user_id = ?");
        $stmt->execute(['active', $user_id]);
    }

    $_SESSION['success_message'] = "Success";
    echo json_encode(['status' => 'success', 'message' => 'Payment and subscription created successfully']);
} else {
    // Signature mismatch, payment failed
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, order_id, payment_status, amount, subscription_id, product_id, transaction_id, created_at) 
                           VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $order_id, 'failed', $amount, $subscription_id, $product_id, $payment_id]);
    $_SESSION['error_message'] = "Error";
    echo json_encode(['status' => 'failure', 'message' => 'Payment verification failed']);
}
if ($subscription_id) {
    echo `<script>alert("Successfully Purchased Subcription");</script>`;
    header('Location: profile.php');
} else {
    
    echo `<script>alert("Successfully Purchased Product");</script>`;
    header('Location: shoping-cart.php');
}
exit;
?>