<?php
// public/farmer/delete_product.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

checkRole('farmer');

$farmer_id = $_SESSION['user_id'];
$product_id = (int)($_GET['id'] ?? 0);

if ($product_id > 0) {
    // Verify ownership before deleting
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND farmer_id = ?");
    if ($stmt->execute([$product_id, $farmer_id])) {
        redirect(url('public/farmer/dashboard.php'), 'Product removed from marketplace.', 'success');
    } else {
        redirect(url('public/farmer/dashboard.php'), 'Failed to delete product.', 'error');
    }
} else {
    redirect(url('public/farmer/dashboard.php'), 'Invalid product ID.', 'error');
}
?>
