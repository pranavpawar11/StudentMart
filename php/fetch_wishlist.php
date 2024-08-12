<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];

    $query = "
        SELECT p.product_id, p.product_name, p.product_price, p.img1, w.date_added 
        FROM wishlist w
        JOIN products p ON w.product_id = p.product_id
        WHERE w.user_id = :user_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($wishlist_items);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
