<?php
include('php/cart-wishlist-notification.php');
include './php/conn.php'; // Include the conn.php file to establish a database connection
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Product</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

                        <!-- <img src="images/icons/edit-logo._133x26.png" alt="IMG-LOGO"> -->
                    </a>

                    <!-- Menu desktop -->
                    <div class="menu-desktop">
                        <ul class="main-menu">
                            <li>
                                <a href="index.php">Home</a>
                            </li>
                            <li class="active-menu">
                                <a href="product.php">Books</a>
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
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11" id="cartIcon">
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

    <!-- whishlist  -->
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


    <!-- Product -->


    <div class="bg0 m-t-23 p-b-140">
        <div class="container">
            <div class="flex-w flex-sb-m p-b-52">
                <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                    <button class="filter-btn stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1"
                        data-filter="*">
                        All Products
                    </button>
                    <button class="filter-btn stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter="books">
                        Books
                    </button>
                    <button class="filter-btn stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter="electronics">
                        Electronics
                    </button>
                    <button class="filter-btn stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter="drawings">
                        Drawings
                    </button>

                </div>
                <div class="flex-w flex-c-m m-tb-10">
                    <div
                        class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                        <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                        <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Filter
                    </div>
                    <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
                        <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                        <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Search
                    </div>
                </div>
                <!-- Search product -->
                <div class="dis-none panel-search w-full p-t-10 p-b-15">
                    <div class="bor8 dis-flex p-l-15">
                        <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                            <i class="zmdi zmdi-search"></i>
                        </button>
                        <input id="searchInput" class="mtext-107 cl2 size-114 plh2 p-r-15" type="text"
                            name="search-product" placeholder="Search products...">
                    </div>
                </div>



                <div class="dis-none panel-filter w-full p-t-10">
                    <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
                        <div class="filter-col1 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Sort By
                            </div>
                            <ul>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04 filter-link-active"
                                        data-sort="default">
                                        Default
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="sort-link stext-106 trans-04" data-sort="low-high">
                                        Price: Low to High
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="sort-link stext-106 trans-04" data-sort="high-low">
                                        Price: High to Low
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="filter-col2 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Price
                            </div>
                            <ul>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04 filter-link-active"
                                        data-price="*">
                                        All
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04" data-price="0-200">
                                        ₹0.00 - ₹200.00
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04" data-price="200-500">
                                        ₹200.00 - ₹500.00
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04" data-price="500-999999">
                                        ₹500.00+
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="filter-col3 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Place
                            </div>
                            <ul>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04 filter-link-active"
                                        data-place="*">
                                        All
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04" data-place="pune">
                                        Pune
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04" data-place="Delhi">
                                        Delhi
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04" data-place="Awasari">
                                        Awasari
                                    </a>
                                </li>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04" data-place="Sinhgad">
                                        Sinhgad
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="row row-cols-1 row-cols-md-4 g-4" id="productContainer">
                <!-- Products will be dynamically added here via JavaScript -->
            </div>

            <!-- Load More Button -->
            <div class="flex-c-m flex-w w-full p-t-45" id="load-more-container">
                <button id="load-more-btn" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                    Load More
                </button>
            </div>

            <!-- Back to Top Button -->
            <div class="btn-back-to-top" id="myBtn">
                <span class="symbol-btn-back-to-top">
                    <i class="zmdi zmdi-chevron-up"></i>
                </span>
            </div>
        </div>
    </div>


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

        $('.js-addcart-detail').each(function () {
            var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
            $(this).on('click', function () {
                swal(nameProduct, "is added to cart !", "success");
            });
        });

    </script>

    <!-- wishlist code -->
    <script>
        // $(document).on('click', '.js-addwish-b2', function (e) {
        //     e.preventDefault();
        //     var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
        //     swal(nameProduct, "is added to wishlist !", "success");
        //     $(this).addClass('js-addedwish-b2');
        //     $(this).off('click');
        // });

        $(document).on('click', '.js-addwish-detail', function (e) {
            e.preventDefault();
            var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();
            swal(nameProduct, "is added to wishlist !", "success");
            $(this).addClass('js-addedwish-detail');
            $(this).off('click');
        });

        $(document).on('click', '.js-addcart-detail', function (e) {
            e.preventDefault();
            var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
            swal(nameProduct, "is added to cart !", "success");
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
    <script src="js/main.js"></script>
    <script>
        // Event handler for showing modal

    </script>

    <!-- load more -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- filter products -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>







    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/whishlist.js"></script>

    <script src="js/pdf_Wishlist.js"></script>
    <script src="js/display_product.js"></script>
    <script src="js/login_logout.js"></script>





</body>


</html>