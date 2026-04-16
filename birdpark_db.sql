-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 05:43 AM
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
-- Database: `birdpark_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `ticket_category` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Paid','Confirmed') NOT NULL DEFAULT 'Pending',
  `receipt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `fullname`, `phone`, `booking_date`, `ticket_category`, `quantity`, `total_price`, `created_at`, `status`, `receipt`) VALUES
(9, 19, 'Ahmad Zaki', '0123456783', '2026-04-22', 'Adult x2, Child x1, ', 4, 150.00, '2026-04-13 13:03:34', 'Confirmed', '1776164977_ahmad_receipt.png'),
(11, 21, 'Mohd Farid', '0123456785', '2026-05-06', 'Adult x1, Child x4, ', 7, 210.00, '2026-04-13 13:06:08', 'Pending', ''),
(12, 22, 'Nurul Huda', '0123456786', '2026-05-13', 'Child x1, Senior x1', 2, 50.00, '2026-04-13 13:07:04', 'Pending', ''),
(14, 24, 'Siti Nurhaliza', '0123456788', '2026-04-23', 'Adult x2, Child x1, ', 10, 270.00, '2026-04-13 13:09:34', 'Paid', '1776252350_siti.pdf'),
(16, 26, 'Aina Sofia', '0123456790', '2026-04-26', 'Adult x1, Child x1, ', 11, 260.00, '2026-04-13 13:12:11', 'Paid', '1776231753_receipt_15_1712345678.jpg'),
(19, 29, 'Haziq Danial', '0123456793', '2026-04-28', 'Adult x4, Child x2, ', 14, 420.00, '2026-04-13 13:15:39', 'Confirmed', '1776250042_receipt.pdf'),
(20, 30, 'Farah Diana', '0123456795', '2026-05-25', 'Adult x3, Senior x1', 4, 170.00, '2026-04-13 13:16:27', 'Paid', '1776251124_aina.pdf'),
(38, 48, 'MONLIKKA A/P CHA NAK', '0187943459', '2026-04-16', 'Adult x5, Child x3, ', 9, 360.00, '2026-04-16 02:17:53', 'Confirmed', '1776305946_monlikka.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullname`, `username`, `password`, `phone`, `role`, `created_at`) VALUES
(19, 'Ahmad Zaki', 'ahmad03', '$2y$10$bHks/DtSNiRzs1NVi219VeDyIQlqluRi44khbJDpjSoA7F5Y6u6lO', '0169375624', 'customer', '2026-04-09 17:03:08'),
(21, 'Mohd Farid', 'farid05', '$2y$10$kB9dBWiXYGWVu8RNqHBSyOjccXG0947guVsUbfkQV2zU7ZCtOzKNa', '0123456785', 'customer', '2026-03-11 18:00:42'),
(22, 'Nurul Huda', 'huda06', '$2y$10$80wKIYfhja1XJhSeKZPLBeltJtcKf1NBv7Ky4vk393FTTQCwHVkH6', '0174835947', 'customer', '2026-03-03 10:06:30'),
(24, 'Siti Nurhaliza', 'siti08', '$2y$10$o9rxHf4xydDyBOy3wOHkaeSEPJAjszPsB8GQMgH48DvRsGE7gp08i', '0163523489', 'customer', '2026-04-12 19:09:10'),
(26, 'Aina Sofia', 'aina10', '$2y$10$A7ZOLS3QQheYY7su9UpwcOGroo7BaNVUHHl8YihrNqqpqNT4//gFG', '0145620975', 'customer', '2026-04-01 21:10:41'),
(29, 'Haziq Danial', 'haziq13', '$2y$10$K.8Tr89j0Li4tLHMdefwWuFffr8UscmYthVoazRSafM2CrRWm0xUG', '0134567390', 'customer', '2026-03-31 20:19:23'),
(30, 'Farah Diana', 'farah15', '$2y$10$5DNpXks2HtHXL3rRM.HyleV0o41Lb617cb/y1hXvPOlKz6m/Ttfr.', '0164329863', 'customer', '2026-03-11 03:16:21'),
(35, 'durga devi', 'durga', '$2y$10$1PJGd4Ypxpf5dEppMKhNseU7Qbfr3Ml8R2OwlHw1StNLwvDIvcXre', '01189754569', 'admin', '2026-04-14 10:13:03'),
(45, 'NUR ARISYA FATIHAH BINTI ZUKRI', 'arisya', '$2y$10$dDZA9i3lEhl40cDrSDQmJux2ZuLJcbZ1RJwwJ2ZMgDVwsK95LbDb6', '1092599086', 'admin', '2026-04-16 02:02:58'),
(46, 'NUR ARISYA FATIHAH BINTI ZUKRI', 'nrarisya', '$2y$10$GtonBGPWo9RDgIsFUSiqrecBYqwzIKpsNNp.T.Meo0wxMC/MtP2H2', '0192599086', 'customer', '2026-04-16 02:10:31'),
(47, 'DURGA DEVI A/P BALASUBRAMANIAM', 'durga devi', '$2y$10$7liwiipvL4FTEk.R/TiRb.zvsaEJVYfOmbov2feQ.zJjV2MJW9dxy', '0187943459', 'customer', '2026-04-16 02:13:30'),
(48, 'MONLIKKA A/P CHA NAK', 'monlikka', '$2y$10$0ZR7IMUc2hudE1x1wzwtyu7AfC.xt9jIfMpJ2itmzVKcpc3753nvu', '0187943459', 'customer', '2026-04-16 02:14:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
