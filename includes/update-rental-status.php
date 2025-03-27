<?php
header('Content-Type: application/json');
include('conn.php');

$response = ['success' => false, 'message' => ''];

try {
    $rent_id = $_GET['rent_id'] ?? 0;
    $status = $_GET['status'] ?? '';
    
    $valid_statuses = [
        'approved' => 'approved',
        'rejected' => 'rejected',
        'in_transit' => 'in_transit',
        'delivered' => 'delivered',
        'return_initiated' => 'return_initiated',
        'completed' => 'completed'
    ];
    
    if(!array_key_exists($status, $valid_statuses)) {
        throw new Exception('Invalid status update');
    }
    
    $stmt = $pdo->prepare("UPDATE rentals SET rental_status = :status WHERE rent_id = :rent_id");
    $stmt->execute([':status' => $valid_statuses[$status], ':rent_id' => $rent_id]);
    
    $response['success'] = true;
    $response['message'] = 'Rental status updated successfully';
    
} catch(Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
exit;