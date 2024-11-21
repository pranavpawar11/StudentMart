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

if (isset($_POST['product_id']) && isset($_POST['address']) && isset($_POST['total_price']) && isset($_POST['pincode']) && isset($_POST['payment_mode'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $address = $_POST['address'];
    $total_price = $_POST['total_price'];
    $pincode = $_POST['pincode'];
    $payment_mode = $_POST['payment_mode']; // New field for payment mode

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

        // Insert into orders table with payment_mode
        $query = "INSERT INTO orders (product_id, buyer_id, seller_id, status, order_date, complete_date, address, pincode, total_price, payment_mode) 
                  VALUES (:product_id, :buyer_id, :seller_id, 'pending', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), :address, :pincode, :total_price, :payment_mode)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':buyer_id', $user_id);
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':pincode', $pincode);
        $stmt->bindParam(':total_price', $total_price);
        $stmt->bindParam(':payment_mode', $payment_mode);

        if ($stmt->execute()) {
            // Fetch the newly inserted order ID
            $order_id = $pdo->lastInsertId();

            // Insert notification for the seller
            $notification_type = 'New Order';
            $query = "INSERT INTO notifications (seller_id, buyer_id, product_id, notification_date, notification_type, is_read) 
                      VALUES (:seller_id, :buyer_id, :product_id, NOW(), :notification_type, FALSE)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':seller_id', $seller_id);
            $stmt->bindParam(':buyer_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':notification_type', $notification_type);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add notification for seller!']);
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
