<?php
// update_product_status.php

// Include the database connection
include("conn.php");
session_start();

// Check if action and id are set in the GET parameters
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $productId = $_GET['id'];

    // Determine the new status based on the action parameter
    $newStatus = ($action === 'available') ? 'available' : 'sold';

    try {
        // Update the product status in the database
        $sql = "UPDATE products SET product_status = :status WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $productId);
        $stmt->execute();

        // Prepare JSON response
        $response = [
            'success' => true,
            'message' => 'Product status updated successfully.'
        ];
    } catch (PDOException $e) {
        // Prepare JSON response on failure
        $response = [
            'success' => false,
            'message' => 'Failed to update product status: ' . $e->getMessage()
        ];
    }
} else {
    // Prepare JSON response if action or id is not set
    $response = [
        'success' => false,
        'message' => 'Action or product ID not provided.'
    ];
}

// Send JSON response back to the client
header('Content-Type: application/json');
echo json_encode($response);
?>
