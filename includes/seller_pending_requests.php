<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">User not logged in</div>';
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Add platform_fee_paid column to the query
    $query = "
        SELECT 
            o.order_id,
            o.product_id,
            o.buyer_id,
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
            AND o.status = 'pending'
            AND o.tracking_status = 'placed'
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
        echo '<h2 class="text-center mb-4">Pending Orders</h2>';

        // Add JavaScript for platform fee payment
        echo '
        <script>
        function payPlatformFee(orderId, totalPrice) {
            const platformFee = Math.ceil(totalPrice * 0.05); // 5% of total price
            
            // Send platform fee data to platform_fee_payment.php using fetch
            fetch("platform_fee_payment.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    type: "platform_fee",
                    orderId: orderId,
                    platformFee: platformFee
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Platform fee payment response:", data);
                if (data.orderId) {
                    startPlatformFeePayment(data.orderId, platformFee);
                } else {
                    console.error("Failed to create platform fee order. Please try again.");
                    alert("Failed to create platform fee order. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error in fetch request:", error);
                alert("Error processing platform fee payment. Please try again.");
            });
        }

        function startPlatformFeePayment(orderId, platformFee) {
            const api_key = "rzp_test_8k9Y3Mmk6y9sy0";
            
            var options = {
                key: api_key,
                amount: platformFee * 100, // Convert to paise
                currency: "INR",
                name: "StudentMart",
                description: "Platform fee payment (5%)",
                image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
                order_id: orderId,
                theme: {
                    color: "#738276"
                },
                handler: function(response) {
                    // Submit the form with payment details
                    document.getElementById("razorpay-form").razorpay_payment_id.value = response.razorpay_payment_id;
                    document.getElementById("razorpay-form").razorpay_order_id.value = response.razorpay_order_id;
                    document.getElementById("razorpay-form").razorpay_signature.value = response.razorpay_signature;
                    document.getElementById("razorpay-form").submit();
                },
                callback_url: "platform_fee_success.php"
            };
            
            var rzp = new Razorpay(options);
            rzp.open();
        }
        
        function openWhatsApp(phone, orderInfo) {
            var message = "Hello! Im contacting you regarding your order on StudentMart: " + orderInfo;
            var whatsappURL = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
            window.open(whatsappURL, "_blank");
        }
        </script>
        ';

        // Add the form for Razorpay callback
        echo '
        <form id="razorpay-form" action="platform_fee_success.php" method="POST" style="display:none">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>
        ';

        echo '<div class="row">';

        foreach ($results as $order) {
            $platformFeePaid = isset($order['platform_fee_paid']) && $order['platform_fee_paid'] == 1;
            $platformFee = ceil($order['total_price'] * 0.05); // Calculate 5% platform fee

            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card border-light shadow">';
            echo '<img src="' . htmlspecialchars($order['product_image']) . '" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($order['product_name']) . '</h5>';
            echo '<p class="card-text">' . htmlspecialchars(substr($order['product_description'], 0, 100)) . '...</p>';
            echo '<div class="tracking-status mb-3">';
            echo '<small class="text-muted">Tracking Status:</small>';
            echo '<div class="progress" style="height: 20px;">';
            echo '<div class="progress-bar bg-secondary" role="progressbar" style="width: 25%">Placed</div>';
            echo '</div>';
            echo '</div>';

            echo '<div class="d-flex justify-content-between align-items-center">';
            echo '<button class="btn btn-primary view-more" data-toggle="modal" data-target="#detailsModal_' . $order['order_id'] . '">View Details</button>';

            if (!$platformFeePaid) {
                echo '<button class="btn btn-info pay-platform-fee" onclick="payPlatformFee(' . $order['order_id'] . ', ' . $order['total_price'] . ')">
                Pay Platform Fee (₹' . $platformFee . ')
                </button>';
            } else {
                echo '<button class="btn btn-success ship-order" onclick="updateOrderStatus(' . $order['order_id'] . ', \'shipped\')">
                Mark as Approved
                </button>';
            }

            echo '</div>';
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
            echo '<p><strong>Tracking Status:</strong> ' . ucwords(str_replace('_', ' ', $order['tracking_status'])) . '</p>';
            echo '<p><strong>Product Description:</strong><br>' . htmlspecialchars($order['product_description']) . '</p>';
            echo '<p><strong>Order Date:</strong> ' . htmlspecialchars($order['order_date']) . '</p>';
            echo '<p><strong>Total Price: </strong> ₹ ' . htmlspecialchars($order['total_price']) . '</p>';

            // Only show buyer info if platform fee is paid
            if ($platformFeePaid) {
                echo '<p><strong>Buyer Name:</strong> ' . htmlspecialchars($order['buyer_fname']) . ' ' . htmlspecialchars($order['buyer_lname']) . '</p>';
                echo '<p><strong>Buyer Address:</strong> ' . htmlspecialchars($order['address']) . '</p>';
                echo '<p><strong>Pincode:</strong> ' . htmlspecialchars($order['pincode']) . '</p>';
                echo '<p><strong>Buyer Phone:</strong> ' . htmlspecialchars($order['buyer_phone']) . '</p>';
            } else {
                echo '<div class="alert alert-warning">
                        <strong>Buyer information hidden!</strong> 
                        Pay the platform fee (₹' . $platformFee . ') to view buyer details.
                      </div>';
            }

            echo '<p><strong>Payment Mode:</strong> ' . htmlspecialchars($order['payment_mode']) . '</p>';
            if ($order['payment_mode'] == 'online') {
                echo '<p><strong>Payment Status:</strong> Done</p>';
            } else {
                echo '<p><strong>Payment Status:</strong> Pending (COD)</p>';
            }

            // Platform fee status
            if ($platformFeePaid) {
                echo '<p><strong>Platform Fee Status:</strong> <span class="badge badge-success">Paid</span></p>';
            } else {
                echo '<p><strong>Platform Fee Status:</strong> <span class="badge badge-warning">Not Paid</span></p>';
                echo '<p><strong>Platform Fee Amount:</strong> ₹' . $platformFee . ' (5% of order total)</p>';
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="modal-footer">';
            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';

            if (!$platformFeePaid) {
                echo '<button class="btn btn-info pay-platform-fee" onclick="payPlatformFee(' . $order['order_id'] . ', ' . $order['total_price'] . ')">
                Pay Platform Fee (₹' . $platformFee . ')
                </button>';
            } else {
                // Show WhatsApp chat button if platform fee is paid
                echo '<button class="btn btn-success" onclick="openWhatsApp(\'' . $order['buyer_phone'] . '\', \'' . $order['product_name'] . ' (Order ID: ' . $order['order_id'] . ')\')">
                <i class="fab fa-whatsapp"></i> Chat with Buyer
                </button>';

                echo '<button class="btn btn-success ship-order" onclick="updateOrderStatus(' . $order['order_id'] . ', \'shipped\')">
                Mark as Approved
                </button>';
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center">No pending orders found</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
}

// // Add the updateOrderStatus JavaScript function
// echo '

// ';
?>