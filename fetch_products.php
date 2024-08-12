<?php
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session to access session variables
}

include './php/conn.php'; // Include the database connection file

try {
    // Check if user_id is set in the session
    if (isset($_SESSION['user_id'])) {
        $current_user_id = $_SESSION['user_id'];

        // Prepare SQL query to fetch all products excluding those of the current user
        $sql = "SELECT p.*, 
                CASE 
                    WHEN w.product_id IS NOT NULL THEN 'images/icons/icon-heart-02.png' 
                    ELSE 'images/icons/icon-heart-01.png' 
                END AS wishlist_icon
                FROM products p
                LEFT JOIN wishlist w ON p.product_id = w.product_id 
                WHERE p.product_status = 'available' AND p.seller_id != :current_user_id
                ORDER BY RAND()";

        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        // Bind the user_id parameter
        $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
        // Execute the statement
        $stmt->execute();
    } else {
        throw new Exception('User ID not set in session.');
    }

    // Initialize an array to hold the products
    $products = [];

    // Loop through each row in the result set and add to the products array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row;
    }

    // Echo the JSON encoded products array
    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
