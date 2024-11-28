<?php
include 'conn.php';

$product_id = $_GET['id']; // Product ID from the URL

try {
    // Fetch reviews for the given product_id
    $sql = "SELECT reviews.*, user.fname, user.lname, user.photo FROM reviews 
            JOIN user ON reviews.user_id = user.user_id 
            WHERE product_id = ? 
            ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);

    // Check if there are any reviews
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reviews)) {
        // No reviews found, display a message
        echo '<p class="no-reviews-message">No reviews yet for this product. Be the first to review!</p> <hr>';
    } else {
        // Loop through and display each review
        foreach ($reviews as $row) {
            echo '<div class="review-item">';
            echo '<div class="flex-w flex-t p-b-68">';
            echo '<div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">';
            echo '<img src="' . htmlspecialchars($row['photo']) . '" alt="AVATAR">'; // User photo
            echo '</div>';
            echo '<div class="size-207">';
            echo '<div class="flex-w flex-sb-m p-b-17">';
            echo '<span class="mtext-107 cl2 p-r-20">' . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '</span>';
            echo '<span class="fs-18 cl11">';
            for ($i = 0; $i < 5; $i++) {
                if ($i < $row['rating']) {
                    echo '<i class="zmdi zmdi-star"></i>';
                } else {
                    echo '<i class="zmdi zmdi-star-outline"></i>';
                }
            }
            echo '</span>';
            echo '</div>';
            echo '<p class="stext-102 cl6">' . htmlspecialchars($row['review_text']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo "<hr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
