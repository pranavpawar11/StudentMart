-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 11, 2024 at 08:30 PM
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
(146, 2, 7, '2024-08-11');

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
(38, 1, 1, 7, '2024-07-18 12:54:33', 'New Request', 0),
(39, 1, 2, 8, '2024-07-18 18:09:17', 'New Request', 0),
(40, 1, 2, 8, '2024-07-19 18:59:05', 'New Request', 0),
(41, 1, 2, 8, '2024-07-19 19:06:55', 'New Request', 0),
(42, 1, 2, 2, '2024-07-19 19:07:23', 'New Request', 0),
(43, 1, 2, 8, '2024-08-11 17:07:41', 'New Request', 0);

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
  `order_date` date NOT NULL,
  `address` text NOT NULL,
  `total_price` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `product_id`, `buyer_id`, `seller_id`, `status`, `order_date`, `address`, `total_price`) VALUES
(127, 7, 1, 1, 'pending', '2024-07-18', 'Pune', 760),
(128, 8, 2, 1, 'pending', '2024-07-18', 'pune', 780),
(129, 8, 2, 1, 'pending', '2024-07-20', 'Pune', 780),
(130, 8, 2, 1, 'pending', '2024-07-20', 'Pune', 780),
(131, 2, 2, 1, 'pending', '2024-07-20', 'Pune', 120),
(132, 8, 2, 1, 'pending', '2024-08-11', 'Pune', 780);

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
(1, 1, 'books', 'Engineering Mathematics', 'Nirali , John Smith', 'This textbook is well-maintained with some highlighting on key sections. The cover shows minor signs of wear, but all pages are intact and legible. Perfect for college students and educators.\r\n', 100, 6, 'good', 'available', 'Sinhgad', 'images/products/1_6698cb5f65ff7.jpg', 'images/products/1_6698cb5f66792.jpg', 'images/products/1_6698cb5f66b78.jpg', '2024-07-18'),
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
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `response_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `order_id`, `request_date`, `status`, `message`, `response_date`) VALUES
(49, 127, '2024-07-18', 'approved', 'buy request from pune', '2024-07-18'),
(50, 128, '2024-07-18', 'completed', 'buy', '2024-07-18'),
(51, 129, '2024-07-20', 'approved', 'want to buy', '2024-07-20'),
(52, 130, '2024-07-20', 'approved', 'want to buy', '2024-07-20'),
(53, 131, '2024-07-20', 'declined', 'want to buy', '2024-07-20'),
(54, 132, '2024-08-11', 'pending', 'i\'m from pune want to buy this', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `response_id` bigint(20) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fname`, `lname`, `email`, `phone`, `password`, `photo`) VALUES
(1, 'Pranav', 'Pawar', 'pranav@123', 7709176271, '$2y$10$2QkA/fzd0igYjs1ZySoGfuK79z89hs.y0fr1QhwQrzwvmW6qO6Zgu', 'images/users/Screenshot_2022-11-11-18-54-41-084_com.miui.gallery.jpg'),
(2, 'prajwal', 'pawar', 'prajwal@123', 8766477822, '$2y$10$2poG0.K0j7XwNkL3zcVNauowWkziHEwLIxI9wLwBerveLlcKnYX8m', ''),
(5, 'Shashank', 'Gavale', 'shsshank@gmail.com', 9834431768, '$2y$10$73Yp5VexFlxHa1Ou8ZLSLuLn4yZMlFN5MGjfwtRHYGysOGtYLYgJK', '');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

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
  MODIFY `cart_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `response_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
