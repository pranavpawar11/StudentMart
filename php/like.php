<?php
session_start();
header('Content-Type: application/json'); // Set response content type to JSON
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['productId'];

try {
    
    $query = 'INSERT INTO wishlist (user_id, product_id,date_added) VALUES (:user_id, :product_id,NOW())';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Product Added to whishlist']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
