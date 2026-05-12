<?php
// public/farmer/add_product.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

checkRole('farmer');

$farmer_id = $_SESSION['user_id'];

// Fetch categories for dropdown
$catStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = $_POST['description'] ?? ''; // Allow raw HTML for details
    $category = (int)($_POST['category'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $unit = sanitize($_POST['unit'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    
    // Image Upload Logic
    $image_url = 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600'; // Default
    
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName = $_FILES['product_image']['name'];
        $fileSize = $_FILES['product_image']['size'];
        $fileType = $_FILES['product_image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = __DIR__ . '/../../assets/image/products/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_url = url('assets/image/products/' . $newFileName);
            }
        }
    }

    $stmt = $pdo->prepare(
        "INSERT INTO products (farmer_id, category_id, name, description, price, unit, image_url, stock_quantity, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')"
    );
    
    if ($stmt->execute([$farmer_id, $category, $name, $description, $price, $unit, $image_url, $stock])) {
        redirect(url('public/farmer/dashboard.php'), 'Harvest listed successfully!', 'success');
        exit();
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 1000px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <div style="margin-bottom: 4rem;">
        <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">List New <span style="color: var(--primary);">Harvest</span></h1>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Add your fresh produce to the global marketplace</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;">
        <!-- Form Side -->
        <div class="premium-card" style="padding: 3rem; border-radius: 35px;">
            <form method="POST" action="" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 2rem;">
                <div style="display: grid; gap: 10px;">
                    <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Product Name</label>
                    <input type="text" name="name" required placeholder="e.g. Premium Arabica Coffee" 
                           style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; transition: var(--transition);"
                           onfocus="this.style.borderColor='var(--primary-glow)'; this.style.background='white'">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div style="display: grid; gap: 10px;">
                        <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Category</label>
                        <select name="category" required style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; cursor: pointer;">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="display: grid; gap: 10px;">
                        <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Unit</label>
                        <input type="text" name="unit" required placeholder="kg, pc, bag..." 
                               style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div style="display: grid; gap: 10px;">
                        <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Price (ETB)</label>
                        <input type="number" step="0.01" name="price" required placeholder="0.00" 
                               style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none;">
                    </div>
                    <div style="display: grid; gap: 10px;">
                        <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Initial Stock</label>
                        <input type="number" name="stock" required placeholder="100" 
                               style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none;">
                    </div>
                </div>

                <div style="display: grid; gap: 10px;">
                    <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Description</label>
                    <textarea name="description" rows="4" placeholder="Describe the quality and source of your produce..." 
                              style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; resize: none;"></textarea>
                </div>

                <div style="display: grid; gap: 10px;">
                    <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Product Image</label>
                    <div style="position: relative; overflow: hidden; border: 2px dashed var(--border-light); border-radius: 16px; padding: 2rem; text-align: center; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border-light)'">
                        <i class="fas fa-cloud-arrow-up" style="font-size: 2rem; color: var(--primary); margin-bottom: 10px; display: block;"></i>
                        <span style="font-weight: 700; color: var(--text-muted); font-size: 0.9rem;">Click to upload image</span>
                        <input type="file" name="product_image" id="imageInput" accept="image/*" required
                               style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 1.5rem; font-size: 1.1rem; border-radius: 20px; box-shadow: 0 15px 30px -10px rgba(27, 94, 32, 0.4); margin-top: 1rem;">
                    <i class="fas fa-plus-circle"></i> List Product
                </button>
            </form>
        </div>

        <!-- Preview Side -->
        <div style="position: sticky; top: 110px;">
            <h4 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 2rem; font-weight: 800; text-align: center;">Live Preview</h4>
            <div class="premium-card" style="padding: 0; overflow: hidden; border-radius: 40px; box-shadow: var(--shadow-premium);">
                <div style="height: 350px; background: var(--bg-main); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img id="previewImg" src="https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 2.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span id="previewCat" style="background: var(--primary-glow); color: var(--primary); padding: 4px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 800;">CATEGORY</span>
                        <span style="font-weight: 800; color: var(--primary);" id="previewPrice">0.00 ETB</span>
                    </div>
                    <h3 id="previewName" style="font-size: 1.8rem; letter-spacing: -0.5px; margin-bottom: 1rem;">Product Name</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;" id="previewDesc">Your product description will appear here...</p>
                </div>
            </div>
            
            <div style="margin-top: 3rem; padding: 2rem; background: var(--bg-main); border-radius: 25px; border: 1px dashed var(--border-light); display: flex; align-items: center; gap: 20px;">
                <div style="width: 50px; height: 50px; background: white; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--primary);">
                    <i class="fas fa-circle-info"></i>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; line-height: 1.5;">Once listed, your produce will be visible to all customers across Ethiopia immediately.</p>
            </div>
        </div>
    </div>
</div>

<script>
    const inputs = {
        name: document.querySelector('input[name="name"]'),
        price: document.querySelector('input[name="price"]'),
        desc: document.querySelector('textarea[name="description"]'),
        img: document.querySelector('input[name="product_image"]')
    };

    const previews = {
        name: document.getElementById('previewName'),
        price: document.getElementById('previewPrice'),
        desc: document.getElementById('previewDesc'),
        img: document.getElementById('previewImg')
    };

    inputs.name.addEventListener('input', e => previews.name.innerText = e.target.value || 'Product Name');
    inputs.price.addEventListener('input', e => previews.price.innerText = (e.target.value || '0.00') + ' ETB');
    inputs.desc.addEventListener('input', e => previews.desc.innerText = e.target.value || 'Your product description will appear here...');
    
    // File Preview Logic
    inputs.img.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ex) {
                previews.img.src = ex.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

