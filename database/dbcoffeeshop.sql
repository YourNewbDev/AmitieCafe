-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 10:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbcoffeeshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`category_id`, `category_name`) VALUES
(1, 'Coffee'),
(2, 'Frappe'),
(9, 'Latte'),
(10, 'With-Coffee'),
(14, '4/2/25');

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `product_desc` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_cost` decimal(10,2) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`product_id`, `product_name`, `subcategory_id`, `product_desc`, `product_price`, `product_cost`, `product_size_id`, `created_at`, `updated_at`) VALUES
(29, 'Iced Caramel Mashatu', 1, '', 123.00, 123.00, 0, '2025-03-23 01:41:10', '2025-03-23 01:41:10'),
(38, '213213', 0, '', 0.00, 0.00, 0, '2025-03-31 04:44:16', NULL),
(39, '321321', 0, '', 0.00, 0.00, 0, '2025-03-31 04:44:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblproductsize`
--

CREATE TABLE `tblproductsize` (
  `product_size_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_size` enum('Small','Regular','Large') NOT NULL,
  `product_size_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproductsize`
--

INSERT INTO `tblproductsize` (`product_size_id`, `product_id`, `product_size`, `product_size_price`) VALUES
(19, 22, '', 0.00),
(20, 23, '', 0.00),
(21, 24, '', 0.00),
(22, 25, '', 1231.00),
(23, 26, 'Large', 123.00),
(24, 27, 'Small', 12321.00),
(25, 28, '', 123.00),
(26, 29, '', 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblsubcategory`
--

CREATE TABLE `tblsubcategory` (
  `subcategory_id` int(11) NOT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubcategory`
--

INSERT INTO `tblsubcategory` (`subcategory_id`, `subcategory_name`, `category_id`) VALUES
(3, 'Matcha', 2),
(4, 'Amitie', 1),
(5, 'Amitie', 10),
(6, 'Choco', 9),
(7, 'Mucho', 1),
(8, 'Wala na number', 1),
(10, 'Americano', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `user_firstName` varchar(255) NOT NULL,
  `user_lastName` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_role` enum('OWNER','STAFF') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `user_firstName`, `user_lastName`, `user_email`, `user_phone`, `user_name`, `user_role`, `password`, `created_at`, `updated_at`) VALUES
(3, '', '', '', '', 'admin', 'OWNER', '$2y$10$jBr/Jq/BLOwADCm/Sze/w.61fov.lBqNDnHYN/PAl0KmfYiRpdeh.', '2025-02-23 22:36:27', NULL),
(4, '', '', '', '', 'cashier', 'STAFF', '$2y$10$z03VguvrnQPxEO3ZYoflM.uy4U/yHkidsGeV6BG7iBudupYAs0xJa', '2025-02-24 05:54:38', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tblproductsize`
--
ALTER TABLE `tblproductsize`
  ADD PRIMARY KEY (`product_size_id`);

--
-- Indexes for table `tblsubcategory`
--
ALTER TABLE `tblsubcategory`
  ADD PRIMARY KEY (`subcategory_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tblproductsize`
--
ALTER TABLE `tblproductsize`
  MODIFY `product_size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tblsubcategory`
--
ALTER TABLE `tblsubcategory`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
