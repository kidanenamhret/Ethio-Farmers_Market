<?php
// includes/header.php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/functions.php';
ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ethio Farmers Market | Premium Local Produce</title>
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="<?php echo url('assets/images/favicon.png'); ?>">
    <script>
        const BASE_URL = '<?php echo url(""); ?>';
    </script>
</head>
<body>

<div class="app-container">
    <!-- Left Sidebar (Premium Modern Design) -->
    <aside class="main-sidebar" id="mainSidebar">

        <a href="<?php echo url('public/index.php'); ?>" class="sidebar-logo">
            <i class="fas fa-leaf"></i>
            <span>Ethio Farmers</span>
        </a>

        <nav class="sidebar-nav">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li><a href="<?php echo url('public/index.php'); ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-house-chimney"></i> <span>Home</span>
                </a></li>
                <li><a href="<?php echo url('public/products.php'); ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shop"></i> <span>Marketplace</span>
                </a></li>
                <li><a href="<?php echo url('public/about.php'); ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                    <i class="fas fa-circle-info"></i> <span>About Us</span>
                </a></li>
                
                <?php if (isLoggedIn()): ?>
                    <li style="margin-top: 2rem; margin-bottom: 1rem; padding-left: 1.2rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); font-weight: 800;">
                        <span>Personal</span>
                    </li>
                    <?php if ($_SESSION['role'] == 'farmer'): ?>
                        <li><a href="<?php echo url('public/farmer/dashboard.php'); ?>" class="<?php echo strpos($_SERVER['PHP_SELF'], '/farmer/') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-chart-pie"></i> <span>My Dashboard</span>
                        </a></li>
                        <li><a href="<?php echo url('public/farmer/add-product.php'); ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'add-product.php' ? 'active' : ''?>">
                            <i class="fas fa-plus-circle"></i> <span>Add Product</span>
                        </a></li>
                    <?php elseif ($_SESSION['role'] == 'admin'): ?>
                        <li><a href="<?php echo url('public/admin/dashboard.php'); ?>" class="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-user-shield"></i> <span>Admin Panel</span>
                        </a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo url('public/order-history.php'); ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'order-history.php' ? 'active' : ''; ?>">
                        <i class="fas fa-receipt"></i> <span>My Orders</span>
                    </a></li>
                <?php else: ?>
                    <li style="margin-top: 2rem;">
                        <a href="<?php echo url('public/register.php?role=farmer'); ?>" style="color: var(--primary); font-weight: 800; border: 2px solid var(--primary-glow); background: var(--primary-glow);">
                            <i class="fas fa-tractor"></i> <span>Join as Farmer</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="sidebar-footer" style="padding: 1.5rem 1.2rem; margin-top: auto; border-top: 1px solid var(--border-light);">
            <div style="padding: 1.5rem; border-radius: 20px; background: var(--bg-main); text-align: center;">
                <div style="width: 40px; height: 40px; background: var(--primary-glow); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 1.2rem;">
                    <i class="fas fa-headset"></i>
                </div>
                <div style="font-size: 0.85rem; font-weight: 850; color: var(--text-main); margin-bottom: 5px;">Need Help?</div>
                <a href="tel:+251911223344" style="font-size: 0.75rem; color: var(--primary); font-weight: 800; display: block; margin-bottom: 3px; text-decoration: none;">+251 911 223 344</a>
                <a href="mailto:support@ethiofarmers.com" style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-decoration: none;">support@ethiofarmers.com</a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content-area" id="mainContent">
        <!-- Top Navigation Bar -->
        <header class="top-navbar" id="topNavbar">
            <div class="nav-left">
                <button id="sidebarToggle" style="background: var(--white); border: 1px solid var(--border-light); width: 55px; height: 55px; border-radius: 18px; font-size: 1.4rem; cursor: pointer; color: var(--primary); display: flex; align-items: center; justify-content: center; transition: var(--transition); box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <i class="fas fa-bars-staggered"></i>
                </button>
            </div>

            <div class="nav-right" style="display: flex; align-items: center; gap: 1.5rem;">
                <a href="<?php echo url('public/cart.php'); ?>" class="cart-modern">
                    <div class="cart-icon-wrapper">
                        <i class="fas fa-basket-shopping"></i>
                        <?php $cartCount = getCartCount(); ?>
                        <span class="cart-badge <?php echo $cartCount > 0 ? 'active' : ''; ?>">
                            <?php echo $cartCount; ?>
                        </span>
                    </div>
                    <span class="cart-label">Cart</span>
                </a>

                <?php if (isLoggedIn()): ?>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="user-profile" style="display: flex; align-items: center; gap: 12px; padding: 6px 16px 6px 6px; border-radius: 16px; background: var(--white); border: 1px solid var(--border-light); box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                            <div class="avatar" style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem;">
                                <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                            </div>
                            <div style="font-weight: 800; font-size: 0.95rem; color: var(--text-main);">Hi, <?php echo explode(' ', $_SESSION['full_name'])[0]; ?></div>
                        </div>
                        <a href="<?php echo url('public/logout.php'); ?>" class="logout-modern" title="Logout">
                            <i class="fas fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <a href="<?php echo url('public/login.php'); ?>" style="font-weight: 800; color: var(--text-main); text-decoration: none; padding: 12px 20px; font-size: 0.95rem;">Login</a>
                        <a href="<?php echo url('public/register.php'); ?>" class="btn btn-primary" style="padding: 12px 25px; border-radius: 16px; font-size: 0.95rem;">Join Us</a>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <div class="content-body" style="padding: 2rem;">
            <?php displayFlashMessage(); ?>
