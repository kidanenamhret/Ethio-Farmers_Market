<?php
// public/order-history.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

checkRole(['customer', 'farmer', 'admin']);

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 4rem 3rem;" class="animate-fade-up">
    <div style="margin-bottom: 4rem;">
        <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Order <span style="color: var(--primary);">History</span></h1>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Track your fresh produce deliveries and harvest support.</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="premium-card" style="text-align: center; padding: 8rem 2rem; border-radius: 40px;">
            <div style="font-size: 6rem; color: var(--primary-glow); margin-bottom: 2rem;" class="floating">
                <i class="fas fa-box-archive"></i>
            </div>
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">No orders found</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 400px; margin: 0 auto 3rem;">You haven't made any purchases yet. Your journey to fresh Ethiopian produce starts at the market.</p>
            <a href="<?php echo url('public/products.php'); ?>" class="btn btn-primary" style="padding: 1.2rem 3.5rem; font-size: 1.1rem; border-radius: 20px;">
                Visit Marketplace
            </a>
        </div>
    <?php else: ?>
        <div class="premium-card" style="padding: 0; border-radius: 35px; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                    <thead>
                        <tr style="text-align: left; background: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-light);">
                            <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Order ID</th>
                            <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Placed Date</th>
                            <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Amount</th>
                            <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Method</th>
                            <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Status</th>
                            <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="font-weight: 800; color: var(--text-main);">#ORD-<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="font-weight: 600; color: var(--text-muted);"><?php echo date("M d, Y", strtotime($order['created_at'])); ?></div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="font-weight: 800; color: var(--primary); font-size: 1.1rem;"><?php echo formatCurrency($order['total_amount']); ?></div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem;">
                                        <?php if($order['payment_method'] == 'Telebirr'): ?>
                                            <i class="fas fa-mobile-screen-button" style="margin-right: 8px; color: var(--primary);"></i>
                                        <?php else: ?>
                                            <i class="fas fa-building-columns" style="margin-right: 8px; color: #854d0e;"></i>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($order['payment_method']); ?>
                                    </div>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <?php 
                                        $status_class = '';
                                        $icon = 'fa-clock';
                                        if ($order['status'] == 'pending') {
                                            $status_class = 'background: #fffbeb; color: #92400e;';
                                            $icon = 'fa-clock-rotate-left';
                                        } elseif ($order['status'] == 'delivered') {
                                            $status_class = 'background: #dcfce7; color: #166534;';
                                            $icon = 'fa-circle-check';
                                        } else {
                                            $status_class = 'background: #f1f5f9; color: #475569;';
                                            $icon = 'fa-truck-fast';
                                        }
                                    ?>
                                    <span style="<?php echo $status_class; ?> padding: 6px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 8px;">
                                        <i class="fas <?php echo $icon; ?>"></i> <?php echo $order['status']; ?>
                                    </span>
                                </td>
                                <td style="padding: 2rem 2.5rem;">
                                    <a href="#" class="btn glass" style="padding: 0.7rem 1.5rem; font-size: 0.85rem; font-weight: 800; border: 1.5px solid var(--border-light); border-radius: 12px;">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
