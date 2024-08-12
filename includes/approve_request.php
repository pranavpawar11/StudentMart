<?php
include ('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $request_id = $_POST['requestId'];

    try {
        // Update the request status to 'approved' in the database
        $query = "
                UPDATE requests r
                SET r.status = 'approved', r.response_date = NOW()
                WHERE r.request_id = :request_id 
                AND r.status IN ('pending', 'declined') 
                AND EXISTS (
                    SELECT 1
                    FROM orders o
                    WHERE r.order_id = o.order_id 
                    AND o.seller_id = :user_id
                )
";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':request_id', $request_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve request.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>