<?php
// includes/functions.php

function sanitize(string $data): string
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function formatCurrency(float|int $amount): string
{
    return number_format($amount, 2) . " ETB";
}

function redirect(string $url, ?string $message = null, string $type = 'success'): void
{
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: $url");
    exit();
}

function displayFlashMessage()
{
    if (isset($_SESSION['flash_message'])) {
        $msg = htmlspecialchars($_SESSION['flash_message']);
        $type = $_SESSION['flash_type'] ?? 'info';
        echo "<div class='alert alert-{$type}'>{$msg}</div>";
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
    }
}

function getCartCount()
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) return 0;
    return array_sum($_SESSION['cart']);
}

/**
 * Check if user has the required role(s). Redirects if not.
 * @param string|array $roles Single role string or array of allowed roles
 */
function checkRole($roles)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . url('public/login.php'));
        exit();
    }
    if (is_string($roles)) {
        $roles = [$roles];
    }
    if (!in_array($_SESSION['role'] ?? '', $roles)) {
        $_SESSION['flash_message'] = "Access denied.";
        $_SESSION['flash_type'] = 'error';
        header("Location: " . url('public/index.php'));
        exit();
    }
}

/**
 * Helper to get correct URLs regardless of environment
 */
function url(string $path): string {
    // This handles the case where the project is in a subdirectory like /ethio-farmers-market/
    $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    
    // If we are already in a subdirectory like /public/ or /public/farmer/, we need to adjust
    if (strpos($base, '/public/farmer') !== false) {
        $base = str_replace('/public/farmer', '', $base);
    } elseif (strpos($base, '/public/admin') !== false) {
        $base = str_replace('/public/admin', '', $base);
    } elseif (strpos($base, '/public') !== false) {
        $base = str_replace('/public', '', $base);
    }
    
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}
