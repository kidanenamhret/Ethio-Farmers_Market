<?php
// public/products.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search_query = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sort_order = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Base query
$query = "SELECT p.*, c.name as category_name, u.full_name as farmer_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          JOIN users u ON p.farmer_id = u.id 
          WHERE p.status = 'active'";

$params = [];

if ($category_filter) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_filter;
}

if ($search_query) {
    $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

// Sorting logic
switch ($sort_order) {
    case 'price_low':
        $query .= " ORDER BY p.price ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY p.price DESC";
        break;
    case 'newest':
    default:
        $query .= " ORDER BY p.created_at DESC";
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch all categories for the filter sidebar
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 4rem; align-items: start;" class="animate-fade-up">
    <!-- Filters Sidebar -->
    <aside class="premium-card" style="padding: 3rem; position: sticky; top: 110px; border-radius: 35px;">
        <h3 style="margin-bottom: 3rem; font-size: 1.6rem; letter-spacing: -0.8px; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-sliders" style="color: var(--primary);"></i> Filters
        </h3>
        
        <div style="margin-bottom: 3.5rem;">
            <h4 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1.5rem; font-weight: 800;">Search Marketplace</h4>
            <form method="GET" action="<?php echo url('public/products.php'); ?>" style="position: relative;">
                <?php if ($category_filter): ?>
                    <input type="hidden" name="category" value="<?php echo $category_filter; ?>">
                <?php endif; ?>
                <?php if ($sort_order !== 'newest'): ?>
                    <input type="hidden" name="sort" value="<?php echo $sort_order; ?>">
                <?php endif; ?>
                <i class="fas fa-search" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="search" placeholder="Find fresh produce..." value="<?php echo htmlspecialchars($search_query); ?>"
                       style="width: 100%; padding: 1rem 1rem 1rem 3rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 600; outline: none; transition: var(--transition);"
                       onfocus="this.style.borderColor='var(--primary-glow)'; this.style.background='white'">
            </form>
        </div>

        <div style="margin-bottom: 3.5rem;">
            <h4 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 1.5rem; font-weight: 800;">By Category</h4>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 0.8rem;">
                    <a href="<?php echo url('public/products.php' . ($search_query ? '?search='.urlencode($search_query) : '')); ?>" class="<?php echo !$category_filter ? 'active' : ''; ?>" style="display: flex; justify-content: space-between; align-items: center; padding: 1.1rem 1.4rem; border-radius: 18px; font-weight: 750; text-decoration: none; color: <?php echo !$category_filter ? 'var(--white)' : 'var(--text-muted)'; ?>; background: <?php echo !$category_filter ? 'var(--primary)' : 'transparent'; ?>; transition: var(--transition); box-shadow: <?php echo !$category_filter ? '0 12px 24px -8px rgba(27, 94, 32, 0.4)' : 'none'; ?>;">
                        <span>All Products</span>
                        <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                    </a>
                </li>
                <?php foreach ($categories as $cat): ?>
                    <li style="margin-bottom: 0.8rem;">
                        <a href="<?php echo url('public/products.php?category=' . $cat['id'] . ($search_query ? '&search='.urlencode($search_query) : '')); ?>" 
                           style="display: flex; justify-content: space-between; align-items: center; padding: 1.1rem 1.4rem; border-radius: 18px; font-weight: 750; text-decoration: none; color: <?php echo $category_filter == $cat['id'] ? 'var(--white)' : 'var(--text-muted)'; ?>; background: <?php echo $category_filter == $cat['id'] ? 'var(--primary)' : 'transparent'; ?>; transition: var(--transition); box-shadow: <?php echo $category_filter == $cat['id'] ? '0 12px 24px -8px rgba(27, 94, 32, 0.4)' : 'none'; ?>;"
                           onmouseover="if(this.style.background==='transparent')this.style.background='var(--bg-main)'" onmouseout="if(this.style.background==='var(--bg-main)')this.style.background='transparent'">
                            <span><?php echo htmlspecialchars($cat['name']); ?></span>
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div style="padding: 2.5rem; border-radius: 28px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; position: relative; overflow: hidden; box-shadow: 0 15px 35px -10px rgba(27, 94, 32, 0.4);">
            <i class="fas fa-seedling" style="position: absolute; right: -15px; bottom: -15px; font-size: 6rem; opacity: 0.15; transform: rotate(-15deg);"></i>
            <h4 style="margin-bottom: 0.8rem; font-weight: 850; font-size: 1.2rem; letter-spacing: -0.5px;">100% Organic</h4>
            <p style="font-size: 0.9rem; opacity: 0.9; line-height: 1.6; font-weight: 500;">Directly sourced from Ethiopia's richest highlands.</p>
        </div>
    </aside>

    <!-- Products Grid -->
    <main>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3.5rem;">
            <div>
                <h1 style="font-size: 3rem; letter-spacing: -1.5px; margin-bottom: 0.5rem;">The <span style="color: var(--primary);">Marketplace</span></h1>
                <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Discover <?php echo count($products); ?> fresh items from local farms</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <div class="glass" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; border-radius: 16px; border: 1px solid var(--border-light);">
                    <i class="fas fa-arrow-down-short-wide" style="color: var(--primary);"></i>
                    <select onchange="location.href='<?php echo url('public/products.php?'.($category_filter?'category='.$category_filter.'&':'').($search_query?'search='.urlencode($search_query).'&':'')); ?>sort=' + this.value" 
                            style="border: none; background: transparent; font-family: inherit; font-weight: 700; color: var(--text-main); outline: none; cursor: pointer;">
                        <option value="newest" <?php echo $sort_order == 'newest' ? 'selected' : ''; ?>>Sort by: Newest</option>
                        <option value="price_low" <?php echo $sort_order == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo $sort_order == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="premium-card" style="text-align: center; padding: 8rem 2rem;">
                <div style="font-size: 5rem; color: var(--text-muted); margin-bottom: 2rem;" class="floating">
                    <i class="fas fa-magnifying-glass-chart"></i>
                </div>
                <h2 style="font-size: 2rem; margin-bottom: 1rem;">No products found</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 400px; margin: 0 auto 2.5rem;">We couldn't find any produce matching your current filters.</p>
                <a href="<?php echo url('public/products.php'); ?>" class="btn btn-primary" style="padding: 1.2rem 3rem;">Clear All Filters</a>
            </div>
        <?php else: ?>
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2.5rem; padding: 0;">
                <?php foreach ($products as $p): ?>
                    <div class="premium-card" style="padding: 0; overflow: hidden; border-radius: 35px; transition: var(--transition);">
                        <div style="position: relative; overflow: hidden; height: 260px;">
                            <img src="<?php echo htmlspecialchars($p['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600'); ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover; transition: var(--transition);"
                                 onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                            <span style="position: absolute; top: 20px; left: 20px; background: var(--white); color: var(--primary); padding: 6px 16px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; box-shadow: var(--shadow-soft);">
                                <?php echo htmlspecialchars($p['category_name']); ?>
                            </span>
                        </div>
                        <div style="padding: 2.2rem;">
                            <h3 style="font-size: 1.4rem; margin-bottom: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($p['name']); ?></h3>
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                                <div>
                                    <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary); line-height: 1;"><?php echo formatCurrency($p['price']); ?></div>
                                    <small style="color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;">Per <?php echo htmlspecialchars($p['unit']); ?></small>
                                </div>
                                <div style="text-align: right;">
                                    <small style="color: var(--text-muted); font-weight: 600; display: block; margin-bottom: 4px;"><i class="fas fa-user-circle"></i> <?php echo explode(' ', $p['farmer_name'])[0]; ?></small>
                                    <?php if($p['stock_quantity'] < 15): ?>
                                        <span style="color: var(--accent); font-size: 0.75rem; font-weight: 800;">ONLY <?php echo $p['stock_quantity']; ?> LEFT</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div style="display: flex; gap: 12px;">
                                <a href="<?php echo url('public/product-details.php?id=' . $p['id']); ?>" class="btn glass" style="flex: 1; padding: 1rem; border: 1.5px solid var(--border-light);"><i class="fas fa-eye"></i></a>
                                <button class="btn btn-primary add-to-cart" data-id="<?php echo $p['id']; ?>" style="flex: 2.5; padding: 1rem; font-size: 1rem; border-radius: 18px;">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
