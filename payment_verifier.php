<?php
session_start();
require_once('conn.php');
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

header('Content-Type: application/json');

try {
    // Validate session
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['payment_context'])) {
        throw new Exception('Invalid session', 401);
    }

    $api = new Api('rzp_test_8k9Y3Mmk6y9sy0', 'cgbCQ1yvbRMK3QM9z2jPhf0G');
    $input = json_decode(file_get_contents('php://input'), true);

    // Verify payment signature
    $api->utility->verifyPaymentSignature([
        'razorpay_order_id' => $input['razorpay_order_id'],
        'razorpay_payment_id' => $input['razorpay_payment_id'],
        'razorpay_signature' => $input['razorpay_signature']
    ]);

    // Update database
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("
        UPDATE orders SET 
            platform_fee = ?,
            fee_paid = 1,
            status = 'approved'
        WHERE product_id = ?
        AND seller_id = ?
    ");
    $stmt->execute([
        $_SESSION['payment_context']['amount'],
        $_SESSION['payment_context']['product_id'],
        $_SESSION['user_id']
    ]);

    $pdo->commit();

    // Clear session data
    unset($_SESSION['payment_context']);

    echo json_encode([
        'status' => 'success',
        'message' => 'Payment verified and order approved'
    ]);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>