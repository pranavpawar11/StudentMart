<?php
// Include your database connection file
require_once 'conn.php';

// Check if POST data exists
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs (you should implement this based on your validation needs)
    $user_id = $_POST['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Fetch the stored password for the user
    $sql = "SELECT password FROM user WHERE user_id=?";
    
    try {
        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(1, $user_id);
        
        // Execute the query
        $stmt->execute();
        
        // Check if a row was returned
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];
            
            // Verify current password
            if (password_verify($current_password, $stored_password)) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update query for password
                $update_sql = "UPDATE user SET password=? WHERE user_id=?";
                
                // Prepare update statement
                $update_stmt = $pdo->prepare($update_sql);
                
                // Bind parameters
                $update_stmt->bindParam(1, $hashed_password);
                $update_stmt->bindParam(2, $user_id);
                
                // Execute update
                if ($update_stmt->execute()) {
                    http_response_code(200);
                    echo "Password updated successfully";
                } else {
                    http_response_code(400);
                    echo "Error updating password";
                }
            } else {
                echo "Current password is incorrect";
            }
        } else {
            echo "User not found";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close statement
    unset($stmt);
    unset($update_stmt);
}else{
    http_response_code(405);
}

// Close connection
$conn = null;
?>
