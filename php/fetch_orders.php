<?php
include ('conn.php'); // Include your database connection file
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Query to fetch orders with product details
    $query = "
        SELECT o.order_id, o.product_id, o.buyer_id, o.status, o.order_date,
               p.product_name, p.product_price, p.product_description, p.img1
        FROM orders o
        INNER JOIN products p ON o.product_id = p.product_id
        WHERE o.buyer_id = :user_id
        ORDER BY o.order_date DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare response data
    $response = ['success' => true, 'orders' => $orders];
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>