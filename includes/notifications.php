<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">User not logged in</div>';
    exit;
}

try {
    // Modified query to fetch all notifications without filtering by seller_id
    $query = "
    SELECT 
        n.notification_id,
        n.seller_id,
        n.buyer_id,
        n.product_id,
        n.notification_date,
        n.notification_type,
        n.is_read,
        p.product_name,
        p.product_description,
        p.img1 AS product_image,
        u.fname AS buyer_fname,
        u.lname AS buyer_lname,
        u.phone AS buyer_phone,
        o.address,
        o.pincode
    FROM 
        notifications n
    JOIN 
        products p ON n.product_id = p.product_id
    JOIN 
        user u ON n.buyer_id = u.user_id
    JOIN 
        orders o ON n.product_id = o.product_id AND n.buyer_id = o.buyer_id
    GROUP BY
        n.notification_id  -- Grouping by notification_id ensures no duplicates
    ORDER BY 
        n.notification_date DESC;
";



    $stmt = $pdo->prepare($query);
    // No binding required since there's no :user_id parameter anymore
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($notifications) {
        echo '<div class="container">';
        echo '<h2 class="text-center mb-4">Notifications</h2>';
        echo '<div class="row">';

        foreach ($notifications as $notification) {
            $is_read_class = $notification['is_read'] ? ' read' : ' unread';

            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card notification-card' . $is_read_class . '" data-id="' . $notification['notification_id'] . '">';
            echo '<div class="card-image-wrapper">';
            echo '<img src="' . htmlspecialchars($notification['product_image']) . '" class="card-img-top" alt="Product Image">';
            echo '</div>';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($notification['product_name']) . '</h5>';
            echo '<p class="card-text"><span class="badge badge-' . ($notification['notification_type'] == 'Order' ? 'success' : 'info') . '">' . htmlspecialchars($notification['notification_type']) . '</span></p>';
            echo '<p class="card-text"><small class="text-muted">' . htmlspecialchars($notification['notification_date']) . '</small></p>';
            echo '</div>';
            echo '<div class="card-footer">';
            echo '<button class="btn btn-primary btn-sm view-details" data-toggle="modal" data-target="#detailsModal_' . $notification['notification_id'] . '">View Details</button>';
            if (!$notification['is_read']) {
                echo '<button class="btn btn-outline-secondary btn-sm mark-as-read float-right" onclick="markAsRead(' . $notification['notification_id'] . ', this)">Mark as Read</button>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Modal code remains the same
            echo '<div class="modal fade" id="detailsModal_' . $notification['notification_id'] . '" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel_' . $notification['notification_id'] . '" aria-hidden="true">';
            echo '<div class="modal-dialog modal-dialog-centered modal-lg" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header bg-light">';
            echo '<h5 class="modal-title" id="detailsModalLabel_' . $notification['notification_id'] . '">' . htmlspecialchars($notification['product_name']) . '</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<div class="row">';
            echo '<div class="col-md-5">';
            echo '<img src="' . htmlspecialchars($notification['product_image']) . '" class="img-fluid" alt="Product Image">';
            echo '</div>';
            echo '<div class="col-md-7">';
            echo '<p><strong>Product Description:</strong><br>' . htmlspecialchars($notification['product_description']) . '</p>';
            echo '<p><strong>Notification Type:</strong><br>' . htmlspecialchars($notification['notification_type']) . '</p>';
            echo '<p><strong>Notification Date:</strong> ' . htmlspecialchars($notification['notification_date']) . '</p>';
            echo '<p><strong>Buyer Name:</strong> ' . htmlspecialchars($notification['buyer_fname']) . ' ' . htmlspecialchars($notification['buyer_lname']) . '</p>';
            echo '<p><strong>Buyer Phone:</strong> ' . htmlspecialchars($notification['buyer_phone']) . '</p>';
            echo '<p><strong>Address:</strong> ' . htmlspecialchars($notification['address']) . '</p>';
            echo '<p><strong>Pincode:</strong> ' . htmlspecialchars($notification['pincode']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            // echo '<a href="https://api.whatsapp.com/send?phone=' . htmlspecialchars($notification['buyer_phone']) . '" class="btn btn-success">Chat with Buyer</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center">No notifications found</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
}
?>
