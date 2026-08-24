<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Authentication & Session Middleware
 * ==============================================================================
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

/**
 * Initializes a secure session with hardened cookie settings.
 */
function start_secure_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        // Configure secure session cookie parameters before starting
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => 0, // Session cookie expires when browser closes
            'path'     => $cookieParams['path'] ?: '/',
            'domain'   => $cookieParams['domain'] ?: '',
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true, // Mitigate XSS cookie theft
            'samesite' => 'Lax' // Mitigate CSRF
        ]);
        session_start();
    }
}

// Start secure session automatically
start_secure_session();

/**
 * Checks if an administrator user is currently authenticated in the session.
 *
 * @return bool
 */
function is_logged_in(): bool {
    return !empty($_SESSION['admin_user_id']) && !empty($_SESSION['admin_username']);
}

/**
 * Returns current authenticated admin user data.
 *
 * @return array|null
 */
function current_user(): ?array {
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id'       => $_SESSION['admin_user_id'],
        'username' => $_SESSION['admin_username'],
        'email'    => $_SESSION['admin_email'] ?? ''
    ];
}

/**
 * Route protection middleware. Redirects unauthenticated users to the admin login page.
 */
function require_login(): void {
    if (!is_logged_in()) {
        set_flash('danger', 'Access restricted. Please log in with your administrative credentials.');
        header('Location: ' . BASE_URL . 'admin/login.php');
        exit;
    }
}

/**
 * Sets session attributes upon successful authentication and regenerates session ID.
 *
 * @param array $user User record from MySQL database
 */
function login_user(array $user): void {
    // Prevent Session Fixation attack by regenerating session ID
    session_regenerate_id(true);

    $_SESSION['admin_user_id']  = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_email']    = $user['email'];
    $_SESSION['admin_login_at'] = time();
}

/**
 * Destroys all session data and cookies upon logout.
 */
function logout_user(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Unset all session variables
    $_SESSION = [];

    // Delete session cookie if present
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy session storage
    session_destroy();
}
