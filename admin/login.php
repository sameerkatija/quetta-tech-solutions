<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Login Gateway (admin/login.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Redirect if already authenticated
if (is_logged_in()) {
    header('Location: ' . BASE_URL . 'admin/dashboard.php');
    exit;
}

$error = '';
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $error = 'Security session expired. Please refresh and try again.';
    } else {
        $identifier = sanitize($_POST['identifier'] ?? '');
        $password   = (string)($_POST['password'] ?? '');

        if (empty($identifier) || empty($password)) {
            $error = 'Please provide both your username/email and password.';
        } else {
            try {
                $pdo = getDBConnection();
                $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `username` = :id_user OR `email` = :id_email LIMIT 1");
                $stmt->execute([
                    ':id_user'  => $identifier,
                    ':id_email' => $identifier
                ]);
                $user = $stmt->fetch();

                // Verify password against secure BCRYPT hash stored in database
                if ($user && password_verify($password, $user['password'])) {
                    // Set secure session and regenerate session ID
                    login_user($user);
                    set_flash('success', 'Welcome back, <strong>' . e($user['username']) . '</strong>! You are securely logged in.');
                    header('Location: ' . BASE_URL . 'admin/dashboard.php');
                    exit;
                } else {
                    $error = 'Invalid credentials. Please verify your username and password.';
                }
            } catch (PDOException $e) {
                error_log('Login PDO Error: ' . $e->getMessage());
                $error = 'A database error occurred during login. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?= e(APP_NAME) ?></title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(135deg, #0b132b 0%, #1c2541 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .login-header {
            background: #0f172a;
            color: #ffffff;
            padding: 30px 24px;
            text-align: center;
            border-bottom: 3px solid #00b4d8;
        }
        .btn-cyan {
            background-color: #00b4d8;
            color: #0b132b;
            font-weight: 700;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-cyan:hover {
            background-color: #0096c7;
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="d-inline-flex p-3 rounded-circle bg-info bg-opacity-25 text-info mb-2 fs-3">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h4 class="fw-bold mb-0">Quetta Tech Solutions</h4>
        <span class="small text-white-50">Administrative Access Portal</span>
    </div>

    <div class="p-4 p-md-5">
        <!-- Display Flash Message -->
        <?= display_flash() ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show small d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i>
                <div><?= e($error) ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>admin/login.php" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="identifier" class="form-label small fw-bold text-dark">Username or Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="identifier" name="identifier" 
                           placeholder="admin or admin@quettatech.com" 
                           value="<?= e($identifier) ?>" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small fw-bold text-dark">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-cyan w-100 mb-3 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-2"></i> Log In to Dashboard
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>index.php" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Return to Public Website
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
