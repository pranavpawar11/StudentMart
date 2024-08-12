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
    // Check if the product is already in the cart
    $query = 'SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Product is already in the cart, return a message indicating that
        echo json_encode(['success' => false, 'message' => 'Product is already in the cart']);
    } else {
        // Insert new record into the cart
        $query = 'INSERT INTO cart (user_id, product_id, date_added) VALUES (:user_id, :product_id, NOW())';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Product added to cart successfully!']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
