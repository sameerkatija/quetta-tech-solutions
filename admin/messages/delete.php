<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Delete Contact Message Action (admin/messages/delete.php)
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
        header('Location: ' . BASE_URL . 'admin/messages/index.php');
        exit;
    }

    $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("DELETE FROM `contact_messages` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);

            set_flash('success', 'Inquiry message was removed from inbox.');
        } catch (PDOException $e) {
            error_log('Delete Message DB Error: ' . $e->getMessage());
            set_flash('danger', 'Could not delete message due to database error.');
        }
    } else {
        set_flash('danger', 'Invalid message identifier.');
    }
}

header('Location: ' . BASE_URL . 'admin/messages/index.php');
exit;
