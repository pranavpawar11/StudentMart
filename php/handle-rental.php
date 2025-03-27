<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header FIRST
header('Content-Type: application/json');

// Start session and include connection
session_start();
require __DIR__ . '/conn.php';

// Verify PDO connection exists
if (!isset($pdo) || !($pdo instanceof PDO)) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

// Initialize response
$response = ['success' => false, 'message' => ''];

try {
    // Get and validate input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    if (empty($input['product_id']) || empty($input['plan_type'])) {
        throw new Exception('Missing required fields');
    }

    // Validate session
    if (empty($_SESSION['user_id'])) {
        throw new Exception('User not authenticated');
    }

    // Calculate end date based on plan type
    $end_date = date('Y-m-d');
    switch ($input['plan_type']) {
        case 'daily':
            $end_date = date('Y-m-d', strtotime('+7 days'));
            break;
        case 'weekly':
            $end_date = date('Y-m-d', strtotime('+1 week'));
            break;
        case 'monthly':
            $end_date = date('Y-m-d', strtotime('+1 month'));
            break;
        default:
            throw new Exception('Invalid plan type');
    }

    // Prepare and execute statement using PDO
    $stmt = $pdo->prepare("INSERT INTO rentals 
                          (user_id, product_id, plan_type, start_date, end_date, deposit_status, rental_status) 
                          VALUES (:user_id, :product_id, :plan_type, CURDATE(), :end_date, 'pending', 'requested')");
    
    // Bind parameters using bindParam
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $input['product_id'], PDO::PARAM_INT);
    $stmt->bindParam(':plan_type', $input['plan_type'], PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Rental request submitted successfully';
        $response['rental_id'] = $pdo->lastInsertId();
    } else {
        throw new Exception('Execute failed');
    }

} catch (PDOException $e) {
    http_response_code(400);
    $response['message'] = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    http_response_code(400);
    $response['message'] = $e->getMessage();
}

// Return JSON response
echo json_encode($response);
exit;
?>