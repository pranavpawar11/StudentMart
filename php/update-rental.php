<?php
header('Content-Type: application/json');
include('conn.php');
session_start();

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized');
    }

    $action = $_GET['action'] ?? '';
    $rent_id = $_GET['rent_id'] ?? 0;

    if (!in_array($action, ['return_initiated', 'deposit_alert'])) {
        throw new Exception('Invalid action');
    }

    // Verify user owns this rental
    $stmt = $pdo->prepare("SELECT user_id FROM rentals WHERE rent_id = :rent_id");
    $stmt->bindParam(':rent_id', $rent_id, PDO::PARAM_INT);
    $stmt->execute();
    $rental = $stmt->fetch();

    if (!$rental || $rental['user_id'] != $_SESSION['user_id']) {
        throw new Exception('Not authorized');
    }

    // Process actions
    switch ($action) {
        case 'return_initiated':
            $stmt = $pdo->prepare("UPDATE rentals SET rental_status = 'return_initiated' WHERE rent_id = :rent_id");
            $stmt->bindParam(':rent_id', $rent_id, PDO::PARAM_INT);
            $stmt->execute();
            $response['message'] = "Return request initiated successfully";
            break;
            
        case 'deposit_alert':
            $stmt = $pdo->prepare("INSERT INTO support_tickets 
                                  (user_id, subject, message, ticket_type) 
                                  VALUES (:user_id, 'Deposit Return Issue', :message, 'deposit')");
            $message = "User reports not receiving deposit for rental #$rent_id";
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            $stmt->execute();
            $response['message'] = "Deposit ticket created. We'll contact you within 24 hours";
            break;
    }

    $response['success'] = true;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
exit;
?>