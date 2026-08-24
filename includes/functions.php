<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Global Helper Functions & Security Utilities
 * ==============================================================================
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Escapes HTML characters for output in templates (XSS mitigation).
 *
 * @param mixed $value
 * @return string
 */
function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Strips whitespace, tags, and slashes from user input strings.
 *
 * @param mixed $data
 * @return string
 */
function sanitize($data): string {
    if (is_array($data)) {
        return '';
    }
    $data = trim((string)$data);
    $data = stripslashes($data);
    return strip_tags($data);
}

/**
 * Formats a decimal currency number as PKR currency.
 *
 * @param float|int|string $amount
 * @return string
 */
function format_price($amount): string {
    $num = (float)$amount;
    return 'PKR ' . number_format($num, 2);
}

/**
 * Returns human readable relative time (e.g., "2 hours ago", "Yesterday").
 *
 * @param string $datetime
 * @return string
 */
function time_ago(string $datetime): string {
    $timestamp = strtotime($datetime);
    if (!$timestamp) return $datetime;

    $difference = time() - $timestamp;

    if ($difference < 60) {
        return 'Just now';
    } elseif ($difference < 3600) {
        $mins = floor($difference / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($difference < 604800) {
        $days = floor($difference / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M d, Y', $timestamp);
    }
}

/**
 * Generates or retrieves an active CSRF token from the session.
 *
 * @return string
 */
function csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Outputs a hidden input field containing the active CSRF token.
 *
 * @return string
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Validates a submitted CSRF token against the stored session token.
 *
 * @param string|null $token
 * @return bool
 */
function verify_csrf(?string $token): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sets a flash message to be displayed once on the subsequent request.
 *
 * @param string $type success|danger|warning|info
 * @param string $message
 */
function set_flash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_message'] = [
        'type'    => $type,
        'message' => $message
    ];
}

/**
 * Retrieves and clears the active flash message if set.
 *
 * @return array|null
 */
function get_flash(): ?array {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Renders an HTML alert container for the active flash message if one exists.
 *
 * @return string
 */
function display_flash(): string {
    $flash = get_flash();
    if (!$flash) {
        return '';
    }
    
    $iconMap = [
        'success' => 'bi-check-circle-fill',
        'danger'  => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info'    => 'bi-info-circle-fill'
    ];
    $icon = $iconMap[$flash['type']] ?? 'bi-info-circle-fill';

    return '
    <div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
        <i class="bi ' . $icon . ' me-2 flex-shrink-0 fs-5"></i>
        <div>' . $flash['message'] . '</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

/**
 * Securely handles image file uploads with validation.
 *
 * @param array $file The $_FILES['image'] item
 * @param string $targetDir Target folder on disk
 * @param array $allowedExtensions Valid file extensions
 * @param int $maxSizeBytes Maximum allowed file size in bytes (default 5MB)
 * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
 */
function upload_image(
    array $file, 
    string $targetDir, 
    array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'], 
    int $maxSizeBytes = 5242880
): array {
    // Check for upload errors
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'filename' => null, 'error' => 'Invalid file parameter uploaded.'];
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'filename' => null, 'error' => 'No file was uploaded.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => null, 'error' => 'File upload error code: ' . $file['error']];
    }

    // Validate file size
    if ($file['size'] > $maxSizeBytes) {
        $mb = round($maxSizeBytes / (1024 * 1024));
        return ['success' => false, 'filename' => null, 'error' => "File exceeds the maximum permitted size of {$mb}MB."];
    }

    // Validate file extension
    $originalName = $file['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        return [
            'success' => false, 
            'filename' => null, 
            'error' => 'Invalid file format. Only ' . strtoupper(implode(', ', $allowedExtensions)) . ' are permitted.'
        ];
    }

    // Validate true MIME type using Fileinfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $validMimes = [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
        'image/webp'
    ];

    if (!in_array($mime, $validMimes, true)) {
        return ['success' => false, 'filename' => null, 'error' => 'Uploaded file is not a valid genuine image (' . $mime . ').'];
    }

    // Generate unique, collision-proof filename
    $uniqueFilename = bin2hex(random_bytes(10)) . '_' . time() . '.' . $extension;
    $destination = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uniqueFilename;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Move uploaded file to destination
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'filename' => null, 'error' => 'Failed to save uploaded file to storage.'];
    }

    return ['success' => true, 'filename' => $uniqueFilename, 'error' => null];
}

/**
 * Safely removes a file from disk if it exists.
 *
 * @param string $filePath Full system path to the file
 * @return bool
 */
function delete_uploaded_file(?string $filePath): bool {
    if (!empty($filePath) && file_exists($filePath) && is_file($filePath)) {
        return @unlink($filePath);
    }
    return false;
}
