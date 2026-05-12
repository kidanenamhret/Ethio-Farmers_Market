<?php
// includes/auth.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/functions.php';

/**
 * Register a new user (customer or farmer)
 * @param PDO $pdo
 * @param string $username
 * @param string $email
 * @param string $password
 * @param string $full_name
 * @param string $phone
 * @param string $address
 * @param string $role (customer, farmer, or admin)
 * @return bool|int Returns the new user_id on success, false on failure
 */
function registerUser($pdo, $username, $email, $password, $full_name, $phone = '', $address = '', $role = 'customer')
{
    // Validate role
    if (!in_array($role, ['customer', 'farmer', 'admin'])) {
        $role = 'customer';
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, phone, password, address, role) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $full_name, $email, $phone, $hashedPassword, $address, $role])) {
            return $pdo->lastInsertId();
        }
        return false;
    } catch (PDOException $e) {
        // Duplicate email/username or other error
        return false;
    }
}

/**
 * Login a user
 * @param PDO $pdo
 * @param string $email
 * @param string $password
 * @return bool
 */
function loginUser($pdo, $email, $password)
{
    $stmt = $pdo->prepare("SELECT id, full_name, email, phone, role, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['role'] = $user['role'];

        // Regenerate session ID for security
        session_regenerate_id(true);
        return true;
    }
    return false;
}

/**
 * Check if logged-in user has a specific role
 * @param string $role
 * @return bool
 */
function hasRole($role)
{
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Require login – redirect to login page if not logged in
 * @param string $redirect URL to return after login
 */
function requireLogin($redirect = null)
{
    if (!isLoggedIn()) {
        $redirectParam = $redirect ? "?redirect=" . urlencode($redirect) : "";
        header("Location: " . url('public/login.php') . $redirectParam);
        exit();
    }
}

/**
 * Require farmer role – redirect if not farmer
 */
function requireFarmer()
{
    requireLogin();
    if (!hasRole('farmer')) {
        $_SESSION['flash_message'] = "Access denied. Farmers only.";
        $_SESSION['flash_type'] = 'error';
        header("Location: " . url('public/index.php'));
        exit();
    }
}

/**
 * Require admin role – redirect if not admin
 */
function requireAdmin()
{
    requireLogin();
    if (!hasRole('admin')) {
        $_SESSION['flash_message'] = "Access denied. Admins only.";
        $_SESSION['flash_type'] = 'error';
        header("Location: " . url('public/index.php'));
        exit();
    }
}

/**
 * Logout user
 */
function logoutUser()
{
    // Unset all session variables
    $_SESSION = [];

    // Destroy session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy session
    session_destroy();

    // Redirect to login
    header("Location: " . url('public/login.php'));
    exit();
}
