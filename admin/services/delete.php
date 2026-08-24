<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Delete Service Action (admin/services/delete.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        set_flash('danger', 'Security token invalid. Action aborted.');
        header('Location: ' . BASE_URL . 'admin/services/index.php');
        exit;
    }

    $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $pdo = getDBConnection();
            
            // 1. Fetch existing record to retrieve image filename
            $stmt = $pdo->prepare("SELECT * FROM `services` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $service = $stmt->fetch();

            if ($service) {
                // 2. Delete physical image file from storage
                if (!empty($service['image'])) {
                    delete_uploaded_file(SERVICES_UPLOAD_PATH . DIRECTORY_SEPARATOR . $service['image']);
                }

                // 3. Delete record from database (foreign key cascade handles relations)
                $deleteStmt = $pdo->prepare("DELETE FROM `services` WHERE `id` = :id");
                $deleteStmt->execute([':id' => $id]);

                set_flash('success', 'Service "<strong>' . e($service['title']) . '</strong>" and its associated image were permanently deleted.');
            } else {
                set_flash('danger', 'The requested service was not found in the database.');
            }
        } catch (PDOException $e) {
            error_log('Delete Service PDO Error: ' . $e->getMessage());
            set_flash('danger', 'Could not delete service due to a database error.');
        }
    } else {
        set_flash('danger', 'Invalid service identifier provided.');
    }
}

header('Location: ' . BASE_URL . 'admin/services/index.php');
exit;
