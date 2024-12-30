-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 29, 2024 at 09:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studentmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `date_added`) VALUES
(141, 2, 8, '2024-07-18'),
(144, 2, 6, '2024-07-20'),
(145, 2, 3, '2024-07-20'),
(146, 2, 7, '2024-08-11'),
(147, 1, 10, '2024-11-15');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `notification_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `notification_type` varchar(100) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `seller_id`, `buyer_id`, `product_id`, `notification_date`, `notification_type`, `is_read`) VALUES
(45, 1, 1, 10, '2024-11-18 08:00:57', 'New Order', 1),
(46, 1, 1, 10, '2024-11-18 08:01:41', 'New Order', 0),
(47, 1, 1, 10, '2024-11-18 10:16:02', 'New Order', 0),
(48, 1, 1, 10, '2024-11-18 10:16:35', 'New Order', 0),
(49, 1, 1, 10, '2024-11-18 10:17:08', 'New Order', 0),
(50, 1, 1, 10, '2024-11-18 10:20:56', 'New Order', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `buyer_id` bigint(20) NOT NULL,
  `seller_id` bigint(20) NOT NULL,
  `status` text NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `complete_date` date DEFAULT NULL,
  `address` text NOT NULL,
  `pincode` bigint(20) NOT NULL DEFAULT 0,
  `total_price` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `product_id`, `buyer_id`, `seller_id`, `status`, `payment_mode`, `order_date`, `complete_date`, `address`, `pincode`, `total_price`) VALUES
(143, 10, 1, 1, 'completed', 'cod', '2024-11-18', '2024-11-20', 'dsdsd', 121212, 180),
(145, 10, 1, 1, 'completed', 'online', '2024-11-18', '2024-11-25', 'cxc', 444444, 180),
(146, 10, 1, 1, 'pending', 'online', '2024-11-18', '2024-11-25', 'cxc', 121212, 180);

-- --------------------------------------------------------

--
-- Table structure for table `pdf_documents`
--

CREATE TABLE `pdf_documents` (
  `pdf_id` int(11) NOT NULL,
  `pdf_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `price` bigint(20) NOT NULL DEFAULT 0,
  `rented_by` bigint(20) NOT NULL DEFAULT 0,
  `img1` varchar(255) NOT NULL,
  `img2` varchar(255) NOT NULL,
  `img3` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `upload_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pdf_documents`
--

INSERT INTO `pdf_documents` (`pdf_id`, `pdf_name`, `category`, `pdf_path`, `price`, `rented_by`, `img1`, `img2`, `img3`, `description`, `upload_date`) VALUES
(1, 'Notes', 'Technology', 'pdfs/documents/6710c3d7d5f56.pdf', 0, 12, 'pdfs/images/6710c3d7d65e8.jpg', 'pdfs/images/6710c3d7d69af.jpg', 'pdfs/images/6710c3d7d6b18.jpg', 'dsdsdsdsd', '2024-10-17'),
(9, 'evyfyvwyjhvw', 'Education', 'pdfs/documents/6710efda1f9be.pdf', 212112, 0, 'pdfs/images/6710efda1fc6d.jpeg', 'pdfs/images/6710efda1fe28.jpeg', 'pdfs/images/6710efda1ff9e.jpeg', '212121212', '2024-10-17'),
(10, 'DC motor', 'Entertainment', 'pdfs/documents/6736f1095294b.pdf', 121, 0, 'pdfs/images/6736f109536a2.jpg', 'pdfs/images/6736f10953f3a.jpeg', 'pdfs/images/6736f1095473d.webp', '2sddsdsd', '2024-11-15');

-- --------------------------------------------------------

--
-- Table structure for table `pdf_reviews`
--

