<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$search = isset($_GET['q']) ? sanitize($_GET['q']) : '';

if (strlen($search) < 2) exit();

$stmt = $pdo->prepare("SELECT p.id, p.name, p.price, p.image_url, c.name as category_name 
                       FROM products p 
                       JOIN categories c ON p.category_id = c.id 
                       WHERE p.status = 'active' AND (p.name LIKE ? OR p.description LIKE ?) 
                       LIMIT 8");
$stmt->execute(["%$search%", "%$search%"]);
$results = $stmt->fetchAll();

if (empty($results)) {
    echo '<div class="search-item">No products found for "' . htmlspecialchars($search) . '"</div>';
} else {
    foreach ($results as $product) {
        $img = !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : '/assets/images/no-image.png';
?>
        <a href="<?php echo url('public/product-details.php?id=' . $product['id']); ?>" class="search-item" style="display: flex; align-items: center; gap: 15px; text-decoration: none; padding: 0.75rem; border-bottom: 1px solid #e2e8f0;">
            <img src="<?php echo $img; ?>" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
            <div>
                <h4 style="margin: 0;"><?php echo htmlspecialchars($product['name']); ?></h4>
                <small style="color: var(--primary);"><?php echo formatCurrency($product['price']); ?></small><br>
                <small style="color: #64748b;"><?php echo htmlspecialchars($product['category_name']); ?></small>
            </div>
        </a>
<?php
    }
}
?>