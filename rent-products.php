<?php
// Include the database connection file
include('php/cart-wishlist-notification.php');
include('php/conn.php');
?>
<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

// Get user rentals from database
try {
	$user_id = $_SESSION['user_id'];
	$stmt = $pdo->prepare("SELECT r.*, p.product_name, p.img1 
                          FROM rentals r
                          JOIN products p ON r.product_id = p.product_id
                          WHERE r.user_id = :user_id");
	$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
	$stmt->execute();
	$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	$rentals = [];
	// You might want to log this error
	error_log("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Shoping Cart</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
		integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link rel="icon" type="image/png" href="images/icons/favicon.png" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link rel="stylesheet" href="css/myOrders.css">
	<!--===============================================================================================-->
	<style>
		/* Enhanced Rental Section Styles */
		.rental-container {
			background-color: #f8f9fa;
			padding: 20px;
			border-radius: 12px;
		}

		.rental-card {
			border: 1px solid #e0e0e0;
			border-radius: 12px;
			overflow: hidden;
			transition: all 0.3s ease;
			background-color: white;
			margin-bottom: 20px;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
		}

		.rental-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
		}

		.rental-card img {
			height: 100%;
			object-fit: cover;
			transition: transform 0.3s ease;
		}

		.rental-card:hover img {
			transform: scale(1.05);
		}

		.rental-progress .progress {
			height: 10px;
			border-radius: 5px;
			background-color: #e9ecef;
		}

		.rental-progress .progress-bar {
			background: linear-gradient(to right, #2c3e50, #3498db);
			transition: width 0.8s cubic-bezier(0.25, 0.1, 0.25, 1);
		}

		.status-labels span {
			font-size: 0.8rem;
			font-weight: 500;
			color: #6c757d;
			position: relative;
			padding-left: 20px;
			transition: color 0.3s ease;
		}

		.status-labels span::before {
			content: "";
			position: absolute;
			left: 0;
			top: 50%;
			transform: translateY(-50%);
			width: 12px;
			height: 12px;
			border-radius: 50%;
			background-color: #e0e0e0;
			transition: background-color 0.3s ease;
		}

		.status-labels .text-primary {
			color: #2c3e50;
			font-weight: 600;
		}

		.status-labels .text-primary::before {
			background-color: #2c3e50;
			box-shadow: 0 0 10px rgba(44, 62, 80, 0.4);
		}

		.rental-details {
			background-color: #f1f3f5;
			border-radius: 8px;
			padding: 15px;
			margin-top: 15px;
		}

		.btn-outline-dark {
			border-width: 2px;
			transition: all 0.3s ease;
		}

		.btn-outline-dark:hover {
			background-color: #2c3e50;
			color: white !important;
		}

		.btn-link.text-danger {
			transition: color 0.3s ease;
		}

		.btn-link.text-danger:hover {
			color: #dc3545 !important;
			text-decoration: underline;
		}

		.badge {
			transition: background-color 0.3s ease;
		}

		.badge:hover {
			background-color: #ffc107 !important;
		}

		/* Responsive Adjustments */
		@media (max-width: 768px) {
			.rental-card .col-md-3 {
				max-height: 250px;
			}

			.rental-details {
				text-align: center;
			}

			.rental-details .col-md-4 {
				margin-bottom: 10px;
			}
		}
	</style>
</head>

<body class="animsition">
	<div id="confirmLogoutModal" class="modal">
		<div class="modal-content">
			<button class="close" onclick="closeConfirmLogout()">&times;</button>
			<p>Are you sure you want to logout?</p>
			<button class="confirm" onclick="logout()">Yes</button>
			<button class="cancel" onclick="closeConfirmLogout()">No</button>
		</div>
	</div>
	<!-- Header -->
	<header class="header-v4">
		<!-- Header desktop -->
		<div class="container-menu-desktop">
			<!-- Topbar -->
			<div class="top-bar">
				<div class="content-topbar flex-sb-m h-full container">
					<div class="left-top-bar">
						Discover and Shop Your Study Essentials Here!
					</div>

					<div class="right-top-bar flex-w h-full">
						<!-- <a href="#" class="flex-c-m trans-04 p-lr-25">
							Help & FAQs
						</a> -->

						<a href="dashboard.php" class="flex-c-m trans-04 p-lr-25">
							Dashboard
						</a>
						<a href="profile.php" class="flex-c-m trans-04 p-lr-25">
							My Account
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							ENG
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">

							<button onclick="showConfirmLogout()" class="text-secondary"><i
									class="fa-solid fa-right-from-bracket px-1 text-secondary"> </i>Logout</button>

						</a>

					</div>
				</div>
			</div>

			<div class="wrap-menu-desktop how-shadow1">
				<nav class="limiter-menu-desktop container">

					<!-- Logo desktop -->
					<a href="#" class="logo">
						<h2 style="color: black;">StudentMart</h2>
						<!-- <img src="images/icons/logo-01.png" alt="IMG-LOGO"> -->
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li>
								<a href="index.php">Home</a>
							</li>
							<li>
								<a href="product.php">Products</a>
							</li>
							<li>
								<a href="shop_pdf.php">PDFs</a>
							</li>

							<li>
								<a href="shoping-cart.php">Cart</a>
							</li>

							<li>
								<a href="my-orders.php">My Orders</a>
							</li>

							<li class="active-menu">
								<a href="rent-products.php">Rent</a>
							</li>
							<!-- <li>
								<a href="dashboard.php">Dashboard</a>
							</li> -->

						</ul>
					</div>

					<!-- Icon header desktop -->
					<div class="wrap-icon-header flex-w flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 active-menu" id="cartIcon">
							<a href="shoping-cart.php"><i class="zmdi zmdi-shopping-cart"></i></a>
						</div>

						<div class="dropdown icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11  js-show-cart"
							id="wishlistDropdown">
							<i class="zmdi zmdi-favorite-outline" onclick="fetchWishlist()"></i>
							<div class="dropdown-content">
								<a href="#" onclick="fetchWishlist()">Products</a>
								<a href="#" onclick="fetchPdfWishlist()">PDFs</a>
							</div>
						</div>
					</div>

				</nav>
			</div>
		</div>

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<!-- Logo moblie -->
			<div class="logo-mobile">
				<a href="index.php">
					<h4 style="color: black;">StudentMart</h4>
					<!-- <img src="images/icons/logo-01.png" alt="IMG-LOGO"></a> -->
			</div>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-w flex-r-m m-r-15">


				<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 active-menu">
					<a href="shoping-cart.php"><i class="zmdi zmdi-shopping-cart"></i></a>
				</div>

				<!-- <div class="dropdown cl2 hov-cl1 trans-04 p-l-22 p-r-11" id="wishlistDropdown">
					<i class="zmdi zmdi-favorite-outline" onclick="fetchWishlist()"></i>
					<div class="dropdown-content">
						<a href="#" onclick="fetchWishlist()">Products</a>
						<a href="#" onclick="fetchPdfWishlist()">PDFs</a>
					</div>
				</div> -->

				<div class="user-dropdown">
					<button class="dropdown-toggle">
						<!-- <img src="images/users/pranav.jpg" alt="User" class="user-avatar"> -->
						<i class="fa-solid fa-user"></i>
						<!-- <span class="username">John Doe</span> -->
						<!-- <i class="fas fa-chevron-down"></i> -->
					</button>
					<div class="dropdown-menu">
						<a href="profile.php" class="dropdown-item">
							<i class="fas fa-user"></i>
							Profile
						</a>
						<a class="dropdown-item" onclick="showConfirmLogout()">
							<i class="fas fa-sign-out-alt"></i>
							Logout
						</a>
					</div>
				</div>
			</div>

			<!-- Button show menu -->
			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>


		<!-- Menu Mobile -->
		<div class="menu-mobile">

			<ul class="main-menu">
				<li>
					<a href="index.php">Home</a>
				</li>
				<li>
					<a href="product.php">Products</a>
				</li>
				<li>
					<a href="shop_pdf.php">PDFs</a>
				</li>

				<li>
					<a href="shoping-cart.php">Cart</a>
				</li>

				<li class="active-menu">
					<a href="my-orders.php">My Orders</a>
				</li>
			</ul>
		</div>


	</header>

	<!-- Wishlist -->
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-cart"></div>
		<div class="header-cart flex-col-l p-l-65 p-r-25">
			<div class="header-cart-title flex-w flex-sb-m p-b-8">
				<span class="mtext-103 cl2">
					my Wishlist
				</span>

				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>

			<div class="header-cart-content flex-w js-pscroll">
				<ul class="header-cart-wrapitem w-full">
					<!-- Wishlist items will be inserted here dynamically -->
				</ul>

				<div class="w-full">
					<div class="header-cart-total w-full p-tb-40">
						<!-- Total will be inserted here dynamically -->
					</div>

					<div class="header-cart-buttons flex-w w-full">
						<a href="shoping-cart.php"
							class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
							View Cart
						</a>

						<a href="profile.php"
							class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Subscribtion
						</a>
					</div>
				</div>
			</div>

		</div>
	</div>


	<!-- breadcrumb -->
	<div class="container py-3">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Rent
			</span>
		</div>
	</div>

	<!-- Add this after the breadcrumb section -->
	<div class="container py-3">
		<div class="row">
			<div class="col-12">
				<!-- <h3 class="my-4">My Rentals</h3> -->
				<div class="rental-container" id="rentalsContainer">
					<?php if (empty($rentals)): ?>
						<div class="alert alert-info">
							You don't have any rental items yet.
						</div>
					<?php else: ?>
						<?php foreach ($rentals as $rental):
							$status_progress = [
								'requested' => 0,
								'approved' => 33,
								'in_transit' => 33,
								'delivered' => 66,
								'return_initiated' => 80,
								'completed' => 100
							];
							$plan_rates = [
								'daily' => 20,   // Per day rate
								'weekly' => 130, // Per week rate
								'monthly' => 200 // Per month rate
							];

							// Plan-specific discount calculations
							$plan_discounts = [
								'daily' => 1,    // No discount for daily
								'weekly' => 0.9, // 10% discount for weekly
								'monthly' => 1 // 20% discount for monthly
							];

							// Calculate total days of rental
							$rental_start = strtotime($rental['start_date']);
							$rental_end = strtotime($rental['end_date']);
							$days_rented = max(1, ceil(($rental_end - $rental_start) / (60 * 60 * 24)));

							// Calculate remaining days
							$current_time = time();
							$remaining_days = max(0, ceil(($rental_end - $current_time) / (60 * 60 * 24)));

							// Comprehensive cost calculation
							switch ($rental['plan_type']) {
								case 'daily':
									// Simple daily calculation
									$total_cost = $days_rented * $plan_rates['daily'];
									break;

								case 'weekly':
									// Calculate full weeks and remaining days
									$weeks = floor($days_rented / 7);
									$remaining_rental_days = $days_rented % 7;

									$weekly_cost = $weeks * $plan_rates['weekly'];
									$daily_cost = $remaining_rental_days * $plan_rates['daily'];

									$total_cost = $weekly_cost + $daily_cost;
									break;

								case 'monthly':
									// Calculate full months and remaining days
									$months = floor($days_rented / 30);
									$remaining_rental_days = $days_rented % 30;

									$monthly_cost = $months * $plan_rates['monthly'];
									$daily_cost = $remaining_rental_days * $plan_rates['daily'];

									$total_cost = $monthly_cost + $daily_cost;
									break;

								default:
									// Fallback to daily calculation
									$total_cost = $days_rented * $plan_rates['daily'];
							}

							// Apply plan-specific discount
							$total_cost = round($total_cost * $plan_discounts[$rental['plan_type']]);

							// Additional context for rental duration
							$rental_duration_details = [
								'total_days' => $days_rented,
								'remaining_days' => $remaining_days,
								'start_date' => date('Y-m-d', $rental_start),
								'end_date' => date('Y-m-d', $rental_end)
							];
							?>
							<div class="rental-card mb-4">
								<div class="card shadow-sm">
									<div class="row g-0">
										<div class="col-md-3">
											<img src="<?= htmlspecialchars($rental['img1']) ?>" class="img-fluid rounded-start"
												alt="Product Image">
										</div>
										<div class="col-md-9">
											<div class="card-body">
												<div class="d-flex justify-content-between align-items-center mb-3">
													<h5 class="card-title"><?= htmlspecialchars($rental['product_name']) ?></h5>
													<span class="badge bg-warning text-dark">
														<?= ucfirst($rental['plan_type']) ?> Plan
													</span>
												</div>
												<div class="rental-progress mb-3">
													<div class="progress">
														<div class="progress-bar" role="progressbar"
															style="width: <?= $status_progress[$rental['rental_status']] ?>%">
														</div>
													</div>
													<div class="status-labels d-flex justify-content-between mt-2">
														<?php foreach (['requested', 'approved', 'delivered', 'completed'] as $status): ?>
															<span
																class="<?= $rental['rental_status'] == $status ? 'text-primary' : 'text-muted' ?>">
																<?= ucfirst($status) ?>
															</span>
														<?php endforeach; ?>
													</div>
												</div>
												<div class="rental-details">
													<div class="row">
														<div class="col-md-4">
															<p class="mb-1 py-2 "><strong>Start Date:</strong>
																<?= htmlspecialchars($rental['start_date']) ?>
															</p>
															<p class="mb-1 py-2"><strong>Remaining Days:</strong>
																<?= $remaining_days ?> days
															</p>
														</div>
														<div class="col-md-4">
															<p class="mb-1 py-2"><strong>Deposit Status:</strong>
																<span
																	class="text-<?= $rental['deposit_status'] == 'collected' ? 'success' : 'danger' ?>">
																	<?= ucfirst($rental['deposit_status']) ?>
																</span>
															</p>
															<p class="mb-1 py-2"><strong>Total Rental Cost:</strong>
																₹<?= $total_cost ?>
															</p>
														</div>
														<div class="col-md-4">
															<?php if ($rental['rental_status'] == 'delivered'): ?>
																<button class="btn btn-outline-dark btn-sm"
																	onclick="showReturnRequest(<?= $rental['rent_id'] ?>)">
																	<i class="fas fa-undo"></i> Initiate Return
																</button>
															<?php endif; ?>
															<button class="btn btn-link text-danger btn-sm"
																onclick="showDepositAlert(<?= $rental['rent_id'] ?>)">
																<i class="fas fa-exclamation-circle"></i> Deposit Alert
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->
	<footer class="student-mart-footer">
		<div class="footer-container">
			<div class="footer-section about">
				<h3>Student Mart</h3>
				<p>Your campus marketplace for buying and selling used study materials.</p>
				<div class="footer-features">
					<span class="feature"><i class="fas fa-book"></i> Textbooks</span>
					<span class="feature"><i class="fas fa-pencil-alt"></i> Stationery</span>
					<span class="feature"><i class="fas fa-laptop"></i> Electronics</span>
				</div>
			</div>
			<div class="footer-section links">
				<h3>Quick Links</h3>
				<ul>
					<li><a href="">Home</a></li>
					<li><a href="#">Buy</a></li>
					<li><a href="dashboard.php">Sell</a></li>
					<li><a href="#">Categories</a></li>
					<li><a href="#">How It Works</a></li>
				</ul>
			</div>
			<div class="footer-section contact">
				<h3>Contact Us</h3>
				<p>Email: pranavpawar745@gmail.com</p>
				<p>Phone: 7709176271</p>
				<div class="social-icons">
					<a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
					<a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
					<a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
				</div>
			</div>
			<div class="footer-section newsletter">
				<h3>Stay Updated</h3>
				<p>Subscribe to our newsletter for the latest deals and campus news!</p>
				<form class="newsletter-form">
					<input type="email" placeholder="Your email address">
					<button type="submit">Subscribe</button>
				</form>
			</div>
		</div>
		<div class="footer-bottom">
			<p>&copy; 2024 Student Mart. All rights reserved.</p>
			<p><a href="#terms">Terms of Service</a> | <a href="#privacy">Privacy Policy</a></p>
		</div>
	</footer>


	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

	<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function () {
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
	<!--===============================================================================================-->
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function () {
			$(this).css('position', 'relative');
			$(this).css('overflow', 'hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function () {
				ps.update();
			})
		});
	</script>
	<!--===============================================================================================-->



	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const defaultProduct = <?php echo json_encode($default_product); ?>;
			if (!defaultProduct) {
				document.getElementById('address-block').style.display = 'none';
				document.getElementById('message-block').style.display = 'none'; // Hide message block initially
			} else {
				// Set default product details if available
				selectToBuy(
					defaultProduct.product_id,
					defaultProduct.product_name,
					defaultProduct.product_price,
					defaultProduct.img1,
					defaultProduct.product_description,
					defaultProduct.seller_id
				);
			}
		});
	</script>
	<script src="js/fetch_my_orders.js"></script>

	<script>
		// Fetch orders on page load
		document.addEventListener('DOMContentLoaded', fetchOrders);
	</script>

	<script src="js/main.js"></script>
	<script src="js/login_logout.js"></script>
	<script src="js/pdf_Wishlist.js"></script>
	<script src="js/whishlist.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="vendor/sweetalert/sweetalert.min.js"></script>

	<script>
		// In fetch_my_orders.js
		// Update these functions to use proper error handling
		function showReturnRequest(rentId) {
			if (confirm('Are you sure you want to initiate return for this rental?')) {
				fetch(`php/update-rental.php?action=return_initiated&rent_id=${rentId}`)
					.then(response => {
						if (!response.ok) throw new Error('Network response was not ok');
						return response.json();
					})
					.then(data => {
						if (data.success) {
							// alert('Return request initiated successfully');
							Swal.fire('Return request initiated successfully', 'success');
							location.reload();
						} else {
							throw new Error(data.message || 'Request failed');
						}
					})
					.catch(error => {
						Swal.fire('Error: ' + error.message, 'danger');
						// alert('Error: ' + error.message);
					});
			}
		}

		function showDepositAlert(rentId) {
			if (confirm('Report missing deposit for this rental?')) {
				fetch(`php/update-rental.php?action=deposit_alert&rent_id=${rentId}`)
					.then(response => {
						if (!response.ok) throw new Error('Network response was not ok');
						return response.json();
					})
					.then(data => {
						if (data.success) {
							// alert('Deposit ticket created. We will contact you shortly.');
							Swal.fire('Deposit ticket created. We will contact you shortly.', 'success');
						} else {
							throw new Error(data.message || 'Request failed');
						}
					})
					.catch(error => {
						// alert('Error: ' + error.message);
						Swal.fire('Error: ' + error.message, 'danger');
					});
			}
		}

		function raiseDepositTicket() {
			// API call to raise deposit ticket
			Swal.fire('Ticket Raised!', 'Our team will contact you within 24 hours.', 'success');
		}
	</script>


</body>

</html>