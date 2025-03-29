<?php
session_start();
require_once('conn.php');
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

try {
    // Verify valid session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    // Initialize Razorpay
    $api = new Api('rzp_test_8k9Y3Mmk6y9sy0', 'cgbCQ1yvbRMK3QM9z2jPhf0G');
    
    // Verify payment signature
    $api->utility->verifyPaymentSignature($_POST);

    // Retrieve payment context
    if (!isset($_SESSION['payment_context'])) {
        throw new Exception('Invalid payment session');
    }
    
    $context = $_SESSION['payment_context'];
    $user_id = $_SESSION['user_id'];

    // Start database transaction
    $pdo->beginTransaction();

    // Insert transaction record
    $stmt = $pdo->prepare("
        INSERT INTO transactions (
            user_id, 
            order_id, 
            payment_id, 
            amount, 
            subscription_id, 
            product_id, 
            payment_status, 
            payment_date
        ) VALUES (?, ?, ?, ?, ?, ?, 'success', NOW())
    ");

    $stmt->execute([
        $user_id,
        $context['order_id'],
        $_POST['razorpay_payment_id'],
        $context['amount'],
        $context['metadata']['subscription_id'] ?? null,
        $context['metadata']['product_id'] ?? null
    ]);

    // Handle specific payment types
    switch ($context['type']) {
        case 'platform_fee':
            // Update order with fee payment
            $stmt = $pdo->prepare("
                UPDATE orders SET 
                    platform_fee = ?,
                    fee_paid = 1 
                WHERE product_id = ? 
                AND seller_id = ?
            ");
            $stmt->execute([
                $context['amount'],
                $context['metadata']['product_id'],
                $user_id
            ]);
            break;

        case 'subscription':
            // Create subscription
            $start_date = new DateTime();
            $end_date = $start_date->modify('+30 days');
            
            $stmt = $pdo->prepare("
                INSERT INTO user_subscriptions (
                    user_id, 
                    subscription_id, 
                    start_date, 
                    end_date
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $context['metadata']['subscription_id'],
                $start_date->format('Y-m-d'),
                $end_date->format('Y-m-d')
            ]);

            // Update user status
            $stmt = $pdo->prepare("
                UPDATE users SET 
                    subscription_status = 'active',
                    subscription_expiry = ?
                WHERE user_id = ?
            ");
            $stmt->execute([
                $end_date->format('Y-m-d'),
                $user_id
            ]);
            break;

        case 'product':
            // Update product order status
            $stmt = $pdo->prepare("
                UPDATE orders SET 
                    payment_status = 'completed',
                    payment_date = NOW()
                WHERE order_id = ?
                AND buyer_id = ?
            ");
            $stmt->execute([
                $context['order_id'],
                $user_id
            ]);
            break;
    }

    // Commit transaction
    $pdo->commit();

    // Clear session data
    unset($_SESSION['payment_context']);

    // Redirect based on payment type
    switch ($context['type']) {
        case 'platform_fee':
            $_SESSION['success'] = 'Contact details unlocked!';
            header('Location: order_details.php?order_id='.$context['order_id']);
            break;
            
        case 'subscription':
            $_SESSION['success'] = 'Subscription activated successfully!';
            header('Location: profile.php');
            break;
            
        default:
            $_SESSION['success'] = 'Payment completed successfully!';
            header('Location: order_confirmation.php?order_id='.$context['order_id']);
    }

} catch (Exception $e) {
    // Rollback on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log('Payment Error: ' . $e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    header('Location: payment_error.php');
}

exit;
?>