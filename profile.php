<?php
// Include the database connection file
include 'php/conn.php';
require_once  'php/check_subcription.php';
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$api_key = 'rzp_test_8k9Y3Mmk6y9sy0';
$api_secret = 'cgbCQ1yvbRMK3QM9z2jPhf0G';

$api = new Api($api_key, $api_secret);


if (isset($_SESSION['success_message'])) {

    echo `<script>console.log("Sucees of paymnt");</script>`;
    echo `<script>swal("Subcription Added", "Successfully Purchased Subcription", "success");</script>`;
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo `<script>console.log("Failure of paymnt");</script>`;
    echo `<script>swal("Error", "Subscription Process Failed due to Payment Error", "error");</script>`;
    unset($_SESSION['error_message']);
}
try {
    session_start();
    // Query to fetch user data
    $sql = "SELECT * FROM user WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $user_id = $_SESSION['user_id']; // Replace with the actual user ID
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Fetch user data as an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user["photo"] == null) {
        // $user["photo"] = "images\avatar-01.jpg";
        $user["photo"] = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
    }


    if ($user['subscription_status'] == 'active') {
        $subscription_status = true;

        // Fetch the subscription_id from the user_subscriptions table
        $query_subscription = "SELECT * FROM user_subscriptions WHERE user_id = :user_id";
        $stmt_subscription = $pdo->prepare($query_subscription);
        $stmt_subscription->bindParam(':user_id', $user_id);
        $stmt_subscription->execute();

        // Check if a subscription record is found
        if ($stmt_subscription->rowCount() > 0) {
            $user_subscription = $stmt_subscription->fetch(PDO::FETCH_ASSOC);
            $subscription_id = $user_subscription['subscription_id'];

            // Fetch subscription details from the subscriptions table using the subscription_id
            $query_subscription_details = "SELECT * FROM subscriptions WHERE id = :subscription_id";
            $stmt_details = $pdo->prepare($query_subscription_details);
            $stmt_details->bindParam(':subscription_id', $subscription_id);
            $stmt_details->execute();

            // Check if subscription details are found
            if ($stmt_details->rowCount() > 0) {
                $subscription_details = $stmt_details->fetch(PDO::FETCH_ASSOC);

                $start_date = new DateTime($user_subscription['start_date']);
                $end_date = new DateTime($user_subscription['end_date']);

                // Calculate the remaining days from now to the end date
                $now = new DateTime();
                $remaining_days = $now->diff($end_date)->format("%a days remaining");
            } else {
                $subscription_details = null;
            }
        } else {
            // If no subscription is found for the user in the user_subscriptions table
            $subscription_details = null;
        }
    } else {
        // If the subscription status in the users table is not active
        $subscription_status = false;
        $subscription_details = null;
    }
} catch (PDOException $e) {
    // Handle exceptions
    die("Error: " . $e->getMessage());
}
?>



<!--Website: wwww.codingdung.com-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="alert-container" class="alert-container"></div>
    <div class="container light-style flex-grow-1 container-p-y" style="margin-top: 10px;">
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <button onclick="history.back()" type="button" class="btn btn-primary" style="width: auto;">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                    <h4 class="font-weight-bold py-3 mb-4 text-center">Account Settings</h4>
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change Password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-subscription">Subscription</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-notifications">Notifications</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="account-general">

                            <form id="general-form" method="post" action="php/update_user.php"
                                enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                            class="d-block ui-w-80">
                                        <div class="media-body ml-4">
                                            <label class="btn btn-outline-primary">
                                                Upload New Photo
                                                <input type="file" id="upload-photo" name="photo"
                                                    onchange="previewImage(event)" class="account-settings-fileinput">
                                            </label>
                                            <button type="button" class="btn btn-default md-btn-flat ml-2"
                                                onclick="resetPhoto()">Reset</button>
                                            <div class="text-muted small mt-1">Allowed formats: JPG, GIF, PNG. Max size:
                                                800KB</div>
                                        </div>
                                    </div>
                                    <hr class="border-light">
                                    <div class="form-group">
                                        <label class="form-label">User ID</label>
                                        <input type="text" name="user_id" class="form-control mb-2"
                                            value="<?php echo htmlspecialchars($user['user_id']); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" id="first-name" class="form-control"
                                            value="<?php echo htmlspecialchars($user['fname']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" id="last-name" class="form-control"
                                            value="<?php echo htmlspecialchars($user['lname']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="<?php echo htmlspecialchars($user['email']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" class="form-control"
                                            value="<?php echo htmlspecialchars($user['phone']); ?>">
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="button" class="btn btn-primary"
                                            onclick="saveGeneralChanges()">Save General Changes</button>
                                    </div>
                                </div>
                            </form>



                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                        class="d-block ui-w-80">
                                    <div class="media-body ml-4">
                                        <label class="form-label">Name</label>
                                        <?php echo htmlspecialchars($user['fname']); ?>
                                        <?php echo htmlspecialchars($user['lname']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                    <div class="media-body ml-4">
                                        <label class="form-label">email</label>
                                        <?php echo htmlspecialchars($user['email']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                </div>
                                <hr class="border-light">
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" id="curr_pass" placeholder="Enter Current Password"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" id="new_pass" class="form-control"
                                        placeholder="Enter new Password">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" id="confirm_new_pass" class="form-control"
                                        placeholder="Re-Enter New Password">
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" onclick="savePasswordChanges()">Save
                                        Password Changes</button>
                                </div>
                            </div>
                        </div>

                        <!-- Info Tab -->
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                        class="d-block ui-w-80">
                                    <div class="media-body ml-4">
                                        <label class="form-label">Name</label>
                                        <?php echo htmlspecialchars($user['fname']); ?>
                                        <?php echo htmlspecialchars($user['lname']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                    <div class="media-body ml-4">
                                        <label class="form-label">email</label>
                                        <?php echo htmlspecialchars($user['email']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                </div>
                                <hr class="border-light">
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                    <select class="custom-select">
                                        <option selected>India</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control"
                                        value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control"
                                        value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>
                                <!-- <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary" onclick="saveInfoChanges()">Save Info
                                        Changes</button>
                                </div> -->
                            </div>
                        </div>

                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="account-notifications">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                        class="d-block ui-w-80">
                                    <div class="media-body ml-4">
                                        <label class="form-label">Name</label>
                                        <?php echo htmlspecialchars($user['fname']); ?>
                                        <?php echo htmlspecialchars($user['lname']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                    <div class="media-body ml-4">
                                        <label class="form-label">email</label>
                                        <?php echo htmlspecialchars($user['email']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                </div>
                                <hr class="border-light">
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone requests for a product</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone confirms an order</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when an order is confirmed</span>
                                    </label>
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary"
                                        onclick="saveNotificationsChanges()">Save Notifications Changes</button>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Management Tab Content -->
                        <div class="tab-pane fade" id="account-subscription">
                            <div class="card-body">
                                <div class="media align-items-center">
                                    <img id="preview-image" src="<?php echo $user['photo']; ?>" alt="User Photo"
                                        class="d-block ui-w-80">
                                    <div class="media-body ml-4">
                                        <label class="form-label">Name</label>
                                        <?php echo htmlspecialchars($user['fname']); ?>
                                        <?php echo htmlspecialchars($user['lname']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                    <div class="media-body ml-4">
                                        <label class="form-label">email</label>
                                        <?php echo htmlspecialchars($user['email']); ?>
                                        <div class="text-muted small mt-1"></div>
                                    </div>
                                </div>
                                <hr class="border-light">
                                <!-- <h4 class="mb-4 text-center">Subscription Management</h4> -->

                                <!-- Current Subscription Status -->
                                <div class="current-subscription mb-5">
                                    <?php if ($subscription_status): ?>
                                        <div class="card border-success">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h4 class="card-title text-success mb-3">Active Subscription</h4>
                                                        <div class="subscription-details">
                                                            <p class="mb-2"><strong>Plan:</strong>
                                                                <?= htmlspecialchars($subscription_details['name']) ?></p>
                                                            <p class="mb-2"><strong>Valid Till:</strong>
                                                                <?= htmlspecialchars($end_date->format('d-m-Y')) ?>
                                                                (<?= $remaining_days ?>)
                                                            </p>
                                                            <p class="mb-0"><strong>Status:</strong> <span
                                                                    class="badge bg-success">Active</span></p>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-4 text-md-end">
                                                        <button class="btn btn-outline-primary"
                                                            onclick="showUpgradeOptions()">
                                                            Upgrade Plan
                                                        </button>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="card border-warning">
                                            <div class="card-body">
                                                <h4 class="card-title text-warning mb-3">No Active Subscription</h4>
                                                <p class="card-text">Choose from our subscription plans below to access
                                                    premium features.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Subscription Plans -->
                                <?php if (!$subscription_status): ?>
                                    <div class="subscription-plans">
                                        <h3 class="mb-4">Available Plans</h3>
                                        <div class="row g-4" id="subscription-list">
                                            <!-- Plans will be populated via JavaScript -->
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Upgrade Options (Hidden by default) -->
                                <div class="upgrade-options d-none">
                                    <h3 class="mb-4">Upgrade Options</h3>
                                    <div class="row g-4" id="upgrade-plans-list">
                                        <!-- Upgrade plans will be populated via JavaScript -->
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Modal -->
                            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentModalLabel">Confirm Subscription</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="subscription-summary">
                                                <h4 id="subscription-name" class="mb-3"></h4>
                                                <p id="subscription-description" class="mb-3"></p>
                                                <div class="price-summary p-3 bg-light rounded">
                                                    <p class="mb-0"><strong>Amount: ₹<span
                                                                id="subscription-price"></span></strong></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" id="buy-now-btn">Buy
                                                Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>


    <!-- add image -->
    <script>
        function previewImage(event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var imageFile = input.files[0];
                var imageUrl = URL.createObjectURL(imageFile);
                document.getElementById('preview-image').src = imageUrl;
            }
        }

        function saveGeneralChanges() {
            var formData = new FormData(document.getElementById('general-form'));

            fetch('php/update_user.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    showAlert('User information updated successfully.', 'success');
                })
                .catch(error => {
                    showAlert('Error updating user information.', 'danger');
                    console.error('Error:', error);
                });
        }




        // Function to display Bootstrap alert
        function showAlert(message, type) {
            var alertContainer = document.getElementById('alert-container');
            if (alertContainer) {
                var alertDiv = document.createElement('div');
                alertDiv.classList.add('alert', 'alert-' + type, 'alert-dismissible', 'fade', 'show');
                alertDiv.innerHTML = `
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                ${message}
            `;
                alertContainer.appendChild(alertDiv);

                // Automatically close the alert after 5 seconds
                setTimeout(function () {
                    alertDiv.classList.remove('show');
                    setTimeout(function () {
                        alertDiv.remove();
                    }, 300); // remove after fading out
                }, 5000); // 5 seconds
            }
        }

        function savePasswordChanges() {
            var userId = '<?php echo $user['user_id']; ?>';
            var curr_pass = document.getElementById('curr_pass').value;
            var new_pass = document.getElementById('new_pass').value;
            var confirm_new_pass = document.getElementById('confirm_new_pass').value;

            var formData = new FormData();
            formData.append('user_id', userId);
            formData.append('current_password', curr_pass);
            formData.append('new_password', new_pass);

            if (curr_pass == "") {
                showAlert('Enter Old Password', 'danger');
            }
            if (curr_pass == new_pass) {
                showAlert('Old Password and New are Same.', 'danger');
            }
            if (new_pass == confirm_new_pass && new_pass != "") {
                fetch('php/change_password.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        // Success message
                        showAlert('Password changed successfully.', 'success');
                    })
                    .catch(error => {
                        // Error message
                        showAlert('Error in updating Password.', 'danger');
                        console.error('Error:', error);
                    });
            } else {
                showAlert('New Password dose not Match.', 'danger');
            }
        }
    </script>
    <script>
        // Your existing subscriptions array
        const subscriptions = [
            { id: 1, name: "Basic Plan", description: "This is a basic plan with limited features.", price: 500, duration: 30 },
            { id: 2, name: "Standard Plan", description: "This plan offers more features and support.", price: 1000, duration: 30 },
            { id: 3, name: "Premium Plan", description: "The premium plan offers all features and priority support.", price: 2000, duration: 30 },
        ];

        // Function to render subscription cards
        function renderSubscriptionCards(containerId, plans, isUpgrade = false) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            plans.forEach(sub => {
                if (isUpgrade && sub.price <= currentSubscriptionPrice) return;

                const cardHTML = `          
                <div class="subscription-card">
                    <h4>${sub.name}</h4>
                    <p>${sub.description}</p>
                    <p><strong>₹${sub.price}</strong></p>
                    <p>Duration: ${sub.duration} days</p>
                    <button class="btn btn-primary btn-buy" onclick="openPaymentModal(${sub.id})">
                        ${isUpgrade ? 'Upgrade Now' : 'Subscribe Now'}
                    </button>
                </div> `;
                container.innerHTML += cardHTML;
            });
        }

        // Function to show upgrade options
        function showUpgradeOptions() {
            document.querySelector('.subscription-plans').classList.add('d-none');
            document.querySelector('.upgrade-options').classList.remove('d-none');
            renderSubscriptionCards('upgrade-plans-list', subscriptions, true);
        }

        // Your existing openPaymentModal function
        function openPaymentModal(subscriptionId) {
            const subscription = subscriptions.find(sub => sub.id === subscriptionId);
            document.getElementById('subscription-name').innerText = subscription.name;
            document.getElementById('subscription-description').innerText = subscription.description;
            document.getElementById('subscription-price').innerText = subscription.price;

            // Store the subscription id to use when payment is successful
            document.getElementById('buy-now-btn').setAttribute('data-subscription-id', subscriptionId);
            $('#paymentModal').modal('show');
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function () {
            // Render initial subscription cards
            if (!window.subscription_status) {
                renderSubscriptionCards('subscription-list', subscriptions);
            }

            // Keep your existing event listener for the buy button
            document.getElementById('buy-now-btn').addEventListener('click', buySubscription);
        });

        // Function to initiate Razorpay payment
        function buySubscription(e) {
            const subscriptionId = document.getElementById('buy-now-btn').getAttribute('data-subscription-id');
            const subscription = subscriptions.find(sub => sub.id === parseInt(subscriptionId));

            // Send subscription data to payment_index.php using fetch
            fetch('payment_index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: 'subscription',
                    subscriptionId: subscriptionId,
                    subscriptionName: subscription.name,
                    price: subscription.price
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Payment response:', data);
                    if (data.orderId) {
                        startPayment(data.orderId, subscription.price);
                    } else {
                        console.error('Failed to create order. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error in fetch request:', error);
                });
        }

        function startPayment(orderId, subscription) {
            const api_key = 'rzp_test_8k9Y3Mmk6y9sy0';  // Make sure to use your Razorpay API key

            var options = {
                key: api_key,
                amount: subscription.price * 100, // Convert the price to paise (100 paise = 1 INR)
                currency: 'INR',
                name: 'Your Company Name',
                description: 'Payment for your order',
                image: 'https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png',
                order_id: orderId,
                theme: {
                    color: '#738276'
                },
                callback_url: 'http://localhost/StudentMart/payment_success.php'
            };

            var rzp = new Razorpay(options);
            rzp.open();
        }

    </script>




    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vendor/sweetalert/sweetalert.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>

</html>