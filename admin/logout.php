<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Logout Action (admin/logout.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Destroy session and cookies
logout_user();

set_flash('info', 'You have been safely logged out of the admin panel.');
header('Location: ' . BASE_URL . 'admin/login.php');
exit;
