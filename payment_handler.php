<?php
// Ensure no output before headers
ob_start();
session_start();
require_once('conn.php');
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

// Set JSON header immediately
header('Content-Type: application/json');

try {
    // Validate session first
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Authentication required', 401);
    }

    // Get raw POST data
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input', 400);
    }

    $api = new Api('rzp_test_8k9Y3Mmk6y9sy0', 'cgbCQ1yvbRMK3QM9z2jPhf0G');

    // Validate payment type
    if (!isset($input['type']) || $input['type'] !== 'platform_fee') {
        throw new Exception('Invalid payment type', 400);
    }

    // Validate required fields
    $required = ['productId', 'product_price'];
    foreach ($required as $field) {
        if (!isset($input[$field])) {
            throw new Exception("Missing field: $field", 400);
        }
    }

    // Calculate fee
    $amount = (float)$input['product_price'] * 0.05;
    if ($amount <= 0) {
        throw new Exception('Invalid product price', 400);
    }

    // Create Razorpay order
    $order = $api->order->create([
        'amount' => $amount * 100,
        'currency' => 'INR',
        'receipt' => 'FEE_'.bin2hex(random_bytes(4)),
        'notes' => [
            'type' => 'platform_fee',
            'product_id' => (int)$input['productId'],
            'seller_id' => $_SESSION['user_id']
        ]
    ]);

    // Store in session
    $_SESSION['payment_context'] = [
        'type' => 'platform_fee',
        'amount' => $amount,
        'product_id' => (int)$input['productId'],
        'razorpay_order_id' => $order->id
    ];

    echo json_encode(['status' => 'success', 'orderId' => $order->id]);
    exit;

} catch (Exception $e) {
    // Clean any output buffers
    while (ob_get_level()) ob_end_clean();
    
    http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    exit;
}
?>