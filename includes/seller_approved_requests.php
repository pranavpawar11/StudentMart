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
            o.product_id,
            o.buyer_id,
            o.seller_id,
            o.order_date,
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
            AND o.status = 'approved'
            AND o.tracking_status IN ('shipped', 'out_for_delivery')
        ORDER BY 
            o.order_date DESC;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display success or error messages if they exist
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success text-center">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger text-center">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }

    if ($results) {
        echo '<div class="container">';
        echo '<h2 class="text-center mb-4">Approved Orders</h2>';

        // Add JavaScript for WhatsApp chat
        echo '
        <script>
        function openWhatsApp(phone, orderInfo) {
            var message = "Hello! I\'m contacting you regarding your order on StudentMart: " + orderInfo;
            var whatsappURL = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
            window.open(whatsappURL, "_blank");
        }
        
        function updateOrderStatus(orderId, status) {
            fetch("update_order_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "order_id=" + orderId + "&status=" + status
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Error updating status: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while updating status");
            });
        }
        </script>
        ';

        echo '<div class="row">';

        foreach ($results as $order) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card border-light shadow-sm h-100">';
            echo '<img src="' . htmlspecialchars($order['product_image']) . '" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">';
            echo '<div class="card-body d-flex flex-column">';
            echo '<h5 class="card-title">' . htmlspecialchars($order['product_name']) . '</h5>';
            // Simplified status display
            echo '<div class="tracking-status mb-3">';
            echo '<span class="badge badge-info">Ready for Delivery</span>';
            echo '</div>';

            // Button container with flex layout
            echo '<div class="mt-auto d-flex flex-column gap-2">'; // Added gap between buttons
            echo '<button class="btn btn-primary btn-block view-more" data-toggle="modal" data-target="#detailsModal_' . $order['order_id'] . '">View Details</button>';
            echo '<button class="btn btn-success btn-block py-2" onclick="openWhatsApp(\'' . $order['buyer_phone'] . '\', \'' . $order['product_name'] . ' (Order ID: ' . $order['order_id'] . ')\')">';
            echo '<i class="fab fa-whatsapp mr-2"></i> Chat with Buyer'; // Added margin to the icon
            echo '</button>';
            echo '</div>'; // Close button container

            echo '</div>'; // Close card-body
            echo '</div>'; // Close card
            echo '</div>'; // Close col';

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
            echo '<p><strong>Status:</strong> Ready for Delivery</p>';
            echo '<p><strong>Product Description:</strong><br>' . htmlspecialchars($order['product_description']) . '</p>';
            echo '<p><strong>Order Date:</strong> ' . htmlspecialchars($order['order_date']) . '</p>';
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

            // WhatsApp chat button
            echo '<button class="btn btn-success" onclick="openWhatsApp(\'' . $order['buyer_phone'] . '\', \'' . $order['product_name'] . ' (Order ID: ' . $order['order_id'] . ')\')">
                <i class="fab fa-whatsapp"></i> Chat with Buyer
            </button>';

            // Single button to mark as delivered
            echo '<button class="btn btn-success" 
                    onclick="updateOrderStatus(' . $order['order_id'] . ', \'delivered\')">
                    Mark as Delivered
                  </button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center">No approved orders ready for delivery</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
}
?>