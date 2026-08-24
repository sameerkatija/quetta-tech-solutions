-- ==============================================================================
-- Quetta Tech Solutions - Database Setup Script
-- Compatible with MySQL 5.7+ / MySQL 8.0+ / MariaDB 10.4+
-- ==============================================================================

-- 1. Create database if not exists with UTF-8 character set
CREATE DATABASE IF NOT EXISTS `quetta_tech_solutions`
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `quetta_tech_solutions`;

-- ------------------------------------------------------------------------------
-- 2. Table structure for `users` (Administrators & Staff)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `gallery`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. Table structure for `services`
-- ------------------------------------------------------------------------------
CREATE TABLE `services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_services_user` 
        FOREIGN KEY (`user_id`) 
        REFERENCES `users` (`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. Table structure for `gallery`
-- ------------------------------------------------------------------------------
CREATE TABLE `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `service_id` INT DEFAULT NULL,
    `image` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_gallery_service` 
        FOREIGN KEY (`service_id`) 
        REFERENCES `services` (`id`) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. Table structure for `contact_messages`
-- ------------------------------------------------------------------------------
CREATE TABLE `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `subject` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. Insert Default Admin User
-- Password is: Admin@123 (hashed using PASSWORD_BCRYPT)
-- ------------------------------------------------------------------------------
INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) 
VALUES (1, 'admin', 'admin@quettatech.com', '$2y$10$MnpO8Vez7isgmArBmHjPb.jRQ6q/pzwh2K/Osluxe6gCRQk/hWYdm', NOW());

-- ------------------------------------------------------------------------------
-- 7. Insert Initial Sample Services
-- ------------------------------------------------------------------------------
INSERT INTO `services` (`id`, `user_id`, `title`, `description`, `price`, `image`, `created_at`) VALUES
(1, 1, 'Computer & PC Hardware Repair', 'Comprehensive hardware diagnostics, motherboard chip-level repair, thermal paste replacement, and custom desktop computer assembly for home and office power users.', 2500.00, 'service_computer_repair.jpg', NOW()),
(2, 1, 'Laptop Screen & Hinge Repair', 'Expert laptop repairs including cracked LED/OLED display replacement, damaged hinge reconstruction, keyboard replacement, and power jack fixes.', 3500.00, 'service_laptop_repair.jpg', NOW()),
(3, 1, 'OS & Software Solutions', 'Genuine Windows/Linux OS installation, driver configurations, antivirus setup, data recovery, and corporate office suite software licensing.', 1500.00, 'service_software_installation.jpg', NOW()),
(4, 1, 'Enterprise Networking & Fiber Optic', 'Structured LAN/WAN cabling, managed switch configuration, router setup, Wi-Fi mesh coverage, and point-to-point wireless networking across Quetta.', 8000.00, 'service_networking.jpg', NOW()),
(5, 1, 'CCTV Security & Surveillance Systems', 'HD and IP camera installation, DVR/NVR network configuration, remote mobile phone live monitoring setup, and night vision security deployments.', 6500.00, 'service_cctv.jpg', NOW()),
(6, 1, 'Custom Web & Business Application Development', 'Modern, responsive, SEO-optimized business websites, eCommerce portals, custom web applications, and database management solutions.', 25000.00, 'service_web_development.jpg', NOW());

-- ------------------------------------------------------------------------------
-- 8. Insert Initial Gallery Items (linked to Services)
-- ------------------------------------------------------------------------------
INSERT INTO `gallery` (`id`, `service_id`, `image`, `caption`, `created_at`) VALUES
(1, 1, 'gallery_pc_build.jpg', 'High-end custom workstation build for 3D animation client', NOW()),
(2, 2, 'gallery_laptop_repair.jpg', 'Precision micro-soldering on a gaming laptop motherboard', NOW()),
(3, 4, 'gallery_server_rack.jpg', 'Server rack and patch panel cabling for Quetta business center', NOW()),
(4, 5, 'gallery_cctv_install.jpg', 'High-definition 16-channel IP camera installation in commercial plaza', NOW()),
(5, 6, 'gallery_web_design.jpg', 'Custom responsive web portal developed for a local retailer', NOW()),
(6, 1, 'gallery_hardware_diag.jpg', 'Thermal imaging diagnostic on an overclocked workstation', NOW());

-- ------------------------------------------------------------------------------
-- 9. Insert Sample Contact Messages for Admin Inbox Preview
-- ------------------------------------------------------------------------------
INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'Ahmed Khan', 'ahmed.khan@gmail.com', '0333-7891234', 'Office Network Setup Inquiry', 'Hello Quetta Tech team, we need a complete 20-node LAN setup with Cat6 cabling and Wi-Fi mesh for our new branch on Zarghoon Road. Please share a quotation.', NOW() - INTERVAL 2 DAY),
(2, 'Fatima Baloch', 'fatima.b@yahoo.com', '0312-4567890', 'Gaming Laptop Overheating Issue', 'My Asus ROG laptop is overheating and shutting down while rendering. Do you provide thermal paste replacement and fan servicing?', NOW() - INTERVAL 1 DAY),
(3, 'Tariq Mehmood', 'tariq.m@outlook.com', '0300-9876543', 'School CCTV System Requirement', 'We are looking to install 32 HD night-vision cameras with cloud backup across our school campus. When can your engineer visit for site survey?', NOW() - INTERVAL 4 HOUR);
