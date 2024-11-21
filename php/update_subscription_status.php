<?php
// Include your database connection here
require('conn.php');

// Get POST data
$subscriptionId = $_POST['subscription_id'];
$paymentId = $_POST['payment_id'];

// Validate the payment and update the subscription status in the database
// You should verify the payment with Razorpay's API before updating the subscription

$query = "INSERT INTO user_subscriptions (user_id, subscription_id, start_date, end_date) 
          VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY))"; // Example: 30-day subscription
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $userId, $subscriptionId); // You should fetch the user ID from the session or token

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Subscription updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update subscription']);
}
?>