CREATE TABLE `pdf_reviews` (
  `id` int(11) NOT NULL,
  `pdf_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pdf_reviews`
--

INSERT INTO `pdf_reviews` (`id`, `pdf_id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 10, 1, 3, 'good', '2024-11-28 17:39:24'),
(2, 1, 1, 4, 'best', '2024-11-28 17:39:40');

-- --------------------------------------------------------

--
-- Table structure for table `pdf_wishlist`
--

CREATE TABLE `pdf_wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pdf_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pdf_wishlist`
--

INSERT INTO `pdf_wishlist` (`wishlist_id`, `user_id`, `pdf_id`, `date_added`) VALUES
(7, 1, 9, '2024-11-19 11:47:03'),
(8, 1, 1, '2024-11-19 11:47:06');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` bigint(11) NOT NULL,
  `seller_id` bigint(11) NOT NULL,
  `category` text NOT NULL,
  `product_name` text DEFAULT NULL,
  `authorbrand` text DEFAULT '\'\\\'Brand\\\'\'',
  `product_description` text DEFAULT NULL,
  `product_price` double DEFAULT NULL,
  `duration_of_use` int(11) DEFAULT NULL,
  `product_condition` text DEFAULT NULL,
  `product_status` varchar(20) DEFAULT NULL,
  `available_area` text DEFAULT NULL,
  `img1` varchar(255) NOT NULL,
  `img2` varchar(255) NOT NULL,
  `img3` varchar(255) NOT NULL,
  `added_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `seller_id`, `category`, `product_name`, `authorbrand`, `product_description`, `product_price`, `duration_of_use`, `product_condition`, `product_status`, `available_area`, `img1`, `img2`, `img3`, `added_date`) VALUES
(1, 1, 'books', 'Engineering Mathematics', 'Nirali , John Smith', 'This textbook is well-maintained with some highlighting on key sections. The cover shows minor signs of wear, but all pages are intact and legible. Perfect for college students and educators.\r\n', 100, 6, 'good', 'sold', 'Sinhgad', 'images/products/1_6698cb5f65ff7.jpg', 'images/products/1_6698cb5f66792.jpg', 'images/products/1_6698cb5f66b78.jpg', '2024-07-18'),
(2, 1, 'books', 'Fundamentals of Physics', 'Nirali , David Halliday', 'This comprehensive physics textbook is essential for first-year engineering students. The book has some highlighting and notes in the margins, but all pages are intact and legible. The cover shows minor signs of wear.', 140, 6, 'good', 'available', 'Sinhgad', 'images/products/1_6698cc1fae64a.jpg', 'images/products/1_6698cc1fae8ee.jpg', 'images/products/1_6698cc1faec06.jpg', '2024-07-18'),
(3, 1, 'books', 'Advanced Engineering Mathematics', 'Technoledge , Erwin Kreyszig', 'This book is a staple for engineering mathematics courses. It contains some highlighting and annotations. The cover shows minor wear, but the pages are intact and readable.', 122, 11, 'fair', 'available', 'Sinhgad', 'images/products/1_6698cc92d7df3.jpeg', 'images/products/1_6698cc92d807d.jpeg', 'images/products/1_6698cc92d82f6.jpeg', '2024-07-18'),
(4, 1, 'electronics', 'Casio FX-991EX', 'Casio ', 'This high-performance scientific calculator is fully functional with a clear display. It has minor scratches on the body but works perfectly.', 800, 6, 'fair', 'available', 'Pune', 'images/products/1_6698ccd379f9a.jpeg', 'images/products/1_6698ccd37a956.jpeg', 'images/products/1_6698ccd37ae97.jpeg', '2024-07-18'),
(5, 1, 'electronics', 'Texas Instruments TI-84 Plus ', 'Texas', 'Popular among students, this graphing calculator has some signs of wear on the buttons but remains fully operational. Screen is clear with no dead pixels.\r\n', 700, 10, 'good', 'available', 'pune', 'images/products/1_6698cd0204c0c.jpeg', 'images/products/1_6698cd020503f.jpeg', 'images/products/1_6698cd0205397.jpeg', '2024-07-18'),
(6, 1, 'electronics', 'HP 35s Scientific Calculator', 'HP ', 'Designed for professionals, this calculator has a few surface scratches but is in good working condition. The display and buttons are fully functional.', 890, 2, 'like new', 'available', 'Sinhgad', 'images/products/1_6698cd498c9fe.jpeg', 'images/products/1_6698cd498ce3a.jpeg', 'images/products/1_6698cd498d126.jpeg', '2024-07-18'),
(7, 1, 'electronics', 'Sharp EL-W516XBSL', 'Sharp ', 'This calculator boasts a WriteView display, 556 scientific functions, and the ability to perform complex calculations, making it a great choice for engineering students.', 780, 5, 'fair', 'available', 'pune', 'images/products/1_6698cdbf89814.jpeg', 'images/products/1_6698cdbf8a247.jpeg', 'images/products/1_6698cdbf8a651.jpeg', '2024-07-18'),
(8, 1, 'electronics', 'Canon F-792SGA Scientific Calculator ', 'Canon', 'The Canon F-792SGA features a large LCD display, 648 scientific functions, and is solar and battery-powered for reliable use. It’s an excellent option for engineering students needing a comprehensive and durable calculator.', 800, 10, 'good', 'available', 'pune', 'images/products/1_6698ce0663da9.jpeg', 'images/products/1_6698ce06640e2.jpeg', 'images/products/1_6698ce06644a3.jpeg', '2024-07-18'),
(9, 1, 'drawings', 'Engineering Drawing Tool Set ', 'Staedtler ', 'This comprehensive set includes scales, rulers, protractors, and other essential drafting tools needed for precise technical drawings. It\'s suitable for both beginners and professionals in engineering disciplines.', 500, 3, 'good', 'available', 'pune', 'images/products/1_6698cead9cdd4.jpeg', 'images/products/1_6698cead9d192.jpeg', 'images/products/1_6698cead9d540.jpeg', '2024-07-18'),
(10, 1, 'drawings', 'Koh-I-Noor Individual Drafter', 'Koh-I-Noor', 'A standalone drafter by Koh-I-Noor, equipped with adjustable angles and measurements, designed for precision in technical drawing tasks. It’s compact, portable, and ideal for engineering students needing a reliable drafter for their projects.', 200, 6, 'like new', 'available', 'pune', 'images/products/1_6698cefc26d2d.jpeg', 'images/products/1_6698cefc27114.jpeg', 'images/products/1_6698cefc274ae.jpeg', '2024-07-18'),
(11, 1, 'drawings', 'Professional Drafting Kit', 'Rotring', 'This kit includes a drafting table, T-square, mechanical pencils, erasers, and other professional-grade tools by Rotring, essential for detailed engineering drawings. Perfect for students and professionals alike.', 100, 6, 'fair', 'available', 'Sinhgad', 'images/products/1_6698cf59141c8.jpeg', 'images/products/1_6698cf59146b1.jpeg', 'images/products/1_6698cf5914abc.jpeg', '2024-07-18');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(3, 8, 1, 2, 'good', '2024-11-28 17:20:40'),
(4, 7, 1, 3, 'best', '2024-11-28 17:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `name`, `duration`, `price`, `created_at`) VALUES
(1, 'Weekly', 7, 350, '2024-11-15 07:25:36'),
(2, 'Monthly', 30, 500, '2024-11-15 07:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `order_id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('success','failed','pending') NOT NULL DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `product_id`, `subscription_id`, `order_id`, `transaction_id`, `amount`, `payment_status`, `payment_date`) VALUES
(45, 1, NULL, 2, 'order_PN8qDeuSz3QZ8n', 'pay_PN8qOt5mSV6MCb', 1000.00, 'success', '2024-11-19 11:09:10'),
(46, 1, NULL, 2, 'order_PN8uIshXFf3RSO', 'pay_PN8uUPJthkPu19', 1000.00, 'success', '2024-11-19 11:13:02');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` bigint(20) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `email` text NOT NULL,
  `phone` bigint(20) NOT NULL,
  `password` text NOT NULL,
  `photo` text NOT NULL,
  `subscription_status` enum('active','inactive') DEFAULT 'inactive',
  `subscription_expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fname`, `lname`, `email`, `phone`, `password`, `photo`, `subscription_status`, `subscription_expiry_date`) VALUES
(1, 'Pranav', 'Pawar', 'pranav@123', 7709176271, '$2y$10$2QkA/fzd0igYjs1ZySoGfuK79z89hs.y0fr1QhwQrzwvmW6qO6Zgu', 'images/users/Iman.webp', 'active', NULL),
(2, 'prajwal', 'pawar', 'prajwal@123', 8766477822, '$2y$10$2poG0.K0j7XwNkL3zcVNauowWkziHEwLIxI9wLwBerveLlcKnYX8m', 'images/users/IMG.jpg', 'inactive', NULL),
(5, 'Shashank', 'Gavale', 'shsshank@gmail.com', 9834431768, '$2y$10$73Yp5VexFlxHa1Ou8ZLSLuLn4yZMlFN5MGjfwtRHYGysOGtYLYgJK', '', 'inactive', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE `user_subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_subscriptions`
--

INSERT INTO `user_subscriptions` (`id`, `user_id`, `subscription_id`, `start_date`, `end_date`, `created_at`) VALUES
(25, 1, 2, '2024-11-19', '2024-12-19', '2024-11-19 11:13:02');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `user_id`, `product_id`, `date_added`) VALUES
(1, 0, 0, '0000-00-00'),
(116, 1, 9, '2024-11-19'),
(119, 1, 10, '2024-11-19'),
(120, 1, NULL, '2024-11-19'),
(121, 1, NULL, '2024-11-19'),
(122, 1, 4, '2024-11-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `pdf_documents`
--
ALTER TABLE `pdf_documents`
  ADD PRIMARY KEY (`pdf_id`);

--
-- Indexes for table `pdf_reviews`
--
ALTER TABLE `pdf_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pdf_wishlist`
--
ALTER TABLE `pdf_wishlist`
  ADD PRIMARY KEY (`wishlist_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- Indexes for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `pdf_documents`
--
ALTER TABLE `pdf_documents`
  MODIFY `pdf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pdf_reviews`
--
ALTER TABLE `pdf_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pdf_wishlist`
--
ALTER TABLE `pdf_wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
