<?php
// public/index.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Fetch categories with product counts
$stmt = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id AND p.status = 'active') as product_count FROM categories c LIMIT 5");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section with Visual Hierarchy -->
<section style="margin-bottom: 6rem; padding: 6rem 3rem; background: linear-gradient(135deg, #0a1a0d 0%, #1b5e20 100%); border-radius: 40px; position: relative; overflow: hidden; color: white;">
    <div style="position: absolute; right: -50px; top: -50px; width: 400px; height: 400px; background: rgba(255,255,255,0.03); border-radius: 50%; blur: 80px;"></div>
    
    <div style="max-width: 900px; position: relative; z-index: 2;">
        <span style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 8px 20px; border-radius: 50px; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; display: inline-block; margin-bottom: 2rem;">Harvesting Excellence</span>
        <h1 style="font-size: clamp(3.5rem, 8vw, 5.5rem); font-weight: 850; line-height: 1; margin-bottom: 1.5rem; letter-spacing: -3px;">Ethio <span style="color: var(--secondary);">Farmers</span></h1>
        <p style="font-size: 1.5rem; opacity: 0.85; margin-bottom: 4rem; font-weight: 500; letter-spacing: -0.5px;">Explore Fresh Produce from Ethiopian Farmers directly to your kitchen.</p>
        
        <!-- AJAX Search Bar (Required) -->
        <div style="max-width: 700px; position: relative;">
            <form action="products.php" method="GET" class="glass" style="padding: 10px; border-radius: 24px; display: flex; align-items: center; gap: 15px; background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.1);">
                <i class="fas fa-magnifying-glass" style="margin-left: 20px; color: var(--secondary); font-size: 1.3rem;"></i>
                <input type="text" id="live-search" name="search" placeholder="Find fresh teff, coffee, vegetables..." 
                       style="flex: 1; border: none; background: transparent; padding: 1.2rem 0; font-size: 1.15rem; outline: none; font-family: inherit; color: white; font-weight: 600;" 
                       autocomplete="off">
                <button type="submit" class="btn btn-secondary" style="border-radius: 18px; padding: 1rem 2.5rem; font-weight: 800;">Search</button>
            </form>
            <div id="search-results" class="search-results" style="top: 100%; width: 100%; border-radius: 20px; margin-top: 10px; overflow: hidden; background: white; color: var(--text-main); box-shadow: var(--shadow-premium);"></div>
        </div>
    </div>
</section>

<!-- Dynamic Category Sections with Sample Products -->
<section style="margin-bottom: 10rem;">
    <div style="margin-bottom: 5rem;">
        <h2 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem; font-weight: 850;">The <span style="color: var(--primary);">Harvest</span></h2>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.2rem;">Discover the freshest produce directly from Ethiopia's highlands.</p>
    </div>

    <?php 
    $cat_icons = [
        'Vegetables' => '🥬',
        'Fruits' => '🍎',
        'Dairy' => '🥛',
        'Grains' => '🌾',
        'Spices' => '🌶️'
    ];
    
    foreach ($categories as $cat): 
        $icon = $cat_icons[$cat['name']] ?? '🍃';
    ?>
        <div style="margin-bottom: 8rem;">
            <div style="display: flex; align-items: center; gap: 25px; margin-bottom: 3.5rem;">
                <div style="width: 70px; height: 70px; background: var(--bg-main); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; box-shadow: var(--shadow-soft);">
                    <?php echo $icon; ?>
                </div>
                <div>
                    <h3 style="font-size: 2.2rem; letter-spacing: -1.2px; font-weight: 850; line-height: 1; margin-bottom: 5px;"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <span style="font-size: 0.85rem; color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px;"><?php echo $cat['product_count']; ?> items available</span>
                </div>
                <div style="flex: 1; height: 1px; background: linear-gradient(to right, var(--border-light), transparent); margin-left: 20px;"></div>
                <a href="products.php?category=<?php echo $cat['id']; ?>" class="btn glass" style="padding: 0.8rem 2rem; font-weight: 800; border-radius: 15px; font-size: 0.9rem;">Browse All</a>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2.5rem;">
                <?php 
                $p_stmt = $pdo->prepare("SELECT p.*, u.full_name as farmer_name FROM products p JOIN users u ON p.farmer_id = u.id WHERE p.category_id = ? AND p.status = 'active' ORDER BY p.id DESC LIMIT 4");
                $p_stmt->execute([$cat['id']]);
                $samples = $p_stmt->fetchAll();
                
                foreach ($samples as $product):
                ?>
                    <div class="premium-card" style="padding: 0; border-radius: 40px; overflow: hidden; border: 1.5px solid var(--border-light);">
                        <div style="position: relative; height: 260px; overflow: hidden;">
                            <img src="<?php echo htmlspecialchars($product['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600'); ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover; transition: var(--transition);"
                                 onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <div style="position: absolute; top: 20px; left: 20px; background: var(--white); padding: 8px 16px; border-radius: 14px; font-weight: 800; font-size: 0.75rem; box-shadow: var(--shadow-soft);">
                                <i class="fas fa-tractor" style="color: var(--primary); margin-right: 8px;"></i> Direct
                            </div>
                        </div>
                        <div style="padding: 2.5rem;">
                            <h4 style="font-size: 1.5rem; font-weight: 850; margin-bottom: 1.5rem; letter-spacing: -0.5px;"><?php echo htmlspecialchars($product['name']); ?></h4>
                            
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem;">
                                <div style="flex: 1;">
                                    <div style="font-size: 1.8rem; font-weight: 900; color: var(--primary); line-height: 1; margin-bottom: 5px;">
                                        <?php echo formatCurrency($product['price']); ?>
                                    </div>
                                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">per <?php echo $product['unit']; ?></span>
                                </div>
                                <div style="text-align: right; flex: 1;">
                                    <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700; margin-bottom: 5px;">
                                        <i class="fas fa-user-circle" style="color: var(--primary-light);"></i> <?php echo explode(' ', $product['farmer_name'])[0]; ?>
                                    </div>
                                    <?php if($product['stock_quantity'] < 20): ?>
                                        <div style="background: #fff1f2; color: #e11d48; padding: 4px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; display: inline-block;">
                                            ONLY <?php echo $product['stock_quantity']; ?> LEFT
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button class="btn btn-primary add-to-cart" data-id="<?php echo $product['id']; ?>" style="width: 100%; border-radius: 18px; padding: 1.2rem; font-weight: 800;">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<!-- Join as Farmer Call-to-Action -->
<section style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; padding: 8rem 4rem; border-radius: 50px; position: relative; overflow: hidden; margin-bottom: 4rem; box-shadow: var(--shadow-premium);">
    <div style="position: absolute; right: -50px; bottom: -50px; font-size: 25rem; color: rgba(255,255,255,0.03); transform: rotate(-15deg);">
        <i class="fas fa-tractor"></i>
    </div>
    
    <div style="max-width: 800px; position: relative; z-index: 2;">
        <h2 style="font-size: clamp(2.5rem, 6vw, 4rem); letter-spacing: -2.5px; line-height: 1.1; margin-bottom: 2.5rem; font-weight: 850;">
            Empower your farm. <br><span style="color: var(--primary-light);">Join the market.</span>
        </h2>
        <p style="font-size: 1.3rem; opacity: 0.9; line-height: 1.6; margin-bottom: 4rem; font-weight: 600; max-width: 650px;">
            List your fresh Ethiopian produce and connect with thousands of local buyers directly. No middlemen, just fair trade.
        </p>
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
            <a href="register.php?role=farmer" class="btn btn-primary" style="padding: 1.5rem 3.5rem; font-size: 1.1rem; border-radius: 22px; box-shadow: 0 15px 30px rgba(76, 175, 80, 0.3);">
                Start Selling Now
            </a>
            <a href="#" class="btn glass" style="padding: 1.5rem 3.5rem; font-size: 1.1rem; border-radius: 22px; color: white; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05);">
                Learn More
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
    // AJAX Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            fetch('<?php echo url("ajax/add-to-cart.php"); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    const cartCountBadge = document.querySelector('.cart-count');
                    if (cartCountBadge) {
                        cartCountBadge.innerText = data.cart_count;
                        cartCountBadge.style.display = 'block';
                    }
                } else {
                    alert(data.message || 'Please login first.');
                }
            });
        });
    });
</script>