<?php
require_once('conn.php');

// Get current date and time
$current_date = new DateTime();

// Prepare query to select expired subscriptions
$query = "SELECT * FROM user_subscriptions WHERE end_date < NOW()";
$stmt = $pdo->prepare($query);
$stmt->execute();
$expired_subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($expired_subscriptions as $subscription) {
    $user_id = $subscription['user_id'];
    $subscription_id = $subscription['id'];
    
    // Update user table or any other table to mark subscription as expired
    $update_user_query = "UPDATE user SET subscription_status = 'inactive' WHERE user_id = ?";
    $update_stmt = $pdo->prepare($update_user_query);
    $update_stmt->bindParam(1, $user_id);
    $update_stmt->execute();
    
    // Optionally, you can also delete the expired subscription from user_subscription table
    // $delete_query = "DELETE FROM user_subscription WHERE id = ?";
    // $delete_stmt = $pdo->prepare($delete_query);
    // $delete_stmt->bindParam(1, $subscription_id);
    // $delete_stmt->execute();
}

?>
