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
            o.order_id,
            o.order_date,
            o.complete_date,
            o.status AS order_status,
            o.tracking_status,
            o.address,
            o.total_price,
            o.payment_mode,
            p.product_name,
            p.product_description,
            p.product_price,
            p.img1 AS product_image,
            u.phone AS buyer_phone,
            u.fname AS buyer_fname,
            u.lname AS buyer_lname
        FROM 
            orders o
        JOIN 
            products p ON o.product_id = p.product_id
        JOIN 
            user u ON o.buyer_id = u.user_id
        WHERE 
            o.seller_id = :user_id 
            AND o.status = 'completed'
            AND o.tracking_status = 'delivered'
        ORDER BY 
            o.complete_date DESC;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo '<div class="container">';
        echo '<h2 class="text-center mb-4">Completed Orders</h2>';
        echo '<div class="row">';

        foreach ($results as $order) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card border-light shadow-sm h-100">';
            echo '<img src="' . htmlspecialchars($order['product_image']) . '" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">';
            echo '<div class="card-body d-flex flex-column">';
            echo '<h5 class="card-title">' . htmlspecialchars($order['product_name']) . '</h5>';
            echo '<div class="tracking-status mb-3">';
            echo '<small class="text-muted">Tracking Status:</small>';
            echo '<div class="progress" style="height: 20px;">';
            echo '<div class="progress-bar bg-success" role="progressbar" style="width: 100%">Delivered</div>';
            echo '</div>';
            echo '</div>';
            echo '<p class="card-text"><strong>Completed on:</strong> ' . htmlspecialchars($order['complete_date']) . '</p>';
            echo '<button class="btn btn-primary btn-block mt-auto view-more" data-toggle="modal" data-target="#detailsModal_' . $order['order_id'] . '">View Details</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Modal for each order
            echo '<div class="modal fade" id="detailsModal_' . $order['order_id'] . '" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel_' . $order['order_id'] . '" aria-hidden="true">';
            echo '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header bg-light">';
            echo '<h5 class="modal-title" id="detailsModalLabel_' . $order['order_id'] . '">' . htmlspecialchars($order['product_name']) . '</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<div class="row">';
            echo '<div class="col-md-5">';
            echo '<img src="' . htmlspecialchars($order['product_image']) . '" class="img-fluid" alt="Product Image">';
            echo '</div>';
            echo '<div class="col-md-7">';
            echo '<p><strong>Tracking Status:</strong> Delivered</p>';
            echo '<p><strong>Product Description:</strong><br>' . htmlspecialchars($order['product_description']) . '</p>';
            echo '<p><strong>Order Date:</strong> ' . htmlspecialchars($order['order_date']) . '</p>';
            echo '<p><strong>Completed Date:</strong> ' . htmlspecialchars($order['complete_date']) . '</p>';
            echo '<p><strong>Total Price: </strong> ₹ ' . htmlspecialchars($order['total_price']) . '</p>';
            echo '<p><strong>Payment Mode: </strong> ' . htmlspecialchars($order['payment_mode']) . '</p>';
            echo '<p><strong>Buyer Name:</strong> ' . htmlspecialchars($order['buyer_fname']) . ' ' . htmlspecialchars($order['buyer_lname']) . '</p>';
            echo '<p><strong>Buyer Address:</strong> ' . htmlspecialchars($order['address']) . '</p>';
            echo '<p><strong>Buyer Phone:</strong> ' . htmlspecialchars($order['buyer_phone']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center">No completed orders found</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
}
?>