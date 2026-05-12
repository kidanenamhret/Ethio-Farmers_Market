<?php
// public/admin/manage-categories.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = sanitize($_POST['name'] ?? '');
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        redirect(url('public/admin/manage-categories.php'), "Category '$name' added!", 'success');
        exit();
    }
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    redirect(url('public/admin/manage-categories.php'), "Category removed.", 'success');
    exit();
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count FROM categories c ORDER BY name ASC")->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 1000px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <div>
            <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Market <span style="color: var(--primary);">Categories</span></h1>
            <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Organize products for better discovery</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 4rem; align-items: start;">
        <!-- Categories List -->
        <div class="premium-card" style="padding: 0; border-radius: 35px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; background: var(--bg-main); color: var(--text-muted); border-bottom: 1px solid var(--border-light);">
                        <th style="padding: 1.5rem 2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Category Name</th>
                        <th style="padding: 1.5rem 2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Products</th>
                        <th style="padding: 1.5rem 2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1.5rem 2rem;">
                                <div style="font-weight: 800; color: var(--text-main); font-size: 1.1rem;"><?php echo htmlspecialchars($cat['name']); ?></div>
                            </td>
                            <td style="padding: 1.5rem 2rem;">
                                <span style="background: var(--bg-main); padding: 5px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">
                                    <?php echo $cat['product_count']; ?> Products
                                </span>
                            </td>
                            <td style="padding: 1.5rem 2rem; text-align: right;">
                                <a href="?delete=<?php echo $cat['id']; ?>" style="color: var(--accent); font-size: 1.2rem; margin-left: 1rem;" onclick="return confirm('Deleting this category will NOT delete the products, but they will become uncategorized. Proceed?')">
                                    <i class="fas fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Add Category Form -->
        <aside>
            <div class="premium-card" style="padding: 2.5rem; border-radius: 30px;">
                <h3 style="margin-bottom: 2rem; font-size: 1.4rem;">Add Category</h3>
                <form method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="display: grid; gap: 8px;">
                        <label style="font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Name</label>
                        <input type="text" name="name" required placeholder="e.g. Dairy & Eggs" 
                               style="width: 100%; padding: 1rem; border-radius: 12px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; transition: var(--transition);"
                               onfocus="this.style.borderColor='var(--primary-glow)'; this.style.background='white'">
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary" style="padding: 1rem; border-radius: 15px; box-shadow: 0 10px 20px -5px rgba(27, 94, 32, 0.3);">
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                </form>
            </div>
            
            <div style="margin-top: 2rem; padding: 1.5rem; background: var(--bg-main); border-radius: 20px; border: 1px dashed var(--border-light);">
                <p style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; line-height: 1.5;">
                    <i class="fas fa-info-circle" style="color: var(--primary); margin-right: 5px;"></i> 
                    Categories help customers filter the marketplace and find specific products faster.
                </p>
            </div>
        </aside>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
