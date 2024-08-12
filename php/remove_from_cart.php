<?php
include('conn.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    $query = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product deleted from cart successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product Not deleted from cart !']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
