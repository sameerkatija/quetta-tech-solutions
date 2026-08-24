<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Database Configuration & Global Constants
 * ==============================================================================
 * Provides a secure, robust PDO connection using prepared statements.
 */

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'quetta_tech_solutions');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

// Application Constants
define('APP_NAME', 'Quetta Tech Solutions');
define('APP_TAGLINE', 'Expert Computer, Laptop & IT Infrastructure Services');
define('APP_PHONE', '+92 333 7891234');
define('APP_EMAIL', 'info@quettatech.com');
define('APP_ADDRESS', 'Suite #14, Al-Rehman Plaza, Zarghoon Road, Quetta, Balochistan, Pakistan');
define('APP_HOURS', 'Mon - Sat: 9:00 AM - 9:00 PM | Sunday: 11:00 AM - 6:00 PM');

// File Upload Directories (Absolute System Paths)
define('ROOT_PATH', dirname(__DIR__));
define('UPLOADS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');
define('SERVICES_UPLOAD_PATH', UPLOADS_PATH . DIRECTORY_SEPARATOR . 'services');
define('GALLERY_UPLOAD_PATH', UPLOADS_PATH . DIRECTORY_SEPARATOR . 'gallery');

// Ensure upload folders exist
if (!is_dir(UPLOADS_PATH)) {
    @mkdir(UPLOADS_PATH, 0777, true);
}
if (!is_dir(SERVICES_UPLOAD_PATH)) {
    @mkdir(SERVICES_UPLOAD_PATH, 0777, true);
}
if (!is_dir(GALLERY_UPLOAD_PATH)) {
    @mkdir(GALLERY_UPLOAD_PATH, 0777, true);
}

/**
 * Dynamically determine the Base URL regardless of server subfolder or virtual host.
 * E.g. http://localhost/ or http://localhost/quetta-tech-solutions/
 */
function get_base_url(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Calculate folder offset from document root
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ROOT_PATH));
    $appRoot = str_replace('\\', '/', ROOT_PATH);
    $subDir = trim(str_replace($docRoot, '', $appRoot), '/');
    
    $baseUrl = $protocol . $host . ($subDir ? '/' . $subDir : '');
    return rtrim($baseUrl, '/') . '/';
}

define('BASE_URL', get_base_url());

/**
 * Returns a shared PDO database connection instance.
 * Configured with strict error throwing and prepared statement emulation disabled.
 *
 * @return PDO
 * @throws PDOException
 */
function getDBConnection(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real native prepared statements
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Friendly error message for users while logging full trace in production
            error_log("Database Connection Error: " . $e->getMessage());
            die("<div style='font-family:sans-serif; padding:2rem; margin:2rem auto; max-width:600px; background:#fff3cd; color:#856404; border:1px solid #ffeeba; border-radius:8px;'>
                    <h3 style='margin-top:0;'>Database Connection Error</h3>
                    <p>Could not connect to the <strong>" . htmlspecialchars(DB_NAME) . "</strong> database. Please ensure your MySQL server is running in XAMPP.</p>
                    <p><small>Details: " . htmlspecialchars($e->getMessage()) . "</small></p>
                 </div>");
        }
    }

    return $pdo;
}
