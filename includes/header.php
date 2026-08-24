<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Public Header Component
 * ==============================================================================
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';

// Determine active page name for nav highlighting
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' . APP_NAME : APP_NAME . ' - IT & Computer Services';
$pageDescription = $pageDescription ?? 'Quetta Tech Solutions - Top-rated computer hardware repair, laptop repair, enterprise networking, CCTV security, and web software development in Quetta.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="keywords" content="Computer Repair Quetta, Laptop Repair, IT Services, CCTV Installation, Networking, Quetta Tech Solutions">
    <meta name="author" content="Quetta Tech Solutions">
    <title><?= e($pageTitle) ?></title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom Modern Stylesheet -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

    <!-- Top Announcement Bar -->
    <div class="top-bar py-2 text-white d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3 font-monospace small">
                <span><i class="bi bi-geo-alt-fill text-info me-1"></i> Zarghoon Road, Quetta</span>
                <span class="text-white-50">|</span>
                <span><i class="bi bi-clock-fill text-info me-1"></i> Mon - Sat: 9:00 AM - 9:00 PM</span>
            </div>
            <div class="d-flex align-items-center gap-3 small">
                <a href="tel:<?= urlencode(APP_PHONE) ?>" class="text-white text-decoration-none hover-cyan">
                    <i class="bi bi-telephone-fill text-info me-1"></i> <?= e(APP_PHONE) ?>
                </a>
                <span class="text-white-50">|</span>
                <?php if (is_logged_in()): ?>
                    <a href="<?= BASE_URL ?>admin/dashboard.php" class="badge bg-info text-dark text-decoration-none px-2 py-1">
                        <i class="bi bi-speedometer2 me-1"></i> Admin Panel
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>admin/login.php" class="text-white-50 text-decoration-none hover-white">
                        <i class="bi bi-lock-fill me-1"></i> Admin Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top main-navbar py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>index.php">
                <div class="brand-icon-box">
                    <i class="bi bi-cpu-fill text-cyan"></i>
                </div>
                <div>
                    <span class="brand-title fw-bold">QUETTA<span class="text-cyan">TECH</span></span>
                    <span class="brand-subtitle d-block">SOLUTIONS</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1 gap-lg-3 py-3 py-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'index' || $currentPage === '') ? 'active' : '' ?>" href="<?= BASE_URL ?>index.php">
                            <i class="bi bi-house-door me-1 d-lg-none"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'about') ? 'active' : '' ?>" href="<?= BASE_URL ?>about.php">
                            <i class="bi bi-info-circle me-1 d-lg-none"></i> About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'services') ? 'active' : '' ?>" href="<?= BASE_URL ?>services.php">
                            <i class="bi bi-tools me-1 d-lg-none"></i> Services & Pricing
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage === 'contact') ? 'active' : '' ?>" href="<?= BASE_URL ?>contact.php">
                            <i class="bi bi-chat-dots me-1 d-lg-none"></i> Contact Us
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="<?= BASE_URL ?>contact.php" class="btn btn-cyan text-dark fw-semibold px-4 rounded-pill shadow-sm w-100 w-lg-auto">
                            <i class="bi bi-calendar2-check me-1"></i> Book Repair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
