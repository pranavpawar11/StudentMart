<?php
include('./conn.php');
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'];
$tracking_status = $data['tracking_status'];

try {
    // Determine main status based on tracking status
    $main_status = 'approved';
    $complete_date = 'NULL';
    
    if ($tracking_status === 'delivered') {
        $main_status = 'completed';
        $complete_date = 'NOW()';
    }

    $query = "
        UPDATE orders 
        SET 
            tracking_status = :tracking_status,
            status = :main_status,
            complete_date = $complete_date
        WHERE 
            order_id = :order_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':tracking_status' => $tracking_status,
        ':main_status' => $main_status,
        ':order_id' => $order_id
    ]);

    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>