<?php

session_start();

include('php/cart-wishlist-notification.php');
// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
	// If not logged in, redirect to the login page
	header("Location: login.php");
	exit;
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
	<title>Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
		integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
		integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
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

	</style>

</head>

<body class="animsition">

	<!-- <div id="popup" class="popup">
	<div class="popup-content">
		<div class="popup-header">
			<h2 class="popup-title">New Message</h2>
			<button class="popup-close" onclick="closePopup()">&times;</button>
		</div>
		<div class="popup-body">
			<p id="popup-message"></p>
		</div>
		<div class="popup-footer">
			<button class="popup-button" onclick="closePopup()">Dismiss</button>
		</div>
	</div>
</div> -->







	<div id="confirmLogoutModal" class="modal">
		<div class="modal-content">
			<button class="close" onclick="closeConfirmLogout()">&times;</button>
			<p>Are you sure you want to logout?</p>
			<button class="confirm" onclick="logout()">Yes</button>
			<button class="cancel" onclick="closeConfirmLogout()">No</button>
		</div>
	</div>

	<!-- Header -->
	<header>
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

			<div class="wrap-menu-desktop">
				<nav class="limiter-menu-desktop container">

					<!-- Logo desktop -->
					<a href="#" class="logo">
						<h2 style="color: black;">StudentMart</h2>

						<!-- <img src="images/icons/logo-01.png" alt="IMG-LOGO"> -->
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li class="active-menu">
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

							<li>
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
				<li class="active-menu">
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

	<!-- Slider -->
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
				<div class="item-slick1" style="background-image: url(images/slide-08.jpg);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
								<span class="ltext-101 cl2 respon2">
									Books Galore
								</span>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
								<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
									Good Quality
								</h2>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
								<a href="product.php"
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
									Shop Now
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="item-slick1" style="background-image: url(images/slide-11.png);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="rollIn" data-delay="0">
								<span class="ltext-101 cl2 respon2">
									Explore Sci-Calculators
								</span>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="lightSpeedIn"
								data-delay="800">
								<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
									Electronics
								</h2>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="slideInUp" data-delay="1600">
								<a href="product.php"
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
									Shop Now
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="item-slick1" style="background-image: url(images/slide-09.png);">
					<div class="container h-full">
						<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
							<div class="layer-slick1 animated visible-false" data-appear="rotateInDownLeft"
								data-delay="0">
								<span class="ltext-101 cl2 respon2">
									Discover New Arrivals
								</span>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="rotateInUpRight"
								data-delay="800">
								<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
									New arrivals
								</h2>
							</div>

							<div class="layer-slick1 animated visible-false" data-appear="rotateIn" data-delay="1600">
								<a href="product.php"
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
									Shop Now
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-- Banner -->
	<div class="sec-banner bg0 p-t-80 p-b-50">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="images/books.png" alt="IMG-BANNER">

						<a href="product.php"
							class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Books
								</span>

								<!-- <span class="block1-info stext-102 trans-04">
									Spring 2018
								</span> -->
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Shop Now
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="images/electronics.jpg" alt="IMG-BANNER">

						<a href="product.php"
							class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Electronics
								</span>

								<!-- <span class="block1-info stext-102 trans-04">
									Spring 2018
								</span> -->
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Shop Now
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="images/others.png" alt="IMG-BANNER">

						<a href="product.php"
							class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									other
								</span>

								<!-- <span class="block1-info stext-102 trans-04">
									New Trend
								</span> -->
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Shop Now
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Product -->
	<div class="bg0 m-t-23 p-b-140">
		<div class="container">
			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="filter-btn stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1"
						data-filter="*">
						All Products
					</button>
					<!-- <button class="filter-btn stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter="books">
						Books
					</button>
					<button class="filter-btn stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter="electronics">
						Electronics
					</button> -->
				</div>
			</div>

			<!-- Products Section -->
			<div class="row row-cols-1 row-cols-md-4 g-4" id="productContainer">
				<!-- Products will be dynamically added here via JavaScript -->
			</div>

			<!-- Load More Button -->
			<div class="flex-c-m flex-w w-full p-t-45" id="load-more-container">
				<a href="product.php">
					<button id="load-more-btn"
						class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
						Shop More
					</button>
				</a>
			</div>

			<!-- Back to Top Button -->
			<div class="btn-back-to-top" id="myBtn">
				<span class="symbol-btn-back-to-top">
					<i class="zmdi zmdi-chevron-up"></i>
				</span>
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
					<!-- <li><a href="dashboard.php">Sell</a></li> -->
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

	<!-- Modal1 -->
	<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
		<div class="overlay-modal1 js-hide-modal1"></div>

		<div class="container">
			<div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
				<button class="how-pos3 hov3 trans-04 js-hide-modal1">
					<img src="images/icons/icon-close.png" alt="CLOSE">
				</button>

				<div class="row">
					<div class="col-md-6 col-lg-7 p-b-30">
						<div class="p-l-25 p-r-30 p-lr-0-lg">
							<div class="wrap-slick3 flex-sb flex-w">
								<div class="wrap-slick3-dots"></div>
								<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

								<div class="slick3 gallery-lb">
									<div class="item-slick3" data-thumb="images/product-detail-01.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-01.jpg" alt="IMG-PRODUCT">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
												href="images/product-detail-01.jpg">
												<i class="fa fa-expand"></i>
											</a>
										</div>
									</div>

									<div class="item-slick3" data-thumb="images/product-detail-02.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-02.jpg" alt="IMG-PRODUCT">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
												href="images/product-detail-02.jpg">
												<i class="fa fa-expand"></i>
											</a>
										</div>
									</div>

									<div class="item-slick3" data-thumb="images/product-detail-03.jpg">
										<div class="wrap-pic-w pos-relative">
											<img src="images/product-detail-03.jpg" alt="IMG-PRODUCT">

											<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
												href="images/product-detail-03.jpg">
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
							<h4 class="mtext-105 cl2 js-name-detail p-b-14">
								Lightweight Jacket
							</h4>

							<span class="mtext-106 cl2">
								$58.79
							</span>

							<p class="stext-102 cl3 p-t-23">
								Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat
								ornare feugiat.
							</p>

							<!--  -->
							<div class="p-t-33">
								<div class="flex-w flex-r-m p-b-10">
									<div class="size-203 flex-c-m respon6">
										Size
									</div>

									<div class="size-204 respon6-next">
										<div class="rs1-select2 bor8 bg0">
											<select class="js-select2" name="time">
												<option>Choose an option</option>
												<option>Size S</option>
												<option>Size M</option>
												<option>Size L</option>
												<option>Size XL</option>
											</select>
											<div class="dropDownSelect2"></div>
										</div>
									</div>
								</div>

								<div class="flex-w flex-r-m p-b-10">
									<div class="size-203 flex-c-m respon6">
										Color
									</div>

									<div class="size-204 respon6-next">
										<div class="rs1-select2 bor8 bg0">
											<select class="js-select2" name="time">
												<option>Choose an option</option>
												<option>Red</option>
												<option>Blue</option>
												<option>White</option>
												<option>Grey</option>
											</select>
											<div class="dropDownSelect2"></div>
										</div>
									</div>
								</div>

								<div class="flex-w flex-r-m p-b-10">
									<div class="size-204 flex-w flex-m respon6-next">
										<div class="wrap-num-product flex-w m-r-20 m-tb-10">
											<div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
												<i class="fs-16 zmdi zmdi-minus"></i>
											</div>

											<input class="mtext-104 cl3 txt-center num-product" type="number"
												name="num-product" value="1">

											<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
												<i class="fs-16 zmdi zmdi-plus"></i>
											</div>
										</div>

										<button
											class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
											Add to cart
										</button>
									</div>
								</div>
							</div>

							<!--  -->
							<div class="flex-w flex-m p-l-100 p-t-40 respon7">
								<div class="flex-m bor9 p-r-10 m-r-11">
									<a href="#"
										class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100"
										data-tooltip="Add to Wishlist">
										<i class="zmdi zmdi-favorite"></i>
									</a>
								</div>

								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
									data-tooltip="Facebook">
									<i class="fa fa-facebook"></i>
								</a>

								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
									data-tooltip="Twitter">
									<i class="fa fa-twitter"></i>
								</a>

								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
									data-tooltip="Google Plus">
									<i class="fa fa-google-plus"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
		$('.js-addwish-b2').on('click', function (e) {
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

		$('.js-addcart-detail').each(function () {
			var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
			$(this).on('click', function () {
				swal(nameProduct, "is added to cart !", "success");
			});
		});

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
	<!--===============================================================================================-->
	<!-- switching without reload -->
	<!-- <script>
		// Function to load section content using AJAX
		function loadSection(sectionId,filename) {
			$.ajax({
				url: filename,
				type: 'GET',
				data: { sectionId: sectionId },
				success: function(response) {
					$('#' + sectionId).html(response).show(); // Update section content and show
				},
				error: function(xhr, status, error) {
					console.error('Error loading section:', error);
				}
			});
		}

		// Event listeners to trigger section loading
		$('#home-button').click(function() {
			loadSection('index.php');
		});

		$('#shop-button').click(function() {
			loadSection('section2');
		});

		$('#cart-button').click(function() {
			loadSection('section3');
		});
		$('#addproduct-button').click(function() {
			loadSection('section3');
		});
		$('#myproduct-button').click(function() {
			loadSection('section3');
		});
	</script> -->

	<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script src="js/whishlist.js"></script>

	<script>
		$(document).ready(function () {
			// Function to fetch products via AJAX and display them
			function fetchAndDisplayProducts(category = '*') {
				$.ajax({
					url: 'php/index_fetch_products.php',
					type: 'GET',
					data: { category: category },
					success: function (data) {
						$('#productContainer').html(data);
						filterProducts(category); // Call filter after loading products
					},
					error: function (xhr, status, error) {
						console.error('Error fetching products:', error);
					}
				});
			}

			// Function to filter products by category
			function filterProducts(category) {
				$('.isotope-item').each(function () {
					if ($(this).hasClass(category) || category === '*') {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			}

			// Event listener for filter buttons
			$('.filter-btn').on('click', function () {
				const category = $(this).data('filter');
				filterProducts(category);
			});

			// Initial fetch and display of products
			fetchAndDisplayProducts();
		});

	</script>

	<script>

		window.onload = function () {
			// console.log("Window loaded");
			const loginSuccess = <?php echo isset($_GET['login']) && $_GET['login'] == 'success' ? 'true' : 'false'; ?>;
			const registerSuccess = <?php echo isset($_GET['register']) && $_GET['register'] == 'success' ? 'true' : 'false'; ?>;

			// console.log("Login success:", loginSuccess);
			// console.log("Register success:", registerSuccess);

			if (loginSuccess) {
				// console.log("Showing login success popup");
				showPopup("Login successful!");
				history.replaceState(null, '', window.location.pathname);
			} else if (registerSuccess) {
				// console.log("Showing register success popup");
				showPopup("Registration successful!");
				history.replaceState(null, '', window.location.pathname);
			} else {
				// console.log("No success condition met");
			}
		};


	</script>

	<script src="js/login_logout.js"></script>
	<script src="js/pdf_Wishlist.js"></script>
</body>

</html>