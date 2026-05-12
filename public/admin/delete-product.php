<?php
// public/admin/delete-product.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$product_id = (int)($_GET['id'] ?? 0);

if ($product_id > 0) {
    // Admin can delete ANY product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$product_id])) {
        redirect(url('public/admin/manage-products.php'), 'Product removed from marketplace.', 'success');
    } else {
        redirect(url('public/admin/manage-products.php'), 'Failed to delete product.', 'error');
    }
} else {
    redirect(url('public/admin/manage-products.php'), 'Invalid product ID.', 'error');
}
?>
