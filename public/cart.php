<?php
// public/cart.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle actions
if (isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] == 'remove') {
        unset($_SESSION['cart'][$id]);
    } elseif ($_GET['action'] == 'clear') {
        $_SESSION['cart'] = [];
    }
    header("Location: " . url('public/cart.php'));
    exit();
}

// Add to cart from product page
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
    $_SESSION['flash_message'] = "Added to cart!";
    $_SESSION['flash_type'] = 'success';
    header("Location: " . url('public/cart.php'));
    exit();
}

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    // Safely build query with placeholders
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders) AND status = 'active'");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    
    foreach ($products as $product) {
        $product['qty'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['price'] * $product['qty'];
        $total += $product['subtotal'];
        $cart_items[] = $product;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 4rem 3rem;" class="animate-fade-up">
    <div style="margin-bottom: 4rem;">
        <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Your <span style="color: var(--primary);">Cart</span></h1>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Review your harvest selection before checkout.</p>
    </div>

    <?php if (empty($cart_items)): ?>
        <div class="premium-card" style="text-align: center; padding: 8rem 2rem; border-radius: 40px;">
            <div style="font-size: 6rem; color: var(--primary-glow); margin-bottom: 2rem;" class="floating">
                <i class="fas fa-shopping-basket"></i>
            </div>
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Your basket is empty</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 400px; margin: 0 auto 3rem;">It seems you haven't added any fresh Ethiopian produce to your cart yet.</p>
            <a href="<?php echo url('public/products.php'); ?>" class="btn btn-primary" style="padding: 1.2rem 3.5rem; font-size: 1.1rem; border-radius: 20px;">
                Explore Marketplace
            </a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 4rem; align-items: start;">
            <!-- Cart Items List -->
            <div class="premium-card" style="padding: 0; border-radius: 35px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; background: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-light);">
                            <th style="padding: 1.5rem 2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Product</th>
                            <th style="padding: 1.5rem 2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Quantity</th>
                            <th style="padding: 1.5rem 2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; text-align: right;">Total</th>
                            <th style="padding: 1.5rem 2rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 2rem;">
                                    <div style="display: flex; align-items: center; gap: 20px;">
                                        <img src="<?php echo htmlspecialchars($item['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=150'); ?>" 
                                             style="width: 100px; height: 100px; border-radius: 20px; object-fit: cover; box-shadow: var(--shadow-soft);">
                                        <div>
                                            <h4 style="font-size: 1.2rem; margin-bottom: 5px;"><?php echo htmlspecialchars($item['name']); ?></h4>
                                            <div style="font-weight: 700; color: var(--primary);"><?php echo formatCurrency($item['price']); ?> <small style="color: var(--text-muted); font-weight: 600;">/ <?php echo htmlspecialchars($item['unit'] ?? 'pc'); ?></small></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 2rem;">
                                    <form method="POST" action="<?php echo url('public/update-cart.php'); ?>" style="display: flex; align-items: center; background: var(--bg-main); border-radius: 14px; padding: 4px; width: fit-content; border: 1px solid var(--border-light);">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="button" class="qty-control" onclick="const inp=this.nextElementSibling; if(inp.value > 1){inp.stepDown(); inp.dispatchEvent(new Event('change'))}" style="width: 35px; height: 35px; border: none; background: transparent; cursor: pointer; color: var(--text-muted); font-weight: 800;"><i class="fas fa-minus"></i></button>
                                        <input type="number" name="quantity" value="<?php echo $item['qty']; ?>" min="1" 
                                               style="width: 45px; text-align: center; border: none; background: transparent; font-weight: 800; font-family: inherit; font-size: 1rem; outline: none;"
                                               onchange="this.form.submit()">
                                        <button type="button" class="qty-control" onclick="this.previousElementSibling.stepUp(); this.previousElementSibling.dispatchEvent(new Event('change'))" style="width: 35px; height: 35px; border: none; background: transparent; cursor: pointer; color: var(--text-muted); font-weight: 800;"><i class="fas fa-plus"></i></button>
                                    </form>
                                </td>
                                <td style="padding: 2rem; text-align: right;">
                                    <div style="font-size: 1.3rem; font-weight: 800; color: var(--text-main);"><?php echo formatCurrency($item['subtotal']); ?></div>
                                </td>
                                <td style="padding: 2rem; text-align: center;">
                                    <a href="?action=remove&id=<?php echo $item['id']; ?>" style="color: var(--accent); background: rgba(211, 47, 47, 0.05); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: var(--transition);" 
                                       onclick="return confirm('Remove this item?')" onmouseover="this.style.background='rgba(211, 47, 47, 0.1)'" onmouseout="this.style.background='rgba(211, 47, 47, 0.05)'">
                                        <i class="fas fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="padding: 2.5rem; display: flex; justify-content: space-between; align-items: center; background: var(--bg-main);">
                    <a href="<?php echo url('public/products.php'); ?>" style="color: var(--primary); text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <a href="?action=clear" style="color: var(--text-muted); font-weight: 600; text-decoration: none; font-size: 0.9rem;" onclick="return confirm('Clear entire cart?')">
                        <i class="fas fa-broom"></i> Clear Entire Basket
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <aside style="position: sticky; top: 110px;">
                <div class="premium-card" style="padding: 3rem; border-radius: 35px;">
                    <h3 style="font-size: 1.8rem; letter-spacing: -0.5px; margin-bottom: 2.5rem;">Summary</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-weight: 600; color: var(--text-muted);">
                        <span>Subtotal</span>
                        <span style="color: var(--text-main);"><?php echo formatCurrency($total); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-weight: 600; color: var(--text-muted);">
                        <span>Delivery</span>
                        <span style="color: var(--primary); font-weight: 800;">FREE</span>
                    </div>
                    
                    <div style="padding: 1.5rem; background: var(--bg-main); border-radius: 20px; border: 1px dashed var(--border-light); margin: 2.5rem 0;">
                        <div style="display: flex; justify-content: space-between; font-size: 1.4rem; font-weight: 800; color: var(--text-main);">
                            <span>Total</span>
                            <span style="color: var(--primary);"><?php echo formatCurrency($total); ?></span>
                        </div>
                    </div>

                    <a href="<?php echo url('public/checkout.php'); ?>" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.1rem; border-radius: 20px; justify-content: center; box-shadow: 0 15px 30px -10px rgba(27, 94, 32, 0.4);">
                        Proceed to Checkout <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                    </a>
                    
                    <div style="margin-top: 2rem; text-align: center;">
                        <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">
                            <i class="fas fa-lock" style="color: #10b981; margin-right: 5px;"></i> Secure Checkout Guaranteed
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>