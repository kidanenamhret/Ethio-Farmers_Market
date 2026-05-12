<?php
// includes/session.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isFarmer() {
    return ($_SESSION['role'] ?? '') === 'farmer';
}

function isAdmin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function isCustomer() {
    return ($_SESSION['role'] ?? '') === 'customer';
}
?>