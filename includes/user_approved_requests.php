<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">User not logged in</div>';
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $query = "
        SELECT 
            r.request_id,
            r.order_id,
            r.request_date,
            r.status AS request_status,
            r.message,
            o.product_id,
            o.buyer_id,
            o.seller_id,
            o.status AS order_status,
            o.order_date,
            o.address,
            o.total_price,
            p.product_name,
            p.product_description,
            p.product_price,
            p.img1 AS product_image,
            u.phone AS buyer_phone,
            u.fname AS buyer_fname,
            u.lname AS buyer_lname
        FROM 
            requests r
        JOIN 
            orders o ON r.order_id = o.order_id
        JOIN 
            products p ON o.product_id = p.product_id
        JOIN 
            user u ON o.buyer_id = u.user_id
        WHERE 
            o.seller_id = :user_id AND r.status = 'approved'
        ORDER BY 
            r.request_date DESC;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo '<div class="container">';
        echo '<h2 class="text-center mb-4">Approved Requests</h2>';
        echo '<div class="row">';

        foreach ($results as $request) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card border-light shadow-sm h-100">';
            echo '<img src="' . htmlspecialchars($request['product_image']) . '" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">';
            echo '<div class="card-body d-flex flex-column">';
            echo '<h5 class="card-title">' . htmlspecialchars($request['product_name']) . '</h5>';
            echo '<p class="card-text">' . htmlspecialchars($request['product_description']) . '</p>';
            echo '<p class="card-text"><strong>Buyer:</strong> ' . htmlspecialchars($request['buyer_fname']) . ' ' . htmlspecialchars($request['buyer_lname']) . '</p>';
            echo '<button class="btn btn-primary btn-block mt-auto view-more" data-toggle="modal" data-target="#detailsModal_' . $request['request_id'] . '">View Details</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Modal for each request
            echo '<div class="modal fade" id="detailsModal_' . $request['request_id'] . '" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel_' . $request['request_id'] . '" aria-hidden="true">';
            echo '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header bg-light">';
            echo '<h5 class="modal-title" id="detailsModalLabel_' . $request['request_id'] . '">' . htmlspecialchars($request['product_name']) . '</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<div class="row">';
            echo '<div class="col-md-5">';
            echo '<img src="' . htmlspecialchars($request['product_image']) . '" class="img-fluid" alt="Product Image">';
            echo '</div>';
            echo '<div class="col-md-7">';
            echo '<p><strong>Product Description:</strong><br>' . htmlspecialchars($request['product_description']) . '</p>';
            echo '<p><strong>Request Message:</strong><br>' . htmlspecialchars($request['message']) . '</p>';
            echo '<p><strong>Order Date:</strong> ' . htmlspecialchars($request['order_date']) . '</p>';
            echo '<p><strong>Total Price: </strong> ₹ ' . htmlspecialchars($request['total_price']) . '</p>';
            echo '<p><strong>Buyer Name:</strong> ' . htmlspecialchars($request['buyer_fname']) . ' ' . htmlspecialchars($request['buyer_lname']) . '</p>';
            echo '<p><strong>Buyer Address:</strong> ' . htmlspecialchars($request['address']) . '</p>';
            echo '<p><strong>Buyer Phone:</strong> ' . htmlspecialchars($request['buyer_phone']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            echo '<button class="btn btn-success complete-request" data-request-id="' . $request['request_id'] . '">Complete Request</button>';
            echo '<button class="btn btn-primary chat-with-buyer" data-buyer-phone="' . htmlspecialchars($request['buyer_phone']) . '">Chat with Buyer</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center">No approved requests found</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
}
?>
