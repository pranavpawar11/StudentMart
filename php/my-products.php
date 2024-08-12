<?php
// my_products.php

// Include the database connection
include ("conn.php");

// Check if the action parameter is set and if it's equal to "available" or "sold"
if (isset($_GET['action']) && ($_GET['action'] === 'available' || $_GET['action'] === 'sold')) {
	// Extract the product ID and new status from the GET parameters
	$productId = $_GET['id'];
	$newStatus = ($_GET['action'] === 'available') ? 'available' : 'sold';

	// Prepare and execute the SQL query to update the product status
	$sql = "UPDATE products
            SET product_status = :status
            WHERE product_id = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':status', $newStatus);
	$stmt->bindParam(':id', $productId);
	$stmt->execute();

	// Respond with a JSON success message
	header('Content-Type: application/json');
	echo json_encode(['success' => true]);
	exit;
}
?>




<?php
include 'conn.php'; // Include the conn.php file to establish a database connection

try {
	session_start();
	// SQL query to fetch 10 random product details
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT * FROM products WHERE seller_id = $user_id";
	$stmt = $pdo->query($sql);

	// Start appending to the container
	echo '<script>';
	echo 'document.addEventListener("DOMContentLoaded", function() {';
	echo 'var container = document.querySelector(".row.row-cols-1.row-cols-md-4.g-4");'; // Get the container

	// Loop through each row in the result set
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

		echo 'var newProduct = document.createElement("div");';
		echo 'newProduct.className = "col";';
		echo 'newProduct.innerHTML = `';
		echo '<div class="card" style="width: 18rem;">';
		echo '<div style="height: 300px; overflow: hidden; text-align: center; display: flex; justify-content: center; align-items: center;">';
		echo '<img src="' . $row['img1'] . '" class="card-img-top" alt="IMG-PRODUCT" style="width: 100%; height: auto;">';
		echo '</div>';
		echo '<div class="card-body">';
		echo '<h5 class="card-title">' . $row['product_name'] . '</h5>';
		echo '<p class="card-title">' . $row['product_price'] . '</p>';

		if ($row['product_status'] == "available") {
			echo '<button id="button_' . $row['product_id'] . '" class="btn btn-success" onclick="updateProductStatus(' . $row['product_id'] . ', \'sold\')">Make Unavailable</button>';
		} else {
			echo '<button id="button_' . $row['product_id'] . '" class="btn btn-danger" onclick="updateProductStatus(' . $row['product_id'] . ', \'available\')">Make Available</button>';
		}

		echo '<span style="margin-left: 20px;"></span>';
		echo '<a href="my_products.php?id=' . $row['product_id'] . '" class="btn btn-primary">Edit</a>';
		echo '</div>';
		echo '</div>`;';
		echo 'container.appendChild(newProduct);'; // Append to the container
	}

	echo '});';
	echo '</script>';
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
}
?>







<div class="row row-cols-1 row-cols-md-4 g-4">
				
</div>