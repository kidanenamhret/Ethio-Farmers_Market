<?php
// public/admin/dashboard.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

// Stats
$user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$order_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$cat_count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled'")->fetchColumn() ?: 0;

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <div style="margin-bottom: 4rem;">
        <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Marketplace <span style="color: var(--primary);">Overview</span></h1>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Central command for Ethio Farmers Market</p>
    </div>

    <!-- Admin Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-bottom: 5rem;">
        <div class="premium-card" style="padding: 2.5rem; border-top: 5px solid var(--primary);">
            <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem;">Total Revenue</div>
            <div style="font-size: 2.2rem; font-weight: 900; color: var(--text-main);"><?php echo formatCurrency($revenue); ?></div>
        </div>
        <div class="premium-card" style="padding: 2.5rem; border-top: 5px solid #3b82f6;">
            <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem;">Registered Users</div>
            <div style="font-size: 2.2rem; font-weight: 900; color: var(--text-main);"><?php echo $user_count; ?></div>
        </div>
        <div class="premium-card" style="padding: 2.5rem; border-top: 5px solid #8b5cf6;">
            <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem;">Live Products</div>
            <div style="font-size: 2.2rem; font-weight: 900; color: var(--text-main);"><?php echo $product_count; ?></div>
        </div>
        <div class="premium-card" style="padding: 2.5rem; border-top: 5px solid #10b981;">
            <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem;">Categories</div>
            <div style="font-size: 2.2rem; font-weight: 900; color: var(--text-main);"><?php echo $cat_count; ?></div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 4rem;">
        <!-- Recent Orders -->
        <div class="premium-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 2rem; background: var(--bg-main); border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.4rem;">Recent Transactions</h3>
                <a href="#" style="color: var(--primary); font-weight: 700; font-size: 0.85rem;">View All</a>
            </div>
            <div style="padding: 1rem;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--border-light);">
                            <th style="padding: 1.2rem;">ID</th>
                            <th style="padding: 1.2rem;">Customer</th>
                            <th style="padding: 1.2rem;">Amount</th>
                            <th style="padding: 1.2rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $recent_orders = $pdo->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.customer_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
                        foreach ($recent_orders as $ro):
                        ?>
                            <tr style="border-bottom: 1px solid var(--border-light);">
                                <td style="padding: 1.2rem;">#<?php echo $ro['id']; ?></td>
                                <td style="padding: 1.2rem; font-weight: 700;"><?php echo htmlspecialchars($ro['full_name']); ?></td>
                                <td style="padding: 1.2rem; font-weight: 800; color: var(--primary);"><?php echo formatCurrency($ro['total_amount']); ?></td>
                                <td style="padding: 1.2rem;">
                                    <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; padding: 4px 10px; border-radius: 50px; background: #eee;"><?php echo $ro['status']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <aside>
            <div class="premium-card" style="padding: 2.5rem; background: linear-gradient(135deg, #1e293b, #0f172a); color: white; border: none;">
                <h3 style="margin-bottom: 2rem;">Quick Actions</h3>
                <div style="display: grid; gap: 1rem;">
                    <a href="manage-users.php" class="btn glass" style="justify-content: flex-start; color: white; border: 1px solid rgba(255,255,255,0.1); width: 100%;">
                        <i class="fas fa-users-gear" style="margin-right: 15px;"></i> Manage Users
                    </a>
                    <a href="manage-products.php" class="btn glass" style="justify-content: flex-start; color: white; border: 1px solid rgba(255,255,255,0.1); width: 100%;">
                        <i class="fas fa-boxes-stacked" style="margin-right: 15px;"></i> Review Products
                    </a>
                    <a href="manage-categories.php" class="btn glass" style="justify-content: flex-start; color: white; border: 1px solid rgba(255,255,255,0.1); width: 100%;">
                        <i class="fas fa-layer-group" style="margin-right: 15px;"></i> Manage Categories
                    </a>
                    <button class="btn glass" style="justify-content: flex-start; color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); width: 100%;" onclick="alert('Maintenance mode enabled')">
                        <i class="fas fa-triangle-exclamation" style="margin-right: 15px;"></i> System Settings
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>