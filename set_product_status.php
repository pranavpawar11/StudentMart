<?php
// my_products.php
include ("./php/conn.php");

// Check if the action parameter is set and if it's equal to "available"
if (isset($_GET['action']) && $_GET['action'] === 'available') {
    
    $productId = $_GET['id'];
    $sql = "UPDATE products
    SET product_status = 'sold'
    WHERE product_id = $productId;";
	$stmt = $pdo->query($sql);
    header("Location: my_products.php");
    exit; // Make sure to exit to prevent further execution
}else{
    $productId = $_GET['id'];
    $sql = "UPDATE products
    SET product_status = 'available'
    WHERE product_id = $productId;";
	$stmt = $pdo->query($sql);
    header("Location: my_products.php");
}
?>
