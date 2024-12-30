<?php
// Include the database connection file
include('php/cart-wishlist-notification.php');
include('php/conn.php');
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$api_key = 'rzp_test_8k9Y3Mmk6y9sy0';
$api_secret = 'cgbCQ1yvbRMK3QM9z2jPhf0G';

$api = new Api($api_key, $api_secret);

// Check if user is logged in (user_id should be set in the session)
// session_start();
// if (!isset($_SESSION['user_id'])) {
// 	// Redirect the user to the login page or show an error message
// 	header('Location: login.php');
// 	exit;
// }

// Fetch products from the cart for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT c.product_id, c.date_added, p.product_name, p.product_price, p.img1, p.seller_id, p.product_description
          FROM cart c
          INNER JOIN products p ON c.product_id = p.product_id
          WHERE c.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle the case where the cart is empty
$default_product = empty($cart_products) ? null : $cart_products[0];



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

	<!--===============================================================================================-->
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
								<a href="product.php">Books</a>
							</li>
							<li>
								<a href="shop_pdf.php">PDFs</a>
							</li>

							<li class="active-menu">
								<a href="shoping-cart.php">Cart</a>
							</li>

							<li>
								<a href="my-orders.php">My Orders</a>
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
					<a href="product.php">Books</a>
				</li>
				<li>
					<a href="shop_pdf.php">PDFs</a>
				</li>

				<li class="active-menu">
					<a href="shoping-cart.php">Cart</a>
				</li>

				<li>
					<a href="my-orders.php">My Orders</a>
				</li>
			</ul>
		</div>


		<!-- Modal Search -->
		<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
			<div class="container-search-header">
				<button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
					<img src="images/icons/icon-close2.png" alt="CLOSE">
				</button>

				<form class="wrap-search-header flex-w p-l-15">
					<button class="flex-c-m trans-04">
						<i class="zmdi zmdi-search"></i>
					</button>
					<input class="plh3" type="text" name="search" placeholder="Search...">
				</form>
			</div>
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
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Shoping Cart
			</span>
		</div>
	</div>


	<!-- Shoping Cart -->
	<div class="bg0 p-t-75 p-b-85">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
						<div class="wrap-table-shopping-cart">
							<table class="table-shopping-cart">
								<tr class="table_head">
									<th class="column-1">Product</th>
									<th class="column-2">Name</th>
									<th class="column-3">Price</th>
									<th class="column-4">Discount</th>
									<th class="column-4">Total</th>
									<th class="column-5">Actions</th>
								</tr>
								<?php if (empty($cart_products)): ?>
									<tr class="table_row">
										<td colspan="6" class="column-1">Cart is Empty</td>
									</tr>
								<?php else: ?>
									<?php foreach ($cart_products as $product): ?>
										<tr class="table_row" id="product-<?php echo $product['product_id']; ?>">
											<td class="column-1">
												<div class="how-itemcart1">
													<img src="<?php echo addslashes($product['img1']); ?>" alt="Product Image">
												</div>
											</td>
											<td class="column-2"><?php echo addslashes($product['product_name']); ?></td>
											<td class="column-3"><?php echo number_format($product['product_price'], 2); ?> ₹
											</td>
											<td class="column-4"><?php echo number_format(20, 2); ?> ₹</td>
											<td class="column-4"><?php echo number_format($product['product_price'] - 20, 2); ?>
												₹</td>
											<td class="column-5">
												<div class="btn-group">
													<button type="button" class="btn btn-outline-info" onclick="selectToBuy(
													<?php echo $product['product_id']; ?>, 
													'<?php echo addslashes($product['product_name']); ?>', 
													<?php echo $product['product_price']; ?>, 
													'<?php echo addslashes($product['img1']); ?>', 
													'<?php echo addslashes($product['product_description']); ?>',
													<?php echo $product['seller_id']; ?>
												)">
														<i class="fa fa-cart-shopping"></i>
													</button>
													<button type="button" class="btn btn-outline-danger"
														onclick="removeFromCart(<?php echo $product['product_id']; ?>);  ">
														<i class="fa-solid fa-trash"></i>
													</button>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</table>
						</div>

						<?php if (!empty($cart_products)): ?>
							<!-- <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
								<div class="flex-w flex-m m-r-20 m-tb-5">
									<input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text"
										name="coupon" placeholder="Coupon Code">
									<div
										class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
										Apply coupon</div>
								</div>
								<div
									class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
									Update Cart</div>
							</div> -->
						<?php endif; ?>
					</div>
				</div>

				<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
					<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
						<h4 class="mtext-109 cl2 p-b-30">Selected Product</h4>

						<?php if ($default_product): ?>
							<div class="flex-w flex-t p-b-13">
								<div class="size-208"><span class="stext-110 cl2">Name:</span></div>
								<div class="size-209"><span class="mtext-110 cl2"
										id="selected-product-name"><?php echo addslashes($default_product['product_name']); ?></span>
								</div>
							</div>
							<div class="flex-w flex-t p-b-13">
								<div class="size-208"><span class="stext-110 cl2">Image:</span></div>
								<div class="size-209"><img src="<?php echo addslashes($default_product['img1']); ?>"
										alt="Product Image" id="selected-product-img" style="width: 100px;"></div>
							</div>
							<div class="flex-w flex-t p-b-13">
								<div class="size-208"><span class="stext-110 cl2">Price:</span></div>
								<div class="size-209"><span class="mtext-110 cl2"
										id="selected-product-price"><?php echo number_format($default_product['product_price'], 2); ?>
										₹</span></div>
							</div>
							<div class="flex-w flex-t p-b-13">
								<div class="size-208"><span class="stext-110 cl2">Discount:</span></div>
								<div class="size-209"><span class="mtext-110 cl2">20.00 ₹</span></div>
							</div>
							<div class="flex-w flex-t p-t-15 p-b-30">
								<div class="size-208"><span class="stext-110 cl2">Total:</span></div>
								<div class="size-209"><span class="mtext-110 cl2"
										id="selected-product-total"><?php echo number_format($default_product['product_price'] - 20, 2); ?>
										₹</span></div>
							</div>
							<div class="flex-w flex-t p-t-15 p-b-30">
								<div class="size-208"><span class="stext-110 cl2">Description:</span></div>
								<div class="size-209">
									<p id="selected-product-description">
										<?php echo addslashes($default_product['product_description']); ?>
									</p>
								</div>
							</div>
							<div class="flex-w flex-t p-b-13" id="address-block">
								<div class="size-208 w-full-ssm"><span class="stext-110 cl2">Address :</span></div>
								<div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
									<input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="address"
										id="address" placeholder="Enter your address" required>
								</div>
							</div>
							<div class="flex-w flex-t p-b-13" id="message-block">
								<div class="size-208 w-full-ssm">
									<span class="stext-110 cl2">Pincode :</span>
								</div>
								<div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
									<input class="stext-111 cl8 plh3 size-111 p-lr-15" type="number" name="pincode"
										id="pincode" placeholder="Enter your pincode" required maxlength="6">
								</div>
							</div>
							<div class="flex-w flex-t p-b-13">
								<div class="size-208 w-full-ssm"><span class="stext-110 cl2">Payment Method:</span></div>
								<div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
									<select class="stext-111 cl8 plh3 size-111 p-lr-15" id="payment-method" required>
										<option value="online">Online Payment</option>
										<option value="cod">Cash on Delivery</option>
									</select>
								</div>
							</div>
							<button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer"
								onclick="processPurchase()">Place Order</button>
							<input type="hidden" id="selected-product-id"
								value="<?php echo $default_product['product_id']; ?>">
						<?php else: ?>
							<p>No product selected.</p>
						<?php endif; ?>
					</div>
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

	<script>

		// Function to increase count and update data-notify attribute
		function increaseCount(elementId) {
			console.log("increase func called");
			let element = document.querySelector(elementId);
			let currentCount = parseInt(element.getAttribute('data-notify')) || 0;
			let newCount = currentCount + 1;
			element.setAttribute('data-notify', newCount);
		}

		// Function to decrease count and update data-notify attribute (with minimum limit of 0)
		function decreaseCount(elementId) {
			console.log("decrease func called");
			let element = document.querySelector(elementId);
			let currentCount = parseInt(element.getAttribute('data-notify')) || 0;
			let newCount = Math.max(0, currentCount - 1);
			element.setAttribute('data-notify', newCount);
		}

	</script>

	<script>

		function processPurchase() {
			const productId = document.getElementById('selected-product-id').value;
			const totalPrice = parseFloat(document.getElementById('selected-product-total').innerText);
			const addressField = document.getElementById('address');
			const pincode = document.getElementById('pincode').value.trim();
			const paymentMethod = document.getElementById('payment-method').value;
			const paymentMode = document.querySelector('input[name="payment_mode"]:checked')?.value; // Ensure that the payment mode is selected

			// Validate address, pincode, and payment mode fields
			if (!addressField || !addressField.value.trim() || pincode.length !== 6) {
				swal("Error", "Please enter a valid address and a 6-digit pincode", "error");
				return;
			}

			const address = addressField.value.trim();

			// Check for payment method and process accordingly
			if (paymentMethod === 'online') {
				buyProduct(productId, totalPrice);
				orderProduct(productId, address, pincode, totalPrice, 'online')
			} else if (paymentMethod === 'cod') {
				orderProduct(productId, address, pincode, totalPrice, 'cod');
			}
		}

		function orderProduct(productId, address, pincode, totalPrice, paymentMode) {
			fetch('php/create_order.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: `product_id=${productId}&address=${encodeURIComponent(address)}&total_price=${totalPrice}&pincode=${encodeURIComponent(pincode)}&payment_mode=${encodeURIComponent(paymentMode)}`
			})
				.then(response => {
					if (!response.ok) {
						throw new Error('Network response was not ok');
					}
					return response.json();
				})
				.then(data => {
					if (data.success) {
						swal("Order Placed", data.message, "success");
					} else {
						swal("Failed", data.message, "error");
					}
				})
				.catch(error => {
					swal("Error", "An error occurred. Please try again.", "error");
				});
		}

		function cashOnDelivery(productId, price) {
			// Simple function to handle Cash on Delivery logic
			alert('Order placed with Cash on Delivery. Product ID: ' + productId + ', Price: ' + price + '₹');
			// Implement further backend logic to handle COD orders here
		}

		function buyProduct(productId, productPrice) {

			// Send product data to payment_index.php using fetch
			fetch('payment_index.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					type: 'product',
					productId: productId,
					price: productPrice
				})
			})
				.then(response => response.json())
				.then(data => {
					console.log('Payment response:', data);
					if (data.orderId) {
						startPayment(data.orderId, productPrice);
					} else {
						console.error('Failed to create order. Please try again.');
					}
				})
				.catch(error => {
					console.error('Error in fetch request:', error);
				});
		}

		function startPayment(orderId, price) {
			const api_key = 'rzp_test_8k9Y3Mmk6y9sy0';  // Make sure to use your Razorpay API key

			var options = {
				key: api_key,
				amount: price * 100, // Convert the price to paise (100 paise = 1 INR)
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


		// function sendBuyRequest(address, pincodeNo, paymentMode) {


		// 	console.log("order details:", address, pincodeNo, paymentMode);

		// 	fetch('create_order.php', {
		// 		method: 'POST',
		// 		headers: {
		// 			'Content-Type': 'application/x-www-form-urlencoded'
		// 		},
		// 		body: `product_id=${productId}&address=${encodeURIComponent(address)}&total_price=${totalPrice}&pincode=${encodeURIComponent(pincodeNo)}&payment_mode=${encodeURIComponent(paymentMode)}`
		// 	})
		// 		.then(response => {
		// 			if (!response.ok) {
		// 				throw new Error('Network response was not ok');
		// 			}
		// 			return response.json();
		// 		})
		// 		.then(data => {
		// 			if (data.success) {
		// 				swal("Order Placed", data.message, "success");
		// 			} else {
		// 				swal("Failed", data.message, "error");
		// 			}
		// 		})
		// 		.catch(error => {
		// 			swal("Error", "An error occurred. Please try again.", "error");
		// 		});
		// }

	</script>

	<script src="js/main.js"></script>
	<script src="js/request_product.js"></script>
	<script src="js/whishlist.js"></script>
	<script src="js/delete_from_cart.js"></script>
	<script src="js/login_logout.js"></script>
	<script src="js/shopping_cart_functions.js"></script>

	<script src="js/pdf_Wishlist.js"></script>
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="vendor/sweetalert/sweetalert.min.js"></script>
</body>

</html>