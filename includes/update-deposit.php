<?php
header('Content-Type: application/json');
include('conn.php');

$response = ['success' => false, 'message' => ''];

try {
    $rent_id = $_GET['rent_id'] ?? 0;
    $status = $_GET['status'] ?? '';
    
    if(!in_array($status, ['collected', 'returned'])) {
        throw new Exception('Invalid deposit status');
    }
    
    $stmt = $pdo->prepare("UPDATE rentals SET deposit_status = :status WHERE rent_id = :rent_id");
    $stmt->execute([':status' => $status, ':rent_id' => $rent_id]);
    
    $response['success'] = true;
    $response['message'] = 'Deposit status updated successfully';
    
} catch(Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
exit;