<?php
session_start();
header('Content-Type: application/json');
include 'conn.php';

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$pdf_id = $data['pdf_id'];
$rating = $data['rating'];
$review_text = $data['review_text'];

try {
    $sql = "INSERT INTO pdf_reviews (pdf_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pdf_id, $user_id, $rating, $review_text]);

    echo json_encode(["message" => "Review added successfully"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
