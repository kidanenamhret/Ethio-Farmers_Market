<?php
// public/farmer/dashboard.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

checkRole('farmer');

$farmer_id = $_SESSION['user_id'];

// Get Stats
$product_count = $pdo->prepare("SELECT COUNT(*) FROM products WHERE farmer_id = ?");
$product_count->execute([$farmer_id]);
$total_products = $product_count->fetchColumn();

$order_count = $pdo->prepare("SELECT COUNT(DISTINCT oi.order_id) 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              WHERE p.farmer_id = ?");
$order_count->execute([$farmer_id]);
$total_orders = $order_count->fetchColumn();

// Get Recent Products
$stmt = $pdo->prepare("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.farmer_id = ? ORDER BY p.created_at DESC LIMIT 5");
$stmt->execute([$farmer_id]);
$products = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 4rem; align-items: start;" class="animate-fade-up">
    <!-- Sidebar (Contextual for Farmer) -->
    <aside class="premium-card" style="padding: 3rem; position: sticky; top: 110px; border-radius: 35px;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <div style="width: 80px; height: 80px; background: var(--primary-glow); color: var(--primary); border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 1.5rem;" class="floating">
                <i class="fas fa-tractor"></i>
            </div>
            <h3 style="font-size: 1.5rem; letter-spacing: -0.5px;">Farmer Panel</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Manage Harvest</p>
        </div>

        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 0.8rem;">
                <a href="dashboard.php" style="display: flex; align-items: center; gap: 15px; padding: 1.1rem 1.4rem; border-radius: 18px; font-weight: 750; text-decoration: none; color: var(--white); background: var(--primary); box-shadow: 0 12px 24px -8px rgba(27, 94, 32, 0.4);">
                    <i class="fas fa-chart-line" style="width: 24px; font-size: 1.2rem;"></i> Dashboard
                </a>
            </li>
            <li style="margin-bottom: 0.8rem;">
                <a href="add_product.php" style="display: flex; align-items: center; gap: 15px; padding: 1.1rem 1.4rem; border-radius: 18px; font-weight: 750; text-decoration: none; color: var(--text-muted); transition: var(--transition);" onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-plus-circle" style="width: 24px; font-size: 1.2rem;"></i> Add New Item
                </a>
            </li>
            <li style="margin-bottom: 0.8rem;">
                <a href="orders.php" style="display: flex; align-items: center; gap: 15px; padding: 1.1rem 1.4rem; border-radius: 18px; font-weight: 750; text-decoration: none; color: var(--text-muted); transition: var(--transition);" onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-boxes-packing" style="width: 24px; font-size: 1.2rem;"></i> Incoming Orders
                </a>
            </li>
            <li style="margin-top: 2.5rem; border-top: 1px solid var(--border-light); padding-top: 2.5rem;">
                <a href="../index.php" style="display: flex; align-items: center; gap: 15px; padding: 1.1rem 1.4rem; border-radius: 18px; font-weight: 750; text-decoration: none; color: var(--primary); transition: var(--transition);" onmouseover="this.style.background='var(--primary-glow)'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-store" style="width: 24px; font-size: 1.2rem;"></i> Back to Shop
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
            <div>
                <h1 style="font-size: 3rem; letter-spacing: -1.5px; margin-bottom: 0.5rem;">Hello, <span style="color: var(--primary);"><?php echo explode(' ', $_SESSION['full_name'])[0]; ?>!</span></h1>
                <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Here is what's happening with your farm today.</p>
            </div>
            <a href="add_product.php" class="btn btn-primary" style="padding: 1rem 2.5rem; border-radius: 20px;">
                <i class="fas fa-plus"></i> List New Harvest
            </a>
        </div>

        <!-- Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5rem; margin-bottom: 4rem;">
            <div class="premium-card" style="padding: 2.5rem; border-bottom: 5px solid var(--primary);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background: var(--primary-glow); color: var(--primary); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #166534; background: #dcfce7; padding: 4px 12px; border-radius: 50px;">ACTIVE</span>
                </div>
                <h2 style="font-size: 2.5rem; font-weight: 800; line-height: 1; margin-bottom: 0.5rem;"><?php echo $total_products; ?></h2>
                <p style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Live Products</p>
            </div>

            <div class="premium-card" style="padding: 2.5rem; border-bottom: 5px solid var(--secondary);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background: rgba(251, 192, 45, 0.1); color: #854d0e; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #854d0e; background: #fef9c3; padding: 4px 12px; border-radius: 50px;">NEW SALES</span>
                </div>
                <h2 style="font-size: 2.5rem; font-weight: 800; line-height: 1; margin-bottom: 0.5rem;"><?php echo $total_orders; ?></h2>
                <p style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Total Orders</p>
            </div>

            <div class="premium-card" style="padding: 2.5rem; border-bottom: 5px solid var(--accent);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background: rgba(211, 47, 47, 0.1); color: var(--accent); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #991b1b; background: #fee2e2; padding: 4px 12px; border-radius: 50px;">EARNINGS</span>
                </div>
                <?php
                // Calculate actual earnings
                $earnings_stmt = $pdo->prepare("SELECT SUM(oi.quantity * oi.price_at_purchase) 
                                                FROM order_items oi 
                                                JOIN products p ON oi.product_id = p.id 
                                                WHERE p.farmer_id = ?");
                $earnings_stmt->execute([$farmer_id]);
                $total_earnings = $earnings_stmt->fetchColumn() ?: 0;
                ?>
                <h2 style="font-size: 2.5rem; font-weight: 800; line-height: 1; margin-bottom: 0.5rem;"><?php echo number_format($total_earnings, 2); ?></h2>
                <p style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Estimated (ETB)</p>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="premium-card" style="padding: 2.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
                <h3 style="font-size: 1.5rem; letter-spacing: -0.5px;">Recently Managed Produce</h3>
                <a href="#" style="color: var(--primary); font-weight: 700; font-size: 0.9rem;">View All Inventory <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 2px solid var(--border-light); color: var(--text-muted);">
                            <th style="padding: 1.2rem; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Product</th>
                            <th style="padding: 1.2rem; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Category</th>
                            <th style="padding: 1.2rem; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Price</th>
                            <th style="padding: 1.2rem; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Availability</th>
                            <th style="padding: 1.2rem; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr><td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-muted); font-weight: 600;">You haven't listed any produce yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 1.2rem;">
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <img src="<?php echo htmlspecialchars($p['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=100'); ?>" 
                                                 style="width: 50px; height: 50px; border-radius: 12px; object-fit: cover; box-shadow: var(--shadow-soft);">
                                            <div>
                                                <div style="font-weight: 700; color: var(--text-main);"><?php echo htmlspecialchars($p['name']); ?></div>
                                                <small style="color: var(--text-muted); font-weight: 600;">Added <?php echo date('M d', strtotime($p['created_at'])); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 1.2rem;">
                                        <span style="background: var(--bg-main); color: var(--text-muted); padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700;"><?php echo htmlspecialchars($p['cat_name']); ?></span>
                                    </td>
                                    <td style="padding: 1.2rem;">
                                        <div style="font-weight: 800; color: var(--primary);"><?php echo formatCurrency($p['price']); ?></div>
                                        <small style="color: var(--text-muted); font-weight: 600;">per <?php echo htmlspecialchars($p['unit']); ?></small>
                                    </td>
                                    <td style="padding: 1.2rem;">
                                        <div style="font-weight: 700; color: var(--text-main);"><?php echo $p['stock_quantity']; ?> left</div>
                                        <div style="width: 100px; height: 6px; background: #e2e8f0; border-radius: 10px; margin-top: 6px; overflow: hidden;">
                                            <div style="width: <?php echo min(100, $p['stock_quantity']); ?>%; height: 100%; background: var(--primary); border-radius: 10px;"></div>
                                        </div>
                                    </td>
                                    <td style="padding: 1.2rem;">
                                        <div style="display: flex; gap: 10px;">
                                            <a href="edit-product.php?id=<?php echo $p['id']; ?>" class="btn glass" style="width: 40px; height: 40px; padding: 0; border-radius: 12px; border: 1.5px solid var(--border-light); color: var(--primary); display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>
                                            <a href="delete-product.php?id=<?php echo $p['id']; ?>" class="btn glass" 
                                               style="width: 40px; height: 40px; padding: 0; border-radius: 12px; border: 1.5px solid var(--border-light); color: var(--accent); display: flex; align-items: center; justify-content: center; text-decoration: none;"
                                               onclick="return confirm('Are you sure you want to delete this harvest listing?')">
                                                <i class="fas fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
