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
    // Delete the PDF from the wishlist table
    $query = 'DELETE FROM pdf_wishlist WHERE user_id = :user_id AND pdf_id = :pdf_id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':pdf_id', $pdf_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'PDF removed from wishlist']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
