<?php
// display_products.php

// Include the database connection
include("conn.php");
session_start();

try {
    // Fetch products for the logged-in user
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM products WHERE seller_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 1200px; /* Adjust container width as needed */
            margin-top: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-img {
            height: 300px; /* Increased image height for better visibility */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-img img {
            width: auto;
            height: 100%;
            object-fit: cover;
        }

        .product-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card-title {
            font-size: 1.5rem; /* Larger font size for product title */
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Center align buttons vertically */
            margin-top: auto;
        }

        .status-btn {
            flex: 1;
            min-width: 150px; /* Set a minimum width to prevent button resizing */
            font-size: 14px; /* Adjust font size */
            padding: 8px 16px; /* Padding around the text */
            transition: background-color 0.3s ease;
        }

        .btn-success {
            background-color: #28a745; /* Green color for 'Make Unavailable' button */
            border-color: #28a745; /* Matching border color */
        }

        .btn-danger {
            background-color: #dc3545; /* Red color for 'Make Available' button */
            border-color: #dc3545; /* Matching border color */
        }

        .btn-primary {
            font-size: 14px; /* Adjust font size */
            padding: 8px 16px; /* Padding around the text */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>My Products</h2>
        <br>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="col mb-4">
                <div class="card product-card">
                    <div class="product-img">
                        <img src="<?php echo htmlspecialchars($row['img1']); ?>" class="card-img-top"
                            alt="Product Image">
                    </div>
                    <div class="card-body product-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['product_price']); ?> ₹</p>
                        <div class="btn-container">
                            <?php if ($row['product_status'] == "available") : ?>
                            <button id="button_<?php echo $row['product_id']; ?>"
                                class="btn btn-success status-btn mx-2  "
                                onclick="updateProductStatus(<?php echo $row['product_id']; ?>, 'sold')">Make
                                Unavailable</button>
                            <?php else : ?>
                            <button id="button_<?php echo $row['product_id']; ?>"
                                class="btn btn-danger status-btn mx-2"
                                onclick="updateProductStatus(<?php echo $row['product_id']; ?>, 'available')">Make
                                Available</button>
                            <?php endif; ?>
                            <a href="edit_product.php?id=<?php echo $row['product_id']; ?>"
                                class="btn btn-primary btn-edit">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Function to update product status
        function updateProductStatus(productId, newStatus) {
            // Implement your logic to update status here (Ajax call or form submission)
            // For demonstration, we'll toggle the button text and color
            var button = document.getElementById('button_' + productId);
            if (newStatus === 'sold') {
                button.textContent = 'Make Available';
                button.classList.remove('btn-success');
                button.classList.add('btn-danger');
            } else {
                button.textContent = 'Make Unavailable';
                button.classList.remove('btn-danger');
                button.classList.add('btn-success');
            }
        }
    </script>
</body>

</html>
