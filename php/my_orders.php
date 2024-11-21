<?php 
session_start();
header('Content-Type: application/json');
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get all orders for the current user
    $query = "
        SELECT o.order_id, o.product_id, o.status, o.order_date, o.complete_date, o.total_price, p.product_name, p.img1
        FROM orders o
        JOIN products p ON o.product_id = p.product_id
        WHERE o.buyer_id = :user_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'orders' => $orders]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
