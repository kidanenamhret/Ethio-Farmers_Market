<?php
// public/admin/delete-user.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$user_id = (int)($_GET['id'] ?? 0);

if ($user_id > 0) {
    if ($user_id == $_SESSION['user_id']) {
        redirect(url('public/admin/manage-users.php'), 'You cannot delete your own account.', 'error');
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        redirect(url('public/admin/manage-users.php'), 'User removed successfully.', 'success');
    } else {
        redirect(url('public/admin/manage-users.php'), 'Failed to delete user.', 'error');
    }
} else {
    redirect(url('public/admin/manage-users.php'), 'Invalid user ID.', 'error');
}
?>
