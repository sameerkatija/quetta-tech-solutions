<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Delete Gallery Item (admin/gallery/delete.php)
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
        header('Location: ' . BASE_URL . 'admin/gallery/index.php');
        exit;
    }

    $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $pdo = getDBConnection();

            // Fetch record to get image filename
            $stmt = $pdo->prepare("SELECT * FROM `gallery` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $item = $stmt->fetch();

            if ($item) {
                // Delete physical image from disk
                if (!empty($item['image'])) {
                    delete_uploaded_file(GALLERY_UPLOAD_PATH . DIRECTORY_SEPARATOR . $item['image']);
                }

                // Delete record from database
                $delStmt = $pdo->prepare("DELETE FROM `gallery` WHERE `id` = :id");
                $delStmt->execute([':id' => $id]);

                set_flash('success', 'Gallery image was permanently deleted.');
            } else {
                set_flash('danger', 'Gallery item not found in database.');
            }
        } catch (PDOException $e) {
            error_log('Delete Gallery DB Error: ' . $e->getMessage());
            set_flash('danger', 'Could not delete gallery image due to database error.');
        }
    } else {
        set_flash('danger', 'Invalid gallery identifier.');
    }
}

header('Location: ' . BASE_URL . 'admin/gallery/index.php');
exit;
