<?php

include('php/conn.php'); // Include the conn.php file to establish a database connection

require_once  'php/check_subcription.php';
include('php/cart-wishlist-notification.php');
// Query to fetch product information from the database
// session_start();
// Query to fetch user data
$sql = "SELECT * FROM user WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$user_id = $_SESSION['user_id']; // Replace with the actual user ID
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

// Fetch user data as an associative array
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($user['subscription_status'] == 'active') {
	$subscription_status = true;
} else {
	$subscription_status = false;
}


if (isset($_GET['id'])) {
	// Fetch product details based on the product ID
	$pdf_id = $_GET['id'];

	$query = "SELECT * FROM pdf_documents WHERE pdf_id = :pdf_id"; // Change the condition as per your requirement
	$stmt = $pdo->prepare($query);
	$stmt->bindParam(':pdf_id', $pdf_id); // Corrected variable name
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
						<a href="#" class="flex-c-m trans-04 p-lr-25">
							Help & FAQs
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
							<li class="active-menu">
								<a href="product.php">Shop</a>
							</li>

							<li>
								<a href="shoping-cart.php">Cart</a>
							</li>

							<li>
								<a href="dashboard.php">Dashboard</a>
							</li>

						</ul>
					</div>

					<!-- Icon header -->
					<div class="wrap-icon-header flex-w flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-search"></i>
						</div>

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti active-menu"
							data-notify="<?php echo $cart_count; ?>">
							<a href="shoping-cart.php"><i class="zmdi zmdi-shopping-cart"></i></a>
						</div>

						<a href="#" onclick="fetchWishlist()"
							class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
							data-notify="<?php echo $wishlist_count; ?>">
							<i class="zmdi zmdi-favorite-outline"></i>
						</a>
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
				<!-- <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-search"></i>
				</div> -->

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti active-menu"
					data-notify="<?php echo $cart_count; ?>">
					<a href="shoping-cart.php"><i class="zmdi zmdi-shopping-cart"></i></a>
				</div>

				<a href="#" onclick="fetchWishlist()"
					class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
					data-notify="<?php echo $wishlist_count; ?>">
					<i class="zmdi zmdi-favorite-outline"></i>
				</a>

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
					<a href="product.php">Shop</a>
				</li>

				<li>
					<a href="shoping-cart.php">Cart</a>
				</li>

				<li>
					<a href="dashboard.php">Dashboard</a>
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

	<!-- Cart -->
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

						<a href="shoping-cart.php"
							class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Check Out
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

			<a href="shop_pdf.php" class="stext-109 cl8 hov-cl1 trans-04">
				PDF
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
						<h4 class="mtext-105 cl2 js-name-detail p-b-14">
							<?php echo $product_details['pdf_name']; ?>
						</h4>
						<span class="mtext-106 cl2">$<?php echo $product_details['price']; ?></span>
						<p class="stext-102 cl3 p-t-23">
							<?php echo $product_details['description']; ?>
						</p>

						<!-- PDF Access Buttons -->
						<div class="flex-w flex-m p-l-100 p-t-40 respon7">
							<?php

							if ($subscription_status) {
								// User has active subscription
								?>
								<button
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-read-pdf"
									onclick="openSecurePdfReader('<?= htmlspecialchars($product_details['pdf_path']) ?>')">
									Read PDF
								</button>
								<?php
							} else {
								// User doesn't have active subscription
								?>
								<button
									class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-buy-subscription"
									onclick="redirectToSubscription()">
									Buy Subscription to Read
								</button>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</section>


	<div id="securePdfReaderModal" class="modal" style="display:none;">
		<div class="modal-content">
			<span class="close-btn" onclick="closeSecurePdfReader()">&times;</span>
			<iframe id="securePdfFrame" src="" style="width:100%; height:80vh; border:none;"
				sandbox="allow-same-origin allow-scripts"></iframe>
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
		function openSecurePdfReader(pdfPath) {
			const viewerUrl = `pdf-viewer.php?path=${encodeURIComponent(pdfPath)}`;
			const windowFeatures = 'width=800,height=600,resizable=no,scrollbars=yes,status=no,location=no,toolbar=no,menubar=no';
			window.open(viewerUrl, '_blank', windowFeatures);
		}

		function redirectToSubscription(){
			window.open("http://localhost/StudentMart/profile.php")
		}
	</script>
	<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script src="js/add_to_cart.js"></script>
	<script src="js/whishlist.js"></script>
	<script src="js/login_logout.js"></script>




</body>

</html>