<?php
// public/product-details.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name, u.full_name as farmer_name 
                       FROM products p 
                       JOIN categories c ON p.category_id = c.id 
                       JOIN users u ON p.farmer_id = u.id 
                       WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    redirect(url('public/products.php'), "Product not found.", "error");
}
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;" class="animate-fade-up">
    <!-- Breadcrumb -->
    <a href="<?php echo url('public/products.php'); ?>" style="color: var(--text-muted); font-weight: 700; text-decoration: none; margin-bottom: 3rem; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">
        <i class="fas fa-arrow-left"></i> Back to Marketplace
    </a>

    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 6rem; align-items: start; margin-top: 2rem;">
        <!-- Left: Image Showcase -->
        <div style="position: sticky; top: 110px;">
            <div class="premium-card" style="padding: 0; overflow: hidden; border-radius: 40px; box-shadow: var(--shadow-premium);">
                <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=1200'); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     style="width: 100%; height: 600px; object-fit: cover;">
            </div>
        </div>
        
        <!-- Right: Content -->
        <div>
            <div style="margin-bottom: 2.5rem;">
                <span style="background: var(--primary-glow); color: var(--primary); padding: 6px 16px; border-radius: 50px; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; display: inline-block; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </span>
                <h1 style="font-size: 4rem; letter-spacing: -2px; line-height: 1.1; font-weight: 800; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>
                <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
                    <div>
                        <span style="font-size: 3rem; font-weight: 800; color: var(--primary);"><?php echo formatCurrency($product['price']); ?></span>
                        <span style="color: var(--text-muted); font-weight: 700; font-size: 1.1rem;"> / <?php echo htmlspecialchars($product['unit']); ?></span>
                    </div>
                </div>
                <div style="margin-top: 2rem;">
                    <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1rem; font-weight: 800;">About this Product</h3>
                    <div style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; font-weight: 500;">
                        <?php echo $product['description']; ?>
                    </div>
                </div>
            </div>

            <div class="premium-card" style="padding: 2.5rem; border-radius: 30px; margin-bottom: 3rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <small style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; font-size: 0.7rem; display: block; margin-bottom: 8px;">Seller Information</small>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                <?php echo strtoupper(substr($product['farmer_name'], 0, 1)); ?>
                            </div>
                            <strong style="font-size: 1.1rem;"><?php echo htmlspecialchars($product['farmer_name']); ?></strong>
                        </div>
                    </div>
                    <div>
                        <small style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; font-size: 0.7rem; display: block; margin-bottom: 8px;">Stock Status</small>
                        <div style="font-weight: 700; font-size: 1.1rem; color: <?php echo $product['stock_quantity'] > 0 ? 'var(--text-main)' : 'var(--accent)'; ?>">
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <i class="fas fa-check-circle" style="color: #10b981;"></i> <?php echo $product['stock_quantity']; ?> <?php echo htmlspecialchars($product['unit']); ?> Available
                            <?php else: ?>
                                <i class="fas fa-times-circle"></i> Out of Stock
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light); display: flex; gap: 2rem;">
                    <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;"><i class="fas fa-truck-fast" style="color: var(--primary); margin-right: 8px;"></i> Next-day Delivery</span>
                </div>
            </div>

            <?php if ($product['stock_quantity'] > 0): ?>
                <form method="POST" action="<?php echo url('public/cart.php'); ?>" style="display: flex; gap: 1.5rem; align-items: center;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div style="display: flex; align-items: center; background: var(--white); border: 2px solid var(--border-light); border-radius: 20px; padding: 0.5rem 0.8rem; box-shadow: var(--shadow-soft);">
                        <button type="button" class="qty-btn" data-action="minus" style="width: 45px; height: 45px; border: none; background: var(--bg-main); border-radius: 12px; cursor: pointer; color: var(--text-main); font-weight: 800; font-size: 1.2rem;">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                               style="width: 70px; text-align: center; border: none; font-weight: 800; font-size: 1.2rem; background: transparent; outline: none;">
                        <button type="button" class="qty-btn" data-action="plus" style="width: 45px; height: 45px; border: none; background: var(--bg-main); border-radius: 12px; cursor: pointer; color: var(--text-main); font-weight: 800; font-size: 1.2rem;">+</button>
                    </div>
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 1.2rem; font-size: 1.1rem; border-radius: 20px; box-shadow: 0 15px 30px -10px rgba(27, 94, 32, 0.4);">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products -->
    <div style="margin-top: 8rem;">
        <h2 style="font-size: 2.5rem; letter-spacing: -1.5px; margin-bottom: 3rem;">Similar <span style="color: var(--primary);">Fresh Picks</span></h2>
        
        <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2.5rem; padding: 0;">
            <?php 
                $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? AND p.status = 'active' LIMIT 4");
                $stmt->execute([$product['category_id'], $product['id']]);
                $related = $stmt->fetchAll();
                foreach ($related as $rp):
            ?>
                <div class="premium-card" style="padding: 0; overflow: hidden; border-radius: 35px;">
                    <div style="position: relative; overflow: hidden; height: 260px;">
                        <img src="<?php echo htmlspecialchars($rp['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600'); ?>" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: var(--transition);"
                             onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <div style="padding: 2.2rem;">
                        <h3 style="font-size: 1.4rem; margin-bottom: 0.8rem;"><?php echo htmlspecialchars($rp['name']); ?></h3>
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div>
                                <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary);"><?php echo formatCurrency($rp['price']); ?></div>
                                <small style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem;">Per <?php echo htmlspecialchars($rp['unit']); ?></small>
                            </div>
                            <a href="<?php echo url('public/product-details.php?id=' . $rp['id']); ?>" class="btn glass" style="width: 50px; height: 50px; padding: 0; border-radius: 14px; border: 1.5px solid var(--border-light);"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = document.getElementById('quantity');
            let val = parseInt(input.value);
            if (this.dataset.action === 'plus') {
                if (val < <?php echo $product['stock_quantity']; ?>) input.value = val + 1;
            } else {
                if (val > 1) input.value = val - 1;
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
