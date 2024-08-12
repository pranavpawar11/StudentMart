<?php
include('conn.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        $query = "SELECT * FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Return product details as JSON response
            echo json_encode($product);
        } else {
            // Product not found
            echo json_encode(['error' => 'Product not found']);
        }
    } catch (PDOException $e) {
        // Database error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // No product ID provided
    echo json_encode(['error' => 'Product ID not provided']);
}
?>
