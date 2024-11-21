<?php
include('conn.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $order_id = $data['order_id'];

    try {
        $query = "UPDATE orders SET status = 'approved' WHERE order_id = :order_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
