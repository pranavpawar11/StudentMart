<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];

        try {
            // Step 1: Retrieve the order_id from the requests table
            $query = "
                SELECT order_id
                FROM requests
                WHERE request_id = :request_id;
            ";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':request_id', $request_id);
            $stmt->execute();
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($request) {
                $order_id = $request['order_id'];

                // Step 2: Retrieve the product_id, buyer_id, and seller_id from the orders table
                $query = "
                    SELECT product_id, buyer_id, seller_id
                    FROM orders
                    WHERE order_id = :order_id;
                ";

                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->execute();
                $order = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($order) {
                    $product_id = $order['product_id'];
                    $buyer_id = $order['buyer_id'];
                    $seller_id = $order['seller_id'];

                    // Step 3: Update the is_read status in the notifications table
                    $query = "
                        UPDATE notifications 
                        SET is_read = 0 , notification_type = 'Confirmation Alert'
                        WHERE product_id = :product_id
                        AND buyer_id = :buyer_id
                        AND seller_id = :seller_id
                        AND is_read = 1;
                    ";

                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':buyer_id', $buyer_id);
                    $stmt->bindParam(':seller_id', $seller_id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        echo json_encode(['status' => 'success', 'message' => 'Notification status updated successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update notification status']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Order not found']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Request not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
