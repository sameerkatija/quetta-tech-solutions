<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Header Component
 * ==============================================================================
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

// Enforce authentication on all admin pages
require_login();

$user = current_user();
$adminPage = $adminPage ?? 'dashboard';
$adminTitle = $adminTitle ?? 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminTitle) ?> | <?= e(APP_NAME) ?> Control Panel</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom Admin Stylesheet -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-wrapper">
    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar">
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="sidebar-brand">
            <div class="brand-icon-box bg-info text-dark" style="width: 38px; height: 38px; font-size: 1.1rem;">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <div>
                <span class="fw-bold fs-6 d-block text-white">QUETTA<span class="text-cyan">TECH</span></span>
                <span class="small text-white-50" style="font-size: 0.65rem; letter-spacing: 0.15em;">ADMIN PANEL</span>
            </div>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-heading">Core Management</li>
            
            <li class="nav-item">
                <a class="nav-link <?= ($adminPage === 'dashboard') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-heading">Content & Catalog</li>

            <li class="nav-item">
                <a class="nav-link <?= ($adminPage === 'services') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/services/index.php">
                    <i class="bi bi-tools"></i>
                    <span>Services CRUD</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= ($adminPage === 'gallery') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/gallery/index.php">
                    <i class="bi bi-images"></i>
                    <span>Gallery CRUD</span>
                </a>
            </li>

            <li class="sidebar-heading">Inquiries & Leads</li>

            <li class="nav-item">
                <a class="nav-link <?= ($adminPage === 'messages') ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/messages/index.php">
                    <i class="bi bi-inbox"></i>
                    <span>Customer Messages</span>
                </a>
            </li>

            <li class="sidebar-heading">Quick Links</li>

            <li class="nav-item">
                <a class="nav-link text-info" href="<?= BASE_URL ?>index.php" target="_blank">
                    <i class="bi bi-box-arrow-up-right text-info"></i>
                    <span>View Public Site</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="<?= BASE_URL ?>admin/logout.php">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <!-- Top Navigation Bar -->
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light d-lg-none" id="sidebarToggle" type="button" aria-label="Toggle Sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark"><?= e($adminTitle) ?></h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-sm-block text-end">
                    <span class="fw-semibold text-dark d-block small"><?= e($user['username']) ?></span>
                    <span class="text-muted small" style="font-size: 0.75rem;"><?= e($user['email']) ?></span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle p-2 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5 text-dark"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><h6 class="dropdown-header">Signed in as <strong><?= e($user['username']) ?></strong></h6></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>index.php" target="_blank"><i class="bi bi-globe me-2"></i> Public Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>admin/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Body Container -->
        <main class="p-4 flex-grow-1">
            <!-- Flash Message Banner -->
            <?= display_flash() ?>
