<?php
// Include the database connection file
include ('conn.php');

// Check if user is logged in (user_id should be set in the session)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user_id is not set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or show an error message
    header('Location: login.php');
    exit;
}

// Fetch the count of products in the cart for the logged-in user
$user_id = $_SESSION['user_id'];
$query_cart = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = :user_id";
$stmt_cart = $pdo->prepare($query_cart);
$stmt_cart->bindParam(':user_id', $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->fetch(PDO::FETCH_ASSOC);
$cart_count = $result_cart['cart_count'];

// Fetch the count of products in the wishlist for the logged-in user
$query_wishlist = "SELECT COUNT(*) AS wishlist_count FROM wishlist WHERE user_id = :user_id";
$stmt_wishlist = $pdo->prepare($query_wishlist);
$stmt_wishlist->bindParam(':user_id', $user_id);
$stmt_wishlist->execute();
$result_wishlist = $stmt_wishlist->fetch(PDO::FETCH_ASSOC);
$wishlist_count = $result_wishlist['wishlist_count'];
?>

