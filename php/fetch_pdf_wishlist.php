<?php 
session_start();
header('Content-Type: application/json');
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get all PDFs in the user's wishlist
    $query = "
        SELECT 
            p.pdf_id, 
            p.pdf_name, 
            p.pdf_path, 
            p.price, 
            p.img1, 
            p.upload_date, 
            w.date_added 
        FROM pdf_wishlist w
        JOIN pdf_documents p ON w.pdf_id = p.pdf_id
        WHERE w.user_id = :user_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($wishlist_items);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
