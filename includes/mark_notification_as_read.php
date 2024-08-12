<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['notification_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$notification_id = $_POST['notification_id'];
$user_id = $_SESSION['user_id'];

try {
    $query = "UPDATE notifications SET is_read = 1 WHERE notification_id = :notification_id AND seller_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':notification_id', $notification_id);
    $stmt->bindParam(':user_id', $user_id);
    
    // Debug: Log the query and parameters
    error_log("Query: $query");
    error_log("Notification ID: $notification_id, User ID: $user_id");

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Notification marked as read']);
    } else {
        // Debug: Log when no rows are affected
        error_log("No rows affected. Notification ID: $notification_id, User ID: $user_id");
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to mark notification as read']);
    }
} catch (PDOException $e) {
    // Debug: Log the exception
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>