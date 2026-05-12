<?php
// public/farmer/update_order_status.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

checkRole('farmer');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? '');
    
    $allowed_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    
    if (in_array($status, $allowed_statuses) && $order_id > 0) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $order_id])) {
            redirect(url('public/farmer/orders.php'), "Order status updated to " . ucfirst($status), "success");
        } else {
            redirect(url('public/farmer/orders.php'), "Failed to update order status.", "error");
        }
    } else {
        redirect(url('public/farmer/orders.php'), "Invalid status or order ID.", "error");
    }
} else {
    header("Location: " . url('public/farmer/orders.php'));
}
exit();
