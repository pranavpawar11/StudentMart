<?php
session_start();
header('Content-Type: application/json');
include 'conn.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$pdf_id = $data['pdfId'];

try {
    // Insert the PDF into the wishlist table
    $query = 'INSERT INTO pdf_wishlist (user_id, pdf_id, date_added) VALUES (:user_id, :pdf_id, NOW())';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':pdf_id', $pdf_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'PDF added to wishlist']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
