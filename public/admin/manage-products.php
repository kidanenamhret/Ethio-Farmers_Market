<?php
// public/admin/manage-products.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$cat_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

$query = "SELECT p.*, c.name as cat_name, u.full_name as farmer_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          JOIN users u ON p.farmer_id = u.id 
          WHERE 1=1";

$params = [];

if ($search) {
    $query .= " AND (p.name LIKE ? OR u.full_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($cat_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $cat_id;
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <div>
            <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Manage <span style="color: var(--primary);">Products</span></h1>
            <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Review and verify marketplace inventory</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <form method="GET" class="glass" style="display: flex; align-items: center; gap: 10px; padding: 10px 20px; border-radius: 14px; border: 1px solid var(--border-light);">
                <i class="fas fa-filter" style="color: var(--primary);"></i>
                <select name="category" onchange="this.form.submit()" style="border: none; background: transparent; font-family: inherit; font-weight: 700; color: var(--text-main); outline: none; cursor: pointer;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat_id == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="premium-card" style="padding: 0; border-radius: 35px; overflow: hidden;">
        <div style="padding: 2rem 2.5rem; border-bottom: 1px solid var(--border-light); background: var(--bg-main); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.4rem; letter-spacing: -0.5px;">Global Inventory</h3>
            <form method="GET" style="position: relative; width: 300px;">
                <?php if ($cat_id): ?>
                    <input type="hidden" name="category" value="<?php echo $cat_id; ?>">
                <?php endif; ?>
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.9rem;"></i>
                <input type="text" name="search" placeholder="Search products or farmers..." value="<?php echo htmlspecialchars($search); ?>" 
                       style="width: 100%; padding: 10px 15px 10px 40px; border-radius: 12px; border: 1px solid var(--border-light); outline: none; font-family: inherit; font-size: 0.9rem;">
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--border-light);">
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Product</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Farmer</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Price</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Stock</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Status</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): 
                        $status_color = match($p['status']) {
                            'active' => '#10b981',
                            'pending' => '#f59e0b',
                            'inactive' => '#ef4444',
                            default => 'var(--text-muted)'
                        };
                    ?>
                        <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 2rem 2.5rem;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="<?php echo htmlspecialchars($p['image_url'] ?: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=100'); ?>" 
                                         style="width: 50px; height: 50px; border-radius: 12px; object-fit: cover; box-shadow: var(--shadow-soft);">
                                    <div>
                                        <div style="font-weight: 800; color: var(--text-main);"><?php echo htmlspecialchars($p['name']); ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;"><?php echo htmlspecialchars($p['cat_name']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 2rem 2.5rem;">
                                <div style="font-weight: 700; color: var(--text-main);"><?php echo htmlspecialchars($p['farmer_name']); ?></div>
                            </td>
                            <td style="padding: 2rem 2.5rem;">
                                <div style="font-weight: 800; color: var(--primary);"><?php echo formatCurrency($p['price']); ?></div>
                                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">per <?php echo htmlspecialchars($p['unit']); ?></div>
                            </td>
                            <td style="padding: 2rem 2.5rem;">
                                <div style="font-weight: 700; color: var(--text-main);"><?php echo $p['stock_quantity']; ?> left</div>
                                <div style="width: 80px; height: 4px; background: #eee; border-radius: 10px; margin-top: 5px; overflow: hidden;">
                                    <div style="width: <?php echo min(100, ($p['stock_quantity'] / 100) * 100); ?>%; height: 100%; background: var(--primary);"></div>
                                </div>
                            </td>
                            <td style="padding: 2rem 2.5rem;">
                                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 50px; background: <?php echo $status_color; ?>15; color: <?php echo $status_color; ?>; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                                    <?php echo $p['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 2rem 2.5rem; text-align: right;">
                                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <a href="../product-details.php?id=<?php echo $p['id']; ?>" class="btn glass" style="width: 40px; height: 40px; padding: 0; border-radius: 12px; border: 1px solid var(--border-light); color: var(--primary); display: flex; align-items: center; justify-content: center; text-decoration: none;"><i class="fas fa-eye"></i></a>
                                    <a href="delete-product.php?id=<?php echo $p['id']; ?>" class="btn glass" style="width: 40px; height: 40px; padding: 0; border-radius: 12px; border: 1px solid var(--border-light); color: var(--accent); display: flex; align-items: center; justify-content: center; text-decoration: none;" onclick="return confirm('Are you sure you want to remove this product from the marketplace?')"><i class="fas fa-trash-can"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
