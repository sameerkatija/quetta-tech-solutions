-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 01:36 PM
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
-- Database: `quetta_tech_solutions`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'Ahmed Khan', 'ahmed.khan@gmail.com', '0333-7891234', 'Office Network Setup Inquiry', 'Hello Quetta Tech team, we need a complete 20-node LAN setup with Cat6 cabling and Wi-Fi mesh for our new branch on Zarghoon Road. Please share a quotation.', '2026-08-21 12:22:32'),
(2, 'Fatima Baloch', 'fatima.b@yahoo.com', '0312-4567890', 'Gaming Laptop Overheating Issue', 'My Asus ROG laptop is overheating and shutting down while rendering. Do you provide thermal paste replacement and fan servicing?', '2026-08-22 12:22:32'),
(3, 'Tariq Mehmood', 'tariq.m@outlook.com', '0300-9876543', 'School CCTV System Requirement', 'We are looking to install 32 HD night-vision cameras with cloud backup across our school campus. When can your engineer visit for site survey?', '2026-08-23 08:22:32'),
(8, 'Sameer Katija', 'sameerkatija@gmail.com', '03317133969', 'Inquiry regarding Computer & PC Hardware Repair', 'Hi, \r\nI want you install windows in my pc', '2026-08-24 11:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `service_id`, `image`, `caption`, `created_at`) VALUES
(1, 1, 'gallery_pc_build.jpg', 'High-end custom workstation build for 3D animation client', '2026-08-23 12:22:32'),
(2, 2, 'gallery_laptop_repair.jpg', 'Precision micro-soldering on a gaming laptop motherboard', '2026-08-23 12:22:32'),
(3, 4, 'gallery_server_rack.jpg', 'Server rack and patch panel cabling for Quetta business center', '2026-08-23 12:22:32'),
(4, 5, 'gallery_cctv_install.jpg', 'High-definition 16-channel IP camera installation in commercial plaza', '2026-08-23 12:22:32'),
(5, 6, 'gallery_web_design.jpg', 'Custom responsive web portal developed for a local retailer', '2026-08-23 12:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `user_id`, `title`, `description`, `price`, `image`, `created_at`) VALUES
(1, 1, 'Computer & PC Hardware Repair', 'Comprehensive hardware diagnostics, motherboard chip-level repair, thermal paste replacement, and custom desktop computer assembly for home and office power users.', 2500.00, 'service_computer_repair.jpg', '2026-08-23 12:22:32'),
(2, 1, 'Laptop Screen & Hinge Repair', 'Expert laptop repairs including cracked LED/OLED display replacement, damaged hinge reconstruction, keyboard replacement, and power jack fixes.', 3500.00, 'service_laptop_repair.jpg', '2026-08-23 12:22:32'),
(3, 1, 'OS & Software Solutions', 'Genuine Windows/Linux OS installation, driver configurations, antivirus setup, data recovery, and corporate office suite software licensing.', 1500.00, 'service_software_installation.jpg', '2026-08-23 12:22:32'),
(4, 1, 'Enterprise Networking & Fiber Optic', 'Structured LAN/WAN cabling, managed switch configuration, router setup, Wi-Fi mesh coverage, and point-to-point wireless networking across Quetta.', 8000.00, 'service_networking.jpg', '2026-08-23 12:22:32'),
(5, 1, 'CCTV Security & Surveillance Systems', 'HD and IP camera installation, DVR/NVR network configuration, remote mobile phone live monitoring setup, and night vision security deployments.', 6500.00, 'service_cctv.jpg', '2026-08-23 12:22:32'),
(6, 1, 'Custom Web & Business Application Development', 'Modern, responsive, SEO-optimized business websites, eCommerce portals, custom web applications, and database management solutions.', 25000.00, 'service_web_development.jpg', '2026-08-23 12:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@quettatech.com', '$2y$10$MnpO8Vez7isgmArBmHjPb.jRQ6q/pzwh2K/Osluxe6gCRQk/hWYdm', '2026-08-23 12:22:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gallery_service` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_services_user` (`user_id`);

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
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `fk_gallery_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
