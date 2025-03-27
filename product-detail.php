<?php

include('php/conn.php'); // Include the conn.php file to establish a database connection

include('php/cart-wishlist-notification.php');
// Query to fetch product information from the database

if (isset($_GET['id'])) {
	// Fetch product details based on the product ID
	$productId = $_GET['id'];

	$query = "SELECT * FROM products WHERE product_id = :product_id"; // Change the condition as per your requirement
	$stmt = $pdo->prepare($query);
	$stmt->bindParam(':product_id', $productId); // Corrected variable name
	$stmt->execute();
	$product_details = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>Product Detail</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
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
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/MagnificPopup/magnific-popup.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<!--===============================================================================================-->
	<style>
		.modal-overlay {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(0, 0, 0, 0.7);
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 9999;
			opacity: 0;
			visibility: hidden;
			transition: all 0.3s ease;
		}

		.modal-overlay.active {
			opacity: 1;
			visibility: visible;
		}

		/* Other existing modal styles... */

		/* Loading spinner styles */
		.spinner {
			display: inline-block;
			width: 1rem;
			height: 1rem;
			border: 2px solid rgba(255, 255, 255, .3);
			border-radius: 50%;
			border-top-color: #fff;
			animation: spin 1s ease-in-out infinite;
			margin-right: 0.5rem;
		}

		@keyframes spin {
			to {
				transform: rotate(360deg);
			}
		}

		/* Alert styles */
		.custom-alert {
			position: fixed;
			top: 20px;
			left: 50%;
			transform: translateX(-50%);
			padding: 1rem 1.5rem;
			border-radius: 4px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
			z-index: 10000;
			display: flex;
			align-items: center;
			max-width: 90%;
			animation: slideIn 0.3s ease-out;
		}

		.alert-success {
			background-color: #d4edda;
			color: #155724;
			border: 1px solid #c3e6cb;
		}

		.alert-danger {
			background-color: #f8d7da;
			color: #721c24;
			border: 1px solid #f5c6cb;
		}

		.alert-info {
			background-color: #d1ecf1;
			color: #0c5460;
			border: 1px solid #bee5eb;
		}

		.alert-close {
			margin-left: 1rem;
			cursor: pointer;
			font-weight: bold;
		}

		@keyframes slideIn {
			from {
				top: -100px;
				opacity: 0;
			}

			to {
				top: 20px;
				opacity: 1;
			}
		}

		/* Modal Container */
		.modal-container {
			width: 90%;
			max-width: 500px;
			background-color: white;
			border-radius: 10px;
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
			overflow: hidden;
			transform: translateY(-20px);
			transition: transform 0.3s ease;
		}

		.modal-overlay.active .modal-container {
			transform: translateY(0);
		}

		/* Modal Header */
		.modal-header {
			background-color: #2c3e50;
			color: white;
			padding: 15px 20px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.modal-header h3 {
			margin: 0;
			font-size: 1.2rem;
		}

		.close-btn {
			background: none;
			border: none;
			color: white;
			font-size: 1.5rem;
			cursor: pointer;
			line-height: 1;
		}

		/* Modal Body */
		.modal-body {
			padding: 20px;
		}

		/* Plan Options */
		.plan-options {
			margin-bottom: 20px;
		}

		.plan-option {
			margin-bottom: 15px;
			display: flex;
			align-items: center;
		}

		.plan-option input[type="radio"] {
			margin-right: 10px;
			width: 18px;
			height: 18px;
			accent-color: #2c3e50;
		}

		.plan-option label {
			font-size: 0.95rem;
			cursor: pointer;
		}

		/* Terms Section */
		.terms-section {
			border-top: 1px solid #eee;
			padding-top: 15px;
		}

		.terms-section h4 {
			margin: 0 0 15px 0;
			font-size: 1rem;
			color: #2c3e50;
		}

		.terms-content {
			background-color: #f8f9fa;
			padding: 15px;
			border-radius: 5px;
			margin-bottom: 15px;
			max-height: 150px;
			overflow-y: auto;
		}

		.terms-content ol {
			padding-left: 20px;
			margin: 0;
		}

		.terms-content li {
			margin-bottom: 8px;
			font-size: 0.85rem;
			color: #555;
		}

		/* Terms Agreement */
		.terms-agreement {
			display: flex;
			align-items: center;
			margin-bottom: 15px;
		}

		.terms-agreement input[type="checkbox"] {
			margin-right: 10px;
			width: 18px;
			height: 18px;
			accent-color: #2c3e50;
		}

		.terms-agreement label {
			font-size: 0.9rem;
			cursor: pointer;
		}

		/* Confirm Button */
		.confirm-btn {
			width: 100%;
			padding: 12px;
			background-color: #2c3e50;
			color: white;
			border: none;
			border-radius: 5px;
			font-weight: bold;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		.confirm-btn:hover {
			background-color: #1a252f;
		}

		.confirm-btn:disabled {
			background-color: #95a5a6;
			cursor: not-allowed;
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
							<li class="active-menu">
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
							<i class="zmdi zmdi-favorite-outline" onclick="fetchPdfWishlist()"></i>
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
				<li class="active-menu">
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

			<a href="product.php" class="stext-109 cl8 hov-cl1 trans-04">
				Shop
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				<?php echo $product_details['category']; ?>
			</span>
		</div>
	</div>


	<!-- Product Detail -->

	<section class="sec-product-detail bg0 p-t-65 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
						<div class="wrap-slick3 flex-sb flex-w">
							<div class="wrap-slick3-dots"></div>
							<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
							<div class="slick3 gallery-lb">
								<div class="item-slick3" data-thumb="<?php echo $product_details['img1']; ?>">
									<div class="wrap-pic-w pos-relative">
										<img src="<?php echo $product_details['img1']; ?>" alt="Product Image">
										<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
											href="<?php echo $product_details['img1']; ?>">
											<i class="fa fa-expand"></i>
										</a>
									</div>
								</div>
								<div class="item-slick3" data-thumb="<?php echo $product_details['img2']; ?>">
									<div class="wrap-pic-w pos-relative">
										<img src="<?php echo $product_details['img2']; ?>" alt="Product Image">
										<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
											href="<?php echo $product_details['img2']; ?>">
											<i class="fa fa-expand"></i>
										</a>
									</div>
								</div>
								<div class="item-slick3" data-thumb="<?php echo $product_details['img3']; ?>">
									<div class="wrap-pic-w pos-relative">
										<img src="<?php echo $product_details['img3']; ?>" alt="Product Image">
										<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
											href="<?php echo $product_details['img3']; ?>">
											<i class="fa fa-expand"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-5 p-b-30">
					<div class="p-r-50 p-t-5 p-lr-0-lg">
						<h4 class="mtext-105 cl2 js-name-detail p-b-14"><?php echo $product_details['product_name']; ?>
						</h4>
						<span class="mtext-106 cl2">$<?php echo $product_details['product_price']; ?></span>
						<p class="stext-102 cl3 p-t-23"><?php echo $product_details['product_description']; ?></p>


						<div class="p-t-33	" style="width: 100%;">
							<div class="flex-w flex-r-m p-b-10">
								<div class="size-203 flex-c-m respon6" style="width: 30%;">Category</div>
								<div class="size-204 respon6-next" style="width: 70%;">
									<?php echo $product_details['category']; ?>
								</div>
							</div>
							<div class="flex-w flex-r-m p-b-10">
								<div class="size-203 flex-c-m respon6" style="width: 30%;">
									<?php
									if ($product_details['category'] != 'books') {
										echo "Brand";
									} else {
										echo "Author";
									}
									?>
								</div>
								<div class="size-204 respon6-next" style="width: 70%;">
									<?php echo $product_details['authorbrand']; ?>
								</div>
							</div>

							<div class="flex-w flex-r-m p-b-10">
								<div class="size-203 flex-c-m respon6" style="width: 30%;">Used for</div>
								<div class="size-204 respon6-next" style="width: 70%;">
									<?php echo $product_details['duration_of_use'] . " Months"; ?>
								</div>
							</div>

							<div class="flex-w flex-r-m p-b-10">
								<div class="size-203 flex-c-m respon6" style="width: 30%;">Condition</div>
								<div class="size-204 respon6-next" style="width: 70%;">
									<?php echo $product_details['product_condition']; ?>
								</div>
							</div>

							<div class="flex-w flex-r-m p-b-10">
								<div class="size-203 flex-c-m respon6" style="width: 30%;">Available Area</div>
								<div class="size-204 respon6-next" style="width: 70%;">
									<?php echo $product_details['available_area']; ?>
								</div>
							</div>
						</div>


						<!-- Add to Cart Button -->
						<div class="flex-w flex-m justify-content-between">
							<div class="flex-m bor9 p-r-10 m-r-11">
								<a href="#"
									class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100"
									data-tooltip="Add to Wishlist">
									<i class="zmdi zmdi-favorite"></i>
								</a>
							</div>

							<button
								class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail"
								data-product-id="<?php echo $product_details['product_id']; ?>">
								Add to cart
							</button>
							<button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
								onclick="showRentalTermsModal(<?php echo $product_details['product_id'] ?>)">
								Rent Product
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="bor10 m-t-50 p-t-43 p-b-40">
				<div class="tab01">
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews</a>
						</li>
					</ul>

					<div class="tab-content p-t-43">
						<div class="tab-pane fade show active" id="reviews">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<div class="p-b-30 m-lr-15-sm">
										<!-- Reviews Section -->
										<div class="reviews-container">
											<!-- PHP will insert reviews here -->
											<?php include 'php/fetch_reviews.php'; ?>
										</div>

										<!-- Add Review Section -->
										<div class="add-review-container">
											<form id="reviewForm" class="w-full">
												<h5 class="mtext-108 cl2 p-b-7">
													Add a review
												</h5>

												<p class="stext-102 cl6">
													Your email address will not be published. Required fields are marked
													*
												</p>

												<div class="flex-w flex-m p-t-50 p-b-23">
													<span class="stext-102 cl3 m-r-16">
														Your Rating
													</span>

													<span class="wrap-rating fs-18 cl11 pointer">
														<i class="item-rating pointer zmdi zmdi-star-outline"></i>
														<i class="item-rating pointer zmdi zmdi-star-outline"></i>
														<i class="item-rating pointer zmdi zmdi-star-outline"></i>
														<i class="item-rating pointer zmdi zmdi-star-outline"></i>
														<i class="item-rating pointer zmdi zmdi-star-outline"></i>
														<input class="dis-none" type="number" name="rating" required>
													</span>
												</div>

												<div class="row p-b-25">
													<div class="col-12 p-b-5">
														<label class="stext-102 cl3" for="review">Your review</label>
														<textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10"
															id="review" name="review_text" required></textarea>
													</div>
												</div>

												<!-- Hidden product ID input -->
												<input type="hidden" name="product_id"
													value="<?php echo $product_details['product_id']; ?>">

												<button type="button" id="submitReview"
													class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
													Submit
												</button>
											</form>
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

	</section>

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
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/slick/slick.min.js"></script>
	<script src="js/slick-custom.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/parallax100/parallax100.js"></script>
	<script>
		$('.parallax100').parallax100();
	</script>
	<!--===============================================================================================-->
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<script>
		$('.gallery-lb').each(function () { // the containers for all your galleries
			$(this).magnificPopup({
				delegate: 'a', // the selector for gallery item
				type: 'image',
				gallery: {
					enabled: true
				},
				mainClass: 'mfp-fade'
			});
		});
	</script>
	<!--===============================================================================================-->
	<script src="vendor/isotope/isotope.pkgd.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/sweetalert/sweetalert.min.js"></script>
	<script>
		$('.js-addwish-b2, .js-addwish-detail').on('click', function (e) {
			e.preventDefault();
		});

		$('.js-addwish-b2').each(function () {
			var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
			$(this).on('click', function () {
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-b2');
				$(this).off('click');
			});
		});

		$('.js-addwish-detail').each(function () {
			var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

			$(this).on('click', function () {
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-detail');
				$(this).off('click');
			});
		});

		/*---------------------------------------------*/

		// $('.js-addcart-detail').each(function () {
		// 	var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
		// 	$(this).on('click', function () {
		// 		swal(nameProduct, "is added to cart !", "success");
		// 	});
		// });

	</script>
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



	<script>
		document.getElementById('submitReview').addEventListener('click', function () {
			const form = document.getElementById('reviewForm');
			const formData = new FormData(form);
			const data = {
				product_id: formData.get('product_id'),
				rating: formData.get('rating'),
				review_text: formData.get('review_text')
			};

			fetch('php/add_review.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(data)
			})
				.then(response => response.json())
				.then(data => {
					if (data.message) {
						swal("Success!", data.message, "success");
						// Optionally, reload the reviews section
						// location.reload(); // Reload the entire page
					} else {
						swal("Error!", data.error, "error");
					}
				})
				.catch(error => {
					swal("Error!", "There was a problem with your review submission.", "error");
				});
		});

		document.querySelectorAll('.item-rating').forEach(function (star, index) {
			star.addEventListener('click', function () {
				document.querySelector('input[name="rating"]').value = index + 1;
				document.querySelectorAll('.item-rating').forEach(function (s, i) {
					s.classList.toggle('zmdi-star', i <= index);
					s.classList.toggle('zmdi-star-outline', i > index);
				});
			});
		});
	</script>
	<!--===============================================================================================-->

	<div class="modal-overlay" id="rentalPlanModal">
		<div class="modal-container">
			<div class="modal-header">
				<h3>Choose Rental Plan</h3>
				<button class="close-btn" aria-label="Close">&times;</button>
			</div>
			<div class="modal-body">
				<div class="plan-options">
					<div class="plan-option">
						<input type="radio" name="rentalPlan" id="dailyPlan" value="daily" checked>
						<label for="dailyPlan">
							<strong>Daily Plan</strong> - ₹20/day (Min 7 days)
						</label>
					</div>
					<div class="plan-option">
						<input type="radio" name="rentalPlan" id="weeklyPlan" value="weekly">
						<label for="weeklyPlan">
							<strong>Weekly Plan</strong> - ₹130/week
						</label>
					</div>
					<div class="plan-option">
						<input type="radio" name="rentalPlan" id="monthlyPlan" value="monthly">
						<label for="monthlyPlan">
							<strong>Monthly Plan</strong> - ₹200/month
						</label>
					</div>
				</div>

				<div class="terms-section">
					<h4>Rental Terms & Conditions</h4>
					<div class="terms-content">
						<ol>
							<li>₹500 refundable deposit required (paid during delivery)</li>
							<li>Rental period begins upon delivery confirmation</li>
							<li>Minimum 7 days rental for daily plan</li>
							<li>₹50/day late return penalty</li>
							<li>Damages may result in deposit deduction</li>
						</ol>
					</div>
					<div class="terms-agreement">
						<input type="checkbox" id="agreeTerms">
						<label for="agreeTerms">
							I agree to the terms and conditions
						</label>
					</div>
					<button class="confirm-btn" id="confirmRentBtn" disabled>
						Confirm Rental Request
					</button>
				</div>
			</div>
		</div>
	</div>


	<script>
		// Global variables
		let currentProductId = null;

		// Show rental terms modal
		function showRentalTermsModal(productId) {
			currentProductId = productId;
			document.getElementById('rentalPlanModal').classList.add('active');
			document.body.style.overflow = 'hidden';

			// Reset form state
			document.getElementById('dailyPlan').checked = true;
			document.getElementById('agreeTerms').checked = false;
			document.getElementById('confirmRentBtn').disabled = true;
		}

		// Hide modal
		function hideModal() {
			document.getElementById('rentalPlanModal').classList.remove('active');
			document.body.style.overflow = '';
		}

		// Show alert message
		function showAlert(type, message, duration = 5000) {
			const alertDiv = document.createElement('div');
			alertDiv.className = `custom-alert alert-${type}`;
			alertDiv.innerHTML = `
	  ${message}
	  <span class="alert-close">&times;</span>
	`;

			document.body.appendChild(alertDiv);

			// Close button functionality
			alertDiv.querySelector('.alert-close').addEventListener('click', () => {
				alertDiv.remove();
			});

			// Auto-remove after duration
			if (duration) {
				setTimeout(() => {
					alertDiv.remove();
				}, duration);
			}

			return alertDiv;
		}

		// Handle rental submission (kept exactly as you requested)
		function proceedToRent() {
			const planType = document.querySelector('input[name="rentalPlan"]:checked').value;
			const btn = document.getElementById('confirmRentBtn');

			console.log("Submitting rental for product:", currentProductId, "with plan:", planType);

			// Show loading state
			btn.innerHTML = '<span class="spinner"></span> Processing...';
			btn.disabled = true;

			fetch('./php/handle-rental.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					product_id: currentProductId,
					plan_type: planType
				})
			})
				.then(async response => {
					console.log("Raw response:", response);
					const text = await response.text();
					console.log("Response text:", text);

					try {
						const data = JSON.parse(text);
						console.log("Parsed JSON:", data);

						if (!data) {
							throw new Error("Empty response from server");
						}

						if (!response.ok) {
							throw new Error(data.message || "Request failed");
						}

						return data;
					} catch (e) {
						console.error("Failed to parse JSON:", e);
						throw new Error(text || "Invalid server response");
					}
				})
				.then(data => {
					console.log("Success data:", data);

					// Show success alert
					const successAlert = showAlert('success', data.message || 'Rental request successful');

					// Add redirect message after 1 second
					setTimeout(() => {
						successAlert.innerHTML = `
			${data.message || 'Rental request successful'}
			<br><small>Redirecting to rental page...</small>
			<span class="alert-close">&times;</span>
		  `;
					}, 1000);

					// Redirect after 2 seconds
					setTimeout(() => {
						window.location.href = './rent-products.php';
					}, 2000);
				})
				.catch(error => {
					console.error("Error:", error);
					const cleanError = error.message.replace(/<[^>]*>?/gm, '');
					showAlert('danger', cleanError || 'An unknown error occurred');
				})
				.finally(() => {
					btn.innerHTML = 'Confirm Rental Request';
					btn.disabled = false;
				});
		}

		// Initialize modal when DOM is loaded
		document.addEventListener('DOMContentLoaded', function () {
			const modal = document.getElementById('rentalPlanModal');
			const closeBtn = document.querySelector('.close-btn');
			const agreeTerms = document.getElementById('agreeTerms');
			const confirmBtn = document.getElementById('confirmRentBtn');

			// Close modal when clicking close button
			closeBtn.addEventListener('click', hideModal);

			// Close modal when clicking outside the modal content
			modal.addEventListener('click', function (e) {
				if (e.target === modal) {
					hideModal();
				}
			});

			// Enable/disable confirm button based on terms agreement
			agreeTerms.addEventListener('change', function () {
				confirmBtn.disabled = !this.checked;
			});

			// Handle confirm button click
			confirmBtn.addEventListener('click', proceedToRent);

			// Close modal when pressing Escape key
			document.addEventListener('keydown', function (e) {
				if (e.key === 'Escape' && modal.classList.contains('active')) {
					hideModal();
				}
			});

			// Calculate and display rental estimate when plan changes
			const planRadios = document.querySelectorAll('input[name="rentalPlan"]');
			planRadios.forEach(radio => {
				radio.addEventListener('change', function () {
					updateRentalEstimate(this.value);
				});
			});

			// Function to calculate and display rental estimate
			function updateRentalEstimate(plan) {
				let estimate = '';
				switch (plan) {
					case 'daily':
						estimate = 'Estimated total for 7 days: ₹140 (₹20 × 7 days) + ₹500 deposit';
						break;
					case 'weekly':
						estimate = 'Estimated total: ₹130 (weekly rate) + ₹500 deposit';
						break;
					case 'monthly':
						estimate = 'Estimated total: ₹200 (monthly rate) + ₹500 deposit';
						break;
				}
				console.log(estimate);
			}
		});
	</script>
	<script src="js/main.js"></script>
	<script src="js/add_to_cart.js"></script>
	<script src="js/whishlist.js"></script>
	<script src="js/login_logout.js"></script>
	<script src="js/pdf_Wishlist.js"></script>
</body>

</html>