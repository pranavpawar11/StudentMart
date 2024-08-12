<?php
include ('conn.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Request method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if (isset($_POST['product_id']) && isset($_POST['address']) && isset($_POST['total_price']) && isset($_POST['message'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $address = $_POST['address'];
    $total_price = $_POST['total_price'];
    $message = $_POST['message'];

    try {
        // Fetch seller_id from products table
        $query = "SELECT seller_id FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit;
        }

        $seller_id = $result['seller_id'];

        // Insert into orders table
        $query = "INSERT INTO orders (product_id, buyer_id, seller_id, status, order_date, address, total_price) 
                  VALUES (:product_id, :buyer_id, :seller_id, 'pending', NOW(), :address, :total_price)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':buyer_id', $user_id);
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':total_price', $total_price);

        if ($stmt->execute()) {
            // Fetch the newly inserted order ID
            $order_id = $pdo->lastInsertId();

            // Insert into requests table
            $query = "INSERT INTO requests (order_id, request_date, status, message) VALUES (:order_id, NOW(), 'pending', :message)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                // Insert notification for the seller
                $notification_type = 'New Request';
                $query = "INSERT INTO notifications (seller_id, buyer_id, product_id, notification_date, notification_type, is_read) 
                          VALUES (:seller_id, :buyer_id, :product_id, NOW(), :notification_type, FALSE)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':seller_id', $seller_id);
                $stmt->bindParam(':buyer_id', $user_id);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':notification_type', $notification_type);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Product requested successfully!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add notification for seller!']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to request product!']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to place order!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>