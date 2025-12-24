-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2025 at 04:25 AM
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
-- Database: `db_fashion`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_address` text DEFAULT NULL,
  `status` enum('waiting_payment','verifying','paid','shipped','completed','ditolak','pending') NOT NULL DEFAULT 'pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_method` enum('Bank Transfer') NOT NULL DEFAULT 'Bank Transfer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `shipping_cost`, `shipping_address`, `status`, `payment_proof`, `payment_method`, `created_at`) VALUES
(1, 3, 24690000.00, 0.00, NULL, 'ditolak', NULL, 'Bank Transfer', '2025-06-24 08:43:31'),
(2, 3, 24690000.00, 0.00, NULL, 'completed', 'proof_2_1750813378.png', 'Bank Transfer', '2025-06-24 14:54:00'),
(3, 3, 10000.00, 0.00, NULL, 'waiting_payment', NULL, 'Bank Transfer', '2025-06-25 00:59:16'),
(4, 3, 10000.00, 0.00, NULL, 'paid', 'proof_4_1750813775.png', 'Bank Transfer', '2025-06-25 01:09:27'),
(5, 3, 10000.00, 0.00, NULL, 'shipped', 'proof_5_1750853047.jpg', 'Bank Transfer', '2025-06-25 12:04:00'),
(6, 3, 60000.00, 0.00, NULL, 'waiting_payment', NULL, 'Bank Transfer', '2025-06-28 00:49:26'),
(7, 3, 10000.00, 0.00, NULL, 'completed', 'proof_7_1751422403.jpg', 'Bank Transfer', '2025-07-02 02:13:02'),
(8, 3, 12230000.00, 0.00, NULL, 'completed', 'proof_8_1751445744.jpg', 'Bank Transfer', '2025-07-02 08:42:11'),
(9, 3, 10000.00, 0.00, NULL, 'completed', 'proof_9_1751754434.jpg', 'Bank Transfer', '2025-07-05 22:27:08'),
(10, 3, 12230000.00, 0.00, NULL, 'completed', 'proof_10_1751755286.jpg', 'Bank Transfer', '2025-07-05 22:41:22'),
(11, 3, 10000.00, 0.00, NULL, 'waiting_payment', NULL, 'Bank Transfer', '2025-07-06 04:05:51'),
(12, 3, 10000.00, 0.00, NULL, 'waiting_payment', NULL, '', '2025-07-06 04:25:43'),
(13, 3, 12230000.00, 0.00, NULL, 'waiting_payment', NULL, '', '2025-07-07 05:46:59'),
(14, 3, 12245000.00, 15000.00, 'Jl Cakung, Kecamatan Cakung', 'ditolak', NULL, 'Bank Transfer', '2025-07-09 01:09:12'),
(15, 4, 2113000.00, 15000.00, 'jl. jakasampurna, Kecamatan Jatinegara', 'shipped', 'proof_15_1752052010.png', 'Bank Transfer', '2025-07-09 09:06:23'),
(16, 4, 314000.00, 15000.00, 'jl. jakasampurna, Kecamatan Kramat Jati', 'shipped', NULL, 'Bank Transfer', '2025-07-09 09:33:04'),
(17, 4, 465000.00, 15000.00, 'test, Kecamatan Jatinegara', 'ditolak', 'proof_17_1752227671.png', 'Bank Transfer', '2025-07-11 09:54:20'),
(18, 3, 465000.00, 15000.00, 'jl jatinegara, Kecamatan Jatinegara', 'waiting_payment', NULL, 'Bank Transfer', '2025-07-12 00:41:05'),
(19, 3, 314000.00, 15000.00, 'jl jatinegara, Kecamatan Jatinegara, Kecamatan Jatinegara', 'paid', NULL, 'Bank Transfer', '2025-07-14 06:05:55'),
(20, 3, 314000.00, 15000.00, 'jl jatinegara, Kecamatan Jatinegara, Kecamatan Jatinegara, Kecamatan Jatinegara', 'paid', 'proof_20_1752561433.png', 'Bank Transfer', '2025-07-15 06:35:13'),
(21, 3, 3164000.00, 15000.00, 'Jl Duren Sawit, Kecamatan Duren Sawit', 'shipped', 'proof_21_1752562301.png', 'Bank Transfer', '2025-07-15 06:51:28'),
(22, 3, 465000.00, 15000.00, 'Jl Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit', 'completed', 'proof_22_1752580313.png', 'Bank Transfer', '2025-07-15 11:51:34'),
(23, 3, 315000.00, 15000.00, 'Jl Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit', 'paid', 'proof_23_1752580449.png', 'Bank Transfer', '2025-07-15 11:54:04'),
(24, 4, 615000.00, 15000.00, 'test, Kecamatan Jatinegara, Kecamatan Jatinegara', 'waiting_payment', NULL, 'Bank Transfer', '2025-07-15 12:05:36'),
(25, 3, 314000.00, 15000.00, 'Jl Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit', 'paid', 'proof_25_1752743777.png', 'Bank Transfer', '2025-07-17 09:11:44'),
(26, 3, 314000.00, 15000.00, 'Jl Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit, Kecamatan Duren Sawit', 'paid', 'proof_26_1752744002.png', 'Bank Transfer', '2025-07-17 09:19:56'),
(27, 3, 462000.00, 12000.00, 'Jl Duren Sawit, Kecamatan Duren Sawit,, Kecamatan Jatinegara', 'verifying', 'proof_27_1752817587.jpg', 'Bank Transfer', '2025-07-18 05:44:28'),
(28, 3, 317000.00, 18000.00, 'Jl Duren Sawit, Kecamatan Cipayung', 'waiting_payment', NULL, 'Bank Transfer', '2025-07-26 00:46:31'),
(29, 3, 311000.00, 12000.00, 'Jl Duren Sawit, Kecamatan Kramat Jati', 'verifying', 'proof_29_1753492646.png', 'Bank Transfer', '2025-07-26 01:06:17'),
(30, 5, 182000.00, 12000.00, 'Jl. Kemuning Bendungan No 42 Jatinegara Jakarta Timur, Kecamatan Jatinegara', 'completed', 'proof_30_1753761072.png', 'Bank Transfer', '2025-07-29 03:38:40'),
(31, 3, 85000.00, 15000.00, 'Jl Duren Sawit, Kecamatan Duren Sawit', 'waiting_payment', NULL, 'Bank Transfer', '2025-07-29 13:04:47');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 12345000.00),
(2, 2, 1, 2, 12345000.00),
(3, 3, 1, 1, 10000.00),
(4, 4, 1, 1, 10000.00),
(5, 5, 1, 1, 10000.00),
(6, 6, 1, 6, 10000.00),
(7, 7, 1, 1, 10000.00),
(8, 8, 6, 1, 12230000.00),
(9, 9, 1, 1, 10000.00),
(10, 10, 6, 1, 12230000.00),
(11, 11, 1, 1, 10000.00),
(12, 12, 1, 1, 10000.00),
(13, 13, 6, 1, 12230000.00),
(14, 14, 6, 1, 12230000.00),
(15, 15, 6, 2, 300000.00),
(16, 15, 5, 2, 450000.00),
(17, 15, 1, 2, 299000.00),
(18, 16, 1, 1, 299000.00),
(19, 17, 5, 1, 450000.00),
(20, 18, 5, 1, 450000.00),
(21, 19, 1, 1, 299000.00),
(22, 20, 1, 1, 299000.00),
(23, 21, 5, 3, 450000.00),
(24, 21, 6, 5, 300000.00),
(25, 21, 1, 1, 299000.00),
(26, 22, 5, 1, 450000.00),
(27, 23, 6, 1, 300000.00),
(28, 24, 6, 2, 300000.00),
(29, 25, 1, 1, 299000.00),
(30, 26, 1, 1, 299000.00),
(31, 27, 5, 1, 450000.00),
(32, 28, 1, 1, 299000.00),
(33, 29, 1, 1, 299000.00),
(34, 30, 11, 1, 170000.00),
(35, 31, 12, 1, 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` enum('Polo','Sweater','Kaos','Pakaian Wanita') NOT NULL DEFAULT 'Kaos',
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category`, `price`, `stock`, `image`, `is_active`, `created_at`) VALUES
(1, 'Polo Merah - L', 'Polo Merah dengan warna yang elegan', 'Polo', 299000.00, 27, '1753702398_62bc8f20-a707-43c9-af12-9b6a7c2c232c_removalai_preview.png', 1, '2025-06-23 12:39:12'),
(5, 'Sweater Hijau - XL', 'Sweater Hijau - XL', 'Sweater', 180000.00, 19, '1753704164_c7cf219c-3ca9-4137-b211-e81e287186da_removalai_preview.png', 1, '2025-07-02 08:02:22'),
(6, 'Polo Putih - XL', 'Polo Putih dengan desain elegan', 'Polo', 150000.00, 80, '1753702363_1c3e8306-5dbe-40c7-ba6c-977f7a7dfa79_removalai_preview.png', 1, '2025-07-02 08:41:11'),
(7, 'Kaos Pria Warna Krem - XL', 'Kaos Kuning Ukuran XL', 'Kaos', 80000.00, 20, '1753702333_Kaos Krem.png', 1, '2025-07-28 10:26:24'),
(8, 'Kaos Biru Pria - M', 'Kaos Biru Pria L', 'Kaos', 80000.00, 20, '1753704982_Kaos Biru (2).png', 1, '2025-07-28 10:28:46'),
(9, 'Sweater Abu-Abu - L', 'Sweater Abu-Abu', 'Sweater', 140000.00, 25, '1753705043_Sweater Abu.png', 1, '2025-07-28 12:17:23'),
(10, 'Kaos Orange - XL', 'Kaos Orange ', 'Kaos', 90000.00, 6, '1753705356_Gemini_Generated_Image_ldunnxldunnxldun-removebg-preview.png', 1, '2025-07-28 12:22:36'),
(11, 'Sweater Abu-Abu Terang - M', 'Sweater Abu-Abu Terang - M', 'Sweater', 170000.00, 3, '1753707451_Gemini_Generated_Image_6ygvwh6ygvwh6ygv__1_-removebg-preview.png', 1, '2025-07-28 12:57:31'),
(12, 'Pakaian Wanita Motif Coklat', 'Pakaian Wanita Motif Coklat', 'Pakaian Wanita', 70000.00, 10, '1753715871_Gemini_Generated_Image_25d2jv25d2jv25d2-removebg-preview.png', 1, '2025-07-28 15:17:51'),
(13, 'Pakaian Wanita Warna Putih', 'Pakaian Wanita Warna Putih', 'Pakaian Wanita', 95000.00, 20, '1753836840_Gemini_Generated_Image_177fy0177fy0177f (1).png', 1, '2025-07-30 00:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `address`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@toko.com', '$2y$10$coUh.D0PABJg2U25G/c4b.9g4G0f.aWc2/Jooa2tIpV2y.W52O5.a', 'Administrator', NULL, 'admin', '2025-06-20 18:54:05'),
(2, 'rakhakumara', 'aditisna.raka@gmail.com', '$2y$10$oEZtGTOfEuCVByzoUnaSYOC4LZUtLBvI3ZevWmdGJ9R2.Ul7QVTrO', NULL, NULL, 'admin', '2025-06-20 18:59:59'),
(3, 'fajars', 'fajarfeb@gmail.com', '$2y$10$r85xCUke4Ced8SSoqmnpG.P7eUjQ8h2YPaPjw8Dxxn/vd506GJ4E6', 'Fajar Febrian', 'Jl Duren Sawit, Kecamatan Duren Sawit', 'user', '2025-06-24 03:08:10'),
(4, 'nisa', 'nisa@gmail.com', '$2y$10$7n3ThgUeBWIQV77r8Je7QeW/w5QYyEgbNOCodY0tqrof7KVQ5z56e', NULL, 'test, Kecamatan Jatinegara, Kecamatan Jatinegara', 'user', '2025-07-09 08:58:37'),
(5, 'rakhaaditisna', 'rakhaaditisna@gmail.com', '$2y$10$5vrO07zSH./kg.8ywXuhnOTGg8VPuzwuyu.bEA1iLJ0mdLswsUklS', NULL, 'Jl. Kemuning Bendungan No 42 Jatinegara Jakarta Timur, Kecamatan Jatinegara', 'user', '2025-07-28 04:24:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
