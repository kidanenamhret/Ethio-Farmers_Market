<?php
// public/farmer/orders.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

checkRole('farmer');

$farmer_id = $_SESSION['user_id'];

// Fetch orders that contain this farmer's products
$stmt = $pdo->prepare("SELECT o.*, u.full_name as customer_name, u.email as customer_email, 
                              p.name as product_name, p.image_url, oi.quantity, oi.price_at_purchase 
                       FROM orders o 
                       JOIN users u ON o.customer_id = u.id 
                       JOIN order_items oi ON o.id = oi.order_id 
                       JOIN products p ON oi.product_id = p.id 
                       WHERE p.farmer_id = ? 
                       ORDER BY o.created_at DESC");
$stmt->execute([$farmer_id]);
$sales = $stmt->fetchAll();

// Calculate some stats
$total_revenue = 0;
$pending_orders = 0;
foreach ($sales as $s) {
    $total_revenue += ($s['quantity'] * $s['price_at_purchase']);
    if ($s['status'] === 'pending') $pending_orders++;
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <div>
            <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Incoming <span style="color: var(--primary);">Orders</span></h1>
            <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Track and manage your sales performance</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button class="btn glass" onclick="window.print()" style="padding: 12px 20px; border-radius: 14px; border: 1px solid var(--border-light); font-weight: 700;">
                <i class="fas fa-print"></i> Export
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 4rem;">
        <div class="premium-card" style="padding: 2.5rem; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; border: none; position: relative; overflow: hidden;">
            <i class="fas fa-money-bill-trend-up" style="position: absolute; right: -10px; bottom: -10px; font-size: 6rem; opacity: 0.15; transform: rotate(-15deg);"></i>
            <div style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.8; margin-bottom: 1rem;">Total Revenue</div>
            <div style="font-size: 2.5rem; font-weight: 900;"><?php echo formatCurrency($total_revenue); ?></div>
        </div>

        <div class="premium-card" style="padding: 2.5rem; background: white; border: 1px solid var(--border-light);">
            <div style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); margin-bottom: 1rem;">Active Orders</div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="font-size: 2.5rem; font-weight: 900; color: var(--text-main);"><?php echo $pending_orders; ?></div>
                <span style="background: #fff7ed; color: #c2410c; padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 800;">PENDING</span>
            </div>
        </div>

        <div class="premium-card" style="padding: 2.5rem; background: white; border: 1px solid var(--border-light);">
            <div style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); margin-bottom: 1rem;">Total Items Sold</div>
            <div style="font-size: 2.5rem; font-weight: 900; color: var(--text-main);"><?php echo count($sales); ?></div>
        </div>
    </div>

    <!-- Sales Table / List -->
    <div class="premium-card" style="padding: 0; border-radius: 35px; overflow: hidden;">
        <div style="padding: 2rem 2.5rem; border-bottom: 1px solid var(--border-light); background: var(--bg-main); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.4rem; letter-spacing: -0.5px;">Order History</h3>
            <div style="display: flex; gap: 10px;">
                <div class="glass" style="padding: 8px 15px; border-radius: 12px; font-size: 0.85rem; font-weight: 700; border: 1px solid var(--border-light);">
                    Filter: All Time
                </div>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                <thead>
                    <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--border-light);">
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Order Info</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Customer</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Product</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Revenue</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Status</th>
                        <th style="padding: 1.5rem 2.5rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="6" style="padding: 8rem 2rem; text-align: center;">
                                <div style="font-size: 4rem; color: var(--bg-main); margin-bottom: 1.5rem;"><i class="fas fa-box-open"></i></div>
                                <h4 style="color: var(--text-muted); font-size: 1.2rem;">No orders received yet.</h4>
                                <p style="color: var(--text-muted); font-size: 0.9rem;">Keep your products updated to attract buyers!</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sales as $sale): 
                            $status_color = match($sale['status']) {
                                'pending' => '#f59e0b',
                                'processing' => '#3b82f6',
                                'shipped' => '#8b5cf6',
                                'delivered' => '#10b981',
                                'cancelled' => '#ef4444',
                                default => 'var(--text-muted)'
                            };
                        ?>
                            <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="font-weight: 800; color: var(--text-main); margin-bottom: 5px;">#ORD-<?php echo str_pad($sale['id'], 5, '0', STR_PAD_LEFT); ?></div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                                        <i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($sale['created_at'])); ?>
                                    </div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 35px; height: 35px; background: var(--bg-main); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; color: var(--primary);">
                                            <?php echo strtoupper(substr($sale['customer_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--text-main);"><?php echo htmlspecialchars($sale['customer_name']); ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;"><?php echo htmlspecialchars($sale['customer_email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="<?php echo htmlspecialchars($sale['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=100'); ?>" 
                                             style="width: 50px; height: 50px; border-radius: 12px; object-fit: cover;">
                                        <div>
                                            <div style="font-weight: 700; color: var(--text-main);"><?php echo htmlspecialchars($sale['product_name']); ?></div>
                                            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Qty: <?php echo $sale['quantity']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="font-size: 1.1rem; font-weight: 900; color: var(--primary);"><?php echo formatCurrency($sale['quantity'] * $sale['price_at_purchase']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;"><?php echo $sale['payment_method']; ?></div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 50px; background: <?php echo $status_color; ?>15; color: <?php echo $status_color; ?>; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                                        <div style="width: 6px; height: 6px; border-radius: 50%; background: <?php echo $status_color; ?>;"></div>
                                        <?php echo $sale['status']; ?>
                                    </div>
                                </td>
                                <td style="padding: 2rem 2.5rem; text-align: right;">
                                    <form method="POST" action="update-order-status.php" style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <input type="hidden" name="order_id" value="<?php echo $sale['id']; ?>">
                                        <select name="status" class="glass" style="padding: 6px 12px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; border: 1px solid var(--border-light); outline: none; cursor: pointer;">
                                            <option value="pending" <?php echo $sale['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="processing" <?php echo $sale['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="shipped" <?php echo $sale['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="delivered" <?php echo $sale['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="cancelled" <?php echo $sale['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary" style="padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; width: auto;">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

