<?php
// public/checkout.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

checkRole(['customer', 'farmer', 'admin']);

if (empty($_SESSION['cart'])) {
    header("Location: " . url('public/products.php'));
    exit();
}

$error = '';
$success = false;

// Calculate total using parameterized query
$total = 0;
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products_in_cart = $stmt->fetchAll();
foreach ($products_in_cart as $p) {
    $total += $p['price'] * $_SESSION['cart'][$p['id']];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = sanitize($_POST['address']);
    $payment = sanitize($_POST['payment_method']);
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // 1. Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (customer_id, total_amount, shipping_address, payment_method) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $total, $address, $payment]);
        $order_id = $pdo->lastInsertId();

        // 2. Add Items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
        $update_stock = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");

        foreach ($products_in_cart as $p) {
            $qty = $_SESSION['cart'][$p['id']];
            $stmt->execute([$order_id, $p['id'], $qty, $p['price']]);
            $update_stock->execute([$qty, $p['id']]);
        }

        $pdo->commit();
        $_SESSION['cart'] = [];
        $success = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Checkout failed: " . $e->getMessage();
    }
}
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 4rem 3rem;" class="animate-fade-up">
    <?php if ($success): ?>
        <div class="premium-card" style="text-align: center; padding: 8rem 2rem; max-width: 800px; margin: 0 auto; border-radius: 40px;">
            <div style="width: 100px; height: 100px; background: var(--primary-glow); color: var(--primary); border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 2.5rem;" class="floating">
                <i class="fas fa-circle-check"></i>
            </div>
            <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 1.5rem;">Order Placed <span style="color: var(--primary);">Successfully!</span></h1>
            <p style="color: var(--text-muted); font-size: 1.25rem; line-height: 1.8; margin-bottom: 3.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Thank you for your order. We've notified the farmers, and your fresh produce is being prepared for delivery.
            </p>
            <div style="display: flex; gap: 1.5rem; justify-content: center;">
                <a href="<?php echo url('public/order-history.php'); ?>" class="btn btn-primary" style="padding: 1.2rem 3rem; font-size: 1.1rem; border-radius: 20px;">
                    Track My Order <i class="fas fa-location-arrow" style="margin-left: 10px;"></i>
                </a>
                <a href="<?php echo url('public/products.php'); ?>" class="btn glass" style="padding: 1.2rem 3rem; font-size: 1.1rem; border-radius: 20px; border: 2px solid var(--border-light);">
                    Back to Market
                </a>
            </div>
        </div>
    <?php else: ?>
        <div style="margin-bottom: 4rem;">
            <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Secure <span style="color: var(--primary);">Checkout</span></h1>
            <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Complete your details to finalize your purchase.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom: 3rem;">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" style="display: grid; grid-template-columns: 1fr 450px; gap: 4rem; align-items: start;">
            <div style="display: flex; flex-direction: column; gap: 3rem;">
                <!-- Step 1: Delivery -->
                <div class="premium-card" style="padding: 3.5rem; border-radius: 35px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 2.5rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800;">1</div>
                        <h3 style="font-size: 1.8rem; letter-spacing: -0.5px;">Delivery Information</h3>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; color: var(--text-muted); display: block; margin-bottom: 1rem;">Shipping Address</label>
                        <textarea name="address" class="form-control" rows="4" placeholder="House no, Street, Neighborhood, City..." required style="border-radius: 20px; padding: 1.5rem; font-size: 1.1rem;"></textarea>
                    </div>
                </div>

                <!-- Step 2: Payment -->
                <div class="premium-card" style="padding: 3.5rem; border-radius: 35px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 2.5rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800;">2</div>
                        <h3 style="font-size: 1.8rem; letter-spacing: -0.5px;">Payment Method</h3>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                        <label class="glass" style="padding: 2rem; cursor: pointer; border-radius: 24px; border: 2px solid var(--border-light); transition: var(--transition); position: relative;">
                            <input type="radio" name="payment_method" value="Telebirr" checked style="position: absolute; right: 20px; top: 20px; accent-color: var(--primary);">
                            <div style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"><i class="fas fa-mobile-screen-button"></i></div>
                            <div style="font-weight: 800; font-size: 1.1rem;">Telebirr</div>
                            <small style="color: var(--text-muted); font-weight: 600;">Mobile Wallet</small>
                        </label>
                        
                        <label class="glass" style="padding: 2rem; cursor: pointer; border-radius: 24px; border: 2px solid var(--border-light); transition: var(--transition); position: relative;">
                            <input type="radio" name="payment_method" value="CBE Birr" style="position: absolute; right: 20px; top: 20px; accent-color: var(--primary);">
                            <div style="font-size: 2rem; color: #854d0e; margin-bottom: 1rem;"><i class="fas fa-building-columns"></i></div>
                            <div style="font-weight: 800; font-size: 1.1rem;">CBE Birr</div>
                            <small style="color: var(--text-muted); font-weight: 600;">Bank Transfer</small>
                        </label>

                        <label class="glass" style="padding: 2rem; cursor: pointer; border-radius: 24px; border: 2px solid var(--border-light); transition: var(--transition); grid-column: span 2; position: relative;">
                            <input type="radio" name="payment_method" value="Cash on Delivery" style="position: absolute; right: 20px; top: 20px; accent-color: var(--primary);">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div style="font-size: 2rem; color: var(--secondary);"><i class="fas fa-money-bill-wave"></i></div>
                                <div>
                                    <div style="font-weight: 800; font-size: 1.1rem;">Cash on Delivery</div>
                                    <small style="color: var(--text-muted); font-weight: 600;">Pay when you receive</small>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Order Review -->
            <aside style="position: sticky; top: 110px;">
                <div class="premium-card" style="padding: 3rem; border-radius: 35px;">
                    <h3 style="font-size: 1.8rem; letter-spacing: -0.5px; margin-bottom: 2.5rem;">Review Order</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1.2rem; margin-bottom: 2.5rem;">
                        <?php foreach ($products_in_cart as $p): ?>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-weight: 700; font-size: 0.95rem;"><?php echo htmlspecialchars($p['name']); ?></div>
                                    <small style="color: var(--text-muted); font-weight: 600;">Qty: <?php echo $_SESSION['cart'][$p['id']]; ?> <?php echo $p['unit']; ?></small>
                                </div>
                                <div style="font-weight: 800; color: var(--text-main);"><?php echo formatCurrency($p['price'] * $_SESSION['cart'][$p['id']]); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 2rem;">
                    
                    <div style="padding: 1.5rem; background: var(--bg-main); border-radius: 20px; border: 1px dashed var(--border-light); margin-bottom: 2.5rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 1.6rem; font-weight: 800; color: var(--text-main);">
                            <span>Total</span>
                            <span style="color: var(--primary);"><?php echo formatCurrency($total); ?></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.1rem; border-radius: 20px; justify-content: center; box-shadow: 0 15px 30px -10px rgba(27, 94, 32, 0.4);">
                        Confirm & Place Order <i class="fas fa-shield-check" style="margin-left: 10px;"></i>
                    </button>
                    
                    <div style="margin-top: 2rem; text-align: center; display: flex; flex-direction: column; gap: 10px;">
                        <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">
                            <i class="fas fa-lock-keyhole" style="color: #10b981; margin-right: 5px;"></i> End-to-end encrypted
                        </p>
                        <p style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4;">
                            By placing an order, you agree to our terms and conditions.
                        </p>
                    </div>
                </div>
            </aside>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>