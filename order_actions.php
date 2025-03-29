<?php
session_start();
require_once('conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    // Validate input
    if(!isset($data['action']) || !isset($data['order_id'])) {
        throw new Exception('Missing required parameters');
    }

    // Verify order ownership
    $stmt = $pdo->prepare("SELECT seller_id FROM orders WHERE order_id = ?");
    $stmt->execute([$data['order_id']]);
    $order = $stmt->fetch();
    
    if(!$order || $order['seller_id'] != $_SESSION['user_id']) {
        throw new Exception('Invalid order access');
    }

    // Process action
    switch($data['action']) {
        case 'approve':
            $stmt = $pdo->prepare("
                UPDATE orders SET 
                status = 'approved',
                tracking_status = 'placed'
                WHERE order_id = ?
            ");
            break;
            
        case 'decline':
            $stmt = $pdo->prepare("
                UPDATE orders SET 
                status = 'declined'
                WHERE order_id = ?
            ");
            break;
            
        default:
            throw new Exception('Invalid action');
    }

    $stmt->execute([$data['order_id']]);
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>