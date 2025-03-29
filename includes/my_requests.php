<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">User not logged in</div>';
    exit;
}

$buyer_id = $_SESSION['user_id'];

try {
    $query = "
    SELECT 
        o.order_id,
        o.product_id,
        o.seller_id,
        o.status AS order_status,
        o.tracking_status,
        o.payment_mode,
        o.order_date,
        o.complete_date,
        o.address,
        o.pincode,
        o.total_price,
        o.platform_fee_paid,
        p.product_name,
        p.product_description,
        p.product_price,
        p.img1 AS product_image,
        u.phone AS seller_phone,
        u.fname AS seller_fname,
        u.lname AS seller_lname,
        u.role AS seller_role
    FROM 
        orders o
    JOIN 
        products p ON o.product_id = p.product_id
    JOIN 
        user u ON o.seller_id = u.user_id
    WHERE 
        o.buyer_id = :buyer_id
        AND u.role != 'admin'  -- Exclude admin sellers
    ORDER BY 
        CASE 
            WHEN o.status = 'pending' THEN 1
            WHEN o.status = 'approved' THEN 2
            WHEN o.status = 'completed' THEN 3
            ELSE 4
        END,
        o.order_date DESC;
";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':buyer_id', $buyer_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add JavaScript for WhatsApp chat
    echo '
    <script>
    function openWhatsApp(phone, orderInfo) {
        var message = "Hello! I\'m contacting you regarding my order on StudentMart: " + orderInfo;
        var whatsappURL = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
        window.open(whatsappURL, "_blank");
    }
    </script>
    ';

    if ($results) {
        echo '<div class="container">';
        echo '<h2 class="text-center mb-4">My Orders</h2>';
        echo '<div class="row">';

        foreach ($results as $order) {
            $platformFeePaid = isset($order['platform_fee_paid']) && $order['platform_fee_paid'] == 1;
            $canChat = $platformFeePaid && $order['payment_mode'] == 'online' ? true : false;
            
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card border-light shadow-sm h-100">';
            echo '<img src="' . htmlspecialchars($order['product_image']) . '" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">';
            echo '<div class="card-body d-flex flex-column">';
            echo '<h5 class="card-title">' . htmlspecialchars($order['product_name']) . '</h5>';
            
            // Order status display
            echo '<div class="tracking-status mb-3">';
            echo '<small class="text-muted">Order Status:</small>';
            echo '<div class="progress" style="height: 20px;">';
            
            // Progress bar based on status
            if ($order['order_status'] == 'pending') {
                echo '<div class="progress-bar bg-secondary" role="progressbar" style="width: 25%">Pending</div>';
            } elseif ($order['order_status'] == 'approved') {
                echo '<div class="progress-bar bg-info" role="progressbar" style="width: 50%">Approved</div>';
            } elseif ($order['order_status'] == 'completed') {
                echo '<div class="progress-bar bg-success" role="progressbar" style="width: 100%">Delivered</div>';
            }
            
            echo '</div>';
            echo '</div>';
            
            // Payment status badge
            if ($order['payment_mode'] == 'online') {
                echo '<span class="badge badge-success mb-2">Paid Online</span>';
            } else {
                echo '<span class="badge badge-warning mb-2">Cash on Delivery</span>';
            }
            
            // Button container
            echo '<div class="mt-auto d-flex flex-column gap-2">';
            echo '<button class="btn btn-primary btn-block view-more" data-toggle="modal" data-target="#detailsModal_' . $order['order_id'] . '">View Details</button>';
            
            // Show chat button only if platform fee is paid AND payment is online
            if ($canChat) {
                echo '<button class="btn btn-success btn-block py-2" onclick="openWhatsApp(\'' . $order['seller_phone'] . '\', \'' . $order['product_name'] . ' (Order ID: ' . $order['order_id'] . ')\')">';
                echo '<i class="fab fa-whatsapp mr-2"></i> Chat with Seller';
                echo '</button>';
            }
            
            echo '</div>'; // Close button container
            echo '</div>'; // Close card-body
            echo '</div>'; // Close card
            echo '</div>'; // Close col

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
            echo '<p><strong>Order Status:</strong> ' . ucfirst($order['order_status']) . '</p>';
            echo '<p><strong>Product Description:</strong><br>' . htmlspecialchars($order['product_description']) . '</p>';
            echo '<p><strong>Order Date:</strong> ' . htmlspecialchars($order['order_date']) . '</p>';
            
            if ($order['order_status'] == 'completed') {
                echo '<p><strong>Delivery Date:</strong> ' . htmlspecialchars($order['complete_date']) . '</p>';
            }
            
            echo '<p><strong>Total Price:</strong> ₹' . htmlspecialchars($order['total_price']) . '</p>';
            echo '<p><strong>Payment Mode:</strong> ' . htmlspecialchars($order['payment_mode']) . '</p>';
            
            if ($canChat) {
                echo '<p><strong>Seller Name:</strong> ' . htmlspecialchars($order['seller_fname']) . ' ' . htmlspecialchars($order['seller_lname']) . '</p>';
                echo '<p><strong>Seller Contact:</strong> ' . htmlspecialchars($order['seller_phone']) . '</p>';
            } else {
                $reason = '';
                if ($order['payment_mode'] != 'online') {
                    $reason = ' (Cash on Delivery orders cannot chat)';
                } elseif (!$platformFeePaid) {
                    $reason = ' (Seller hasn\'t paid platform fee yet)';
                }
                echo '<div class="alert alert-warning">Chat with seller is not available'.$reason.'</div>';
            }
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            
            if ($canChat) {
                echo '<button class="btn btn-success" onclick="openWhatsApp(\'' . $order['seller_phone'] . '\', \'' . $order['product_name'] . ' (Order ID: ' . $order['order_id'] . ')\')">';
                echo '<i class="fab fa-whatsapp mr-2"></i> Chat with Seller';
                echo '</button>';
            }
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center">You have no Requests for the User Products You can visit my orders section to view other orders</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
}
?>