<?php
// public/update-cart.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $id = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);
    
    // Update quantity if item exists
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = $qty;
    }
}

// Redirect back to cart
header("Location: " . url('public/cart.php'));
exit();