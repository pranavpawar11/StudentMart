<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection file or establish connection here
include_once 'conn.php'; // Replace with your database connection file

// Function to handle image upload
function uploadImage($image)
{
    $targetDir = "../images/users/";
    $targetFile = $targetDir . basename($image["name"]);
    $sourceFile = $image["tmp_name"];

    // Check if the directory exists or create it
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory recursively
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($sourceFile, $targetFile)) {
        return "images/users/" . basename($image["name"]); // Return the path if successful
    } else {
        return false; // Return false if move fails
    }
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $userId = $_POST['user_id'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $photo = '';

    // Handle the photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = uploadImage($_FILES['photo']);
        if (!$photo) {
            http_response_code(500);
            echo "Failed to upload photo.";
            exit;
        }
    }

    // Update user information in the database
    try {
        if ($photo) {
            $sql = "UPDATE user SET fname=?, lname=?, email=?, phone=?, photo=? WHERE user_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$firstName, $lastName, $email, $phone, $photo, $userId]);
        } else {
            $sql = "UPDATE user SET fname=?, lname=?, email=?, phone=? WHERE user_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$firstName, $lastName, $email, $phone, $userId]);
        }

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            // Respond with a success message
            http_response_code(200);
            echo "User information updated successfully.";
        } else {
            // Respond with an error message
            http_response_code(400);
            echo "Failed to update user information.";
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
} else {
    // Respond with an error if the request method is not POST
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
