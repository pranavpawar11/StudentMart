<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require("conn.php"); // Include the database connection file  

// Function to upload image and return the path
function uploadImage($image, $seller_id)
{
    $targetDir = "../images/products/";
    $originalFileName = basename($image["name"]);
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $uniqueFileName = $seller_id . '_' . uniqid() . '.' . $fileExtension; // Unique filename
    $targetFile = $targetDir . $uniqueFileName;
    $sourceFile = $image["tmp_name"];
    $maxSize = 2 * 1024 * 1024; // 2MB in bytes
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];

    // Check if the directory exists or create it
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory recursively
    }

    // Check if the file size is within the limit
    if ($image["size"] > $maxSize) {
        return false;
    }

    // Check if the file type is allowed
    if (!in_array($image["type"], $allowedTypes)) {
        return false;
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($sourceFile, $targetFile)) {
        return "images/products/" . $uniqueFileName; // Return the path with unique filename if successful
    } else {
        return false; // Return false if move fails
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch user_id for the seller
    $seller_id = $_SESSION['user_id']; // Assuming you have a session variable for the user_id

    // Prepare SQL statement
    $sql = "INSERT INTO products (seller_id, category, product_name, authorbrand, product_description, product_price, duration_of_use, product_condition, product_status, available_area, img1, img2, img3, added_date) 
            VALUES (:seller_id, :category, :product_name, :brand_author, :product_description, :product_price, :duration_of_use, :product_condition, :product_status, :available_area, :img1, :img2, :img3, CURDATE())";

    // Prepare PDO statement
    $stmt = $pdo->prepare($sql);

    // Upload images with unique filenames using session user_id
    $img1 = uploadImage($_FILES["image1"], $seller_id);
    $img2 = uploadImage($_FILES["image2"], $seller_id);
    $img3 = uploadImage($_FILES["image3"], $seller_id);

    if ($img1 && $img2 && $img3) {
        // Images uploaded successfully, proceed with database insert
        $stmt->bindParam(':seller_id', $seller_id);
        $stmt->bindParam(':category', $_POST['category']);
        $stmt->bindParam(':product_name', $_POST['product_name']);
        $stmt->bindParam(':brand_author', $_POST['brand_author']);
        $stmt->bindParam(':product_description', $_POST['product_description']);
        $stmt->bindParam(':product_price', $_POST['product_price']);
        $stmt->bindParam(':duration_of_use', $_POST['duration_of_use']);
        $stmt->bindParam(':product_condition', $_POST['product_condition']);
        $stmt->bindParam(':product_status', $_POST['product_status']);
        $stmt->bindParam(':available_area', $_POST['available_area']);
        $stmt->bindParam(':img1', $img1);
        $stmt->bindParam(':img2', $img2);
        $stmt->bindParam(':img3', $img3);

        // Execute the statement
        try {
            $stmt->execute();
            echo "<script>alert('Product added successfully!');</script>";
            echo "<script>window.location.href = '../dashboard.php';</script>"; // Redirect to dashboard after successful insertion
            exit; // Ensure script execution stops after redirection
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Error handling if image upload failed
        echo "<script>alert('Failed to upload images. Please ensure each image is a valid image file and less than 2MB.');</script>";
        echo "<script>window.history.back();</script>"; // Go back to the previous page
        exit; // Ensure script execution stops after handling the error
    }
}
?>





<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h2 class="text-center mb-0">Add Product</h2>
                </div>
                <div class="card-body">
                    <form action="includes/add_product.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <select class="form-control" id="category" name="category" required onchange="changeLabels()">
                                <option value="" disabled selected>Select category</option>
                                <option value="electronics">Electronics</option>
                                <option value="books">Books</option>
                                <option value="drawings">Drawings</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_name">Product Name:</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                placeholder="Enter Product Name" required>
                        </div>
                        <div class="form-group">
                            <label for="brand_author">Brand or Author:</label>
                            <input type="text" class="form-control" id="brand_author" name="brand_author"
                                placeholder="Enter Brand Or Author Name" required>
                        </div>
                        <div class="form-group">
                            <label for="product_description">Description:</label>
                            <textarea class="form-control" id="product_description" name="product_description"
                                placeholder="Add detailed information about the product..." rows="4"
                                required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="product_price">Price (RS):</label>
                                <input type="number" class="form-control" id="product_price" name="product_price"
                                    min="0" placeholder="Ex. 100rs" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="duration_of_use">Usage Duration:</label>
                                <input type="text" class="form-control" id="duration_of_use" name="duration_of_use"
                                    placeholder="e.g., 6 months" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="product_condition">Condition:</label>
                                <select class="form-control" id="product_condition" name="product_condition" required>
                                    <option value="" disabled selected>Select condition</option>
                                    <option value="like new">Like New</option>
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="product_status">Status:</label>
                                <select class="form-control" id="product_status" name="product_status" required>
                                    <option value="" disabled >Select status</option>
                                    <option value="available" selected>Available</option>
                                    <option value="sold">Sold</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="available_area">Enter Area:</label>
                            <input type="text" class="form-control" id="available_area" name="available_area"
                                placeholder="Enter area" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="image1">Upload Image 1 (Max 2MB):</label>
                                <input type="file" class="form-control-file" id="image1" name="image1" accept="image/*"
                                    required>
                            </div>
                            <div class="form-group col">
                                <label for="image2">Upload Image 2 (Max 2MB):</label>
                                <input type="file" class="form-control-file" id="image2" name="image2" accept="image/*"
                                    required>
                            </div>
                            <div class="form-group col">
                                <label for="image3">Upload Image 3 (Max 2MB):</label>
                                <input type="file" class="form-control-file" id="image3" name="image3" accept="image/*"
                                    required>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changeLabels() {
        var category = document.getElementById("category").value;
        var nameLabel = document.querySelector("label[for='product_name']");
        var brandAuthorLabel = document.querySelector("label[for='brand_author']");

        if (category === "books") {
            nameLabel.textContent = "Book Name:";
            brandAuthorLabel.textContent = "Author:";
        } else {
            nameLabel.textContent = "Product Name:";
            brandAuthorLabel.textContent = "Brand:";
        }
    }
</script>
