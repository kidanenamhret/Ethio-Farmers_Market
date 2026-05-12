<?php
require_once __DIR__ . '/../config/database.php';

// Enable error reporting
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // 1. Ensure a farmer exists
    $farmer = $pdo->query("SELECT id FROM users WHERE role = 'farmer' LIMIT 1")->fetchColumn();
    
    if (!$farmer) {
        echo "No farmer found. Creating Demo Farmer...\n";
        $pdo->prepare("INSERT INTO users (full_name, email, password, role, username) VALUES (?, ?, ?, ?, ?)")
            ->execute(['Demo Farmer', 'farmer@demo.com', password_hash('password123', PASSWORD_DEFAULT), 'farmer', 'demo_farmer']);
        $farmer = $pdo->lastInsertId();
        echo "Created Demo Farmer with ID: $farmer\n";
    } else {
        echo "Found existing farmer with ID: $farmer\n";
    }

    // 2. Clear existing products to prevent duplicates
    echo "Clearing existing products...\n";
    $pdo->exec("DELETE FROM products");

    // 3. Define products
    $products = [
        ['name' => 'Red Onions', 'cat' => 1, 'price' => 38, 'unit' => 'kg', 'img' => 'assets/images/products/onion.png', 'desc' => '<strong>Freshly Harvested Red Onions</strong><br>Our red onions are known for their pungent aroma and crisp texture. Essential for seasoning and as a base for almost all traditional Ethiopian stews (Wot).<br><br><strong>Key Features:</strong><br>• Pesticide-free organic growth<br>• Long shelf life<br>• Rich in antioxidants'],
        ['name' => 'Fresh Garlic', 'cat' => 1, 'price' => 125, 'unit' => 'kg', 'img' => 'assets/images/products/onion.png', 'desc' => '<strong>Highland Organic Garlic</strong><br>Strong, aromatic garlic bulbs harvested from the Ethiopian highlands. These cloves are packed with flavor and are much more potent than commercial varieties.<br><br><strong>Best Used For:</strong><br>• Medicinal purposes<br>• Gourmet cooking<br>• Preserved garlic pastes'],
        ['name' => 'Green Cabbage', 'cat' => 1, 'price' => 28, 'unit' => 'pc', 'img' => 'assets/images/products/cabbage.png', 'desc' => '<strong>Crispy Farm-Fresh Cabbage</strong><br>Large, dense green cabbage heads with sweet, crunchy leaves. Picked at sunrise to ensure maximum moisture content.<br><br><strong>Highlights:</strong><br>• Excellent for Salata or stewing (Tikur Gomen)<br>• Rich in Vitamin C and fiber<br>• Hand-selected for quality'],
        ['name' => 'Organic Bananas', 'cat' => 2, 'price' => 42, 'unit' => 'kg', 'img' => 'assets/images/products/mango.png', 'desc' => '<strong>Arba Minch Sweet Bananas</strong><br>These small but incredibly sweet bananas come from the lush Arba Minch region. Naturally ripened on the tree to ensure peak sugar content.<br><br><strong>Why buy these?</strong><br>• No chemical ripening agents used<br>• Intense tropical flavor<br>• High potassium content'],
        ['name' => 'Sweet Mangoes', 'cat' => 2, 'price' => 95, 'unit' => 'kg', 'img' => 'assets/images/products/mango.png', 'desc' => '<strong>Buttery Rift Valley Mangoes</strong><br>Large, fiber-free mangoes with a rich, buttery texture and heavenly aroma. Sourced from organic orchards in the Bishoftu area.<br><br><strong>Serving Suggestions:</strong><br>• Perfect for fresh smoothies<br>• Great in traditional fruit salads<br>• Ideal for healthy snacking'],
        ['name' => 'Ripe Avocado', 'cat' => 2, 'price' => 110, 'unit' => 'kg', 'img' => 'assets/images/products/mango.png', 'desc' => '<strong>High-Altitude Creamy Avocados</strong><br>Buttery and rich highland avocados. These are selected for their perfect ripeness and high healthy fat content.<br><br><strong>Nutritional Info:</strong><br>• Rich in Omega-3 fatty acids<br>• Excellent source of Vitamin E<br>• Perfect for vegan and keto diets'],
        ['name' => 'White Teff', 'cat' => 3, 'price' => 135, 'unit' => 'kg', 'img' => 'assets/images/products/teff.png', 'desc' => '<strong>Premium Grade-A White Teff</strong><br>The gold standard of Ethiopian grains. This Teff is thoroughly cleaned and stone-milled to produce the finest flour for Injera.<br><br><strong>Benefits:</strong><br>• Gluten-free and high in Iron<br>• Makes light and flexible Injera<br>• Sourced from Ada\'a (Debre Zeit) region'],
        ['name' => 'Brown Teff', 'cat' => 3, 'price' => 115, 'unit' => 'kg', 'img' => 'assets/images/products/teff.png', 'desc' => '<strong>Traditional Nutty Brown Teff</strong><br>Preferred by many for its deep, nutty flavor and higher nutritional density. Brown teff is robust and full-bodied.<br><br><strong>Key Facts:</strong><br>• Extremely high in calcium and fiber<br>• Lower glycemic index than white teff<br>• Traditional highland variety'],
        ['name' => 'Whole Barley', 'cat' => 3, 'price' => 85, 'unit' => 'kg', 'img' => 'assets/images/products/teff.png', 'desc' => '<strong>Ancient Highland Barley</strong><br>Ancient grain harvested from the high-altitude fields. This whole barley is perfect for making traditional Besso or Kolo.<br><br><strong>Tradition:</strong><br>• Essential for energy-boosting snacks<br>• Milled for thick, healthy soups<br>• Organic and non-GMO'],
        ['name' => 'Fresh Cow Milk', 'cat' => 4, 'price' => 68, 'unit' => 'L', 'img' => 'assets/images/products/milk.png', 'desc' => '<strong>Farm-to-Table Raw Milk</strong><br>Pure, whole milk from healthy, grass-fed cows. Our milk is never diluted and comes from local smallholder farms.<br><br><strong>Quality Check:</strong><br>• High butterfat content<br>• No antibiotics or hormones<br>• Delivered within hours of milking'],
        ['name' => 'Ethiopian Butter', 'cat' => 4, 'price' => 480, 'unit' => 'kg', 'img' => 'assets/images/products/milk.png', 'desc' => '<strong>Premium Traditional Butter</strong><br>Hand-churned butter made from fresh highland cream. This is the perfect base for creating Niter Kibbeh (Spiced Butter).<br><br><strong>Characteristics:</strong><br>• Creamy and rich texture<br>• High melting point<br>• Distinctive organic aroma'],
        ['name' => 'Cottage Cheese', 'cat' => 4, 'price' => 190, 'unit' => 'kg', 'img' => 'assets/images/products/milk.png', 'desc' => '<strong>Fresh Handmade Ayibe</strong><br>Light, crumbly, and freshly made Ethiopian cottage cheese. Prepared daily to accompany spicy wots and kitfo.<br><br><strong>Serving:</strong><br>• Best served cold with hot stew<br>• High protein, low fat content<br>• 100% natural ingredients'],
        ['name' => 'Berbere Mix', 'cat' => 5, 'price' => 230, 'unit' => 'kg', 'img' => 'assets/images/products/tomato.png', 'desc' => '<strong>Authentic Family-Recipe Berbere</strong><br>A complex blend of sun-dried chili peppers and over 15 hand-ground spices. The true soul of Ethiopian flavor.<br><br><strong>Spices Included:</strong><br>• Korarima, Ginger, Garlic<br>• Sacred Basil and Fenugreek<br>• Medium-hot heat level'],
        ['name' => 'Highland Honey', 'cat' => 5, 'price' => 290, 'unit' => 'kg', 'img' => 'assets/images/products/mango.png', 'desc' => '<strong>Organic Wildflower Highland Honey</strong><br>Pure, dark honey harvested from wildflower hives in the Ethiopian highlands. Rich, thick, and unprocessed.<br><br><strong>Health Benefits:</strong><br>• Natural antibacterial properties<br>• Rich in enzymes and pollen<br>• Unique floral undertones'],
        ['name' => 'Ethiopian Coffee', 'cat' => 5, 'price' => 495.00, 'unit' => 'kg', 'img' => 'assets/images/products/teff.png', 'desc' => '<strong>Original Yirgacheffe Coffee Beans</strong><br>Legendary beans from the birthplace of coffee. Medium roast to preserve the delicate floral and citrus notes.<br><br><strong>Tasting Profile:</strong><br>• Jasmine and Lemon notes<br>• Bright, clean acidity<br>• Medium body with silky finish']
    ];

    // 4. Insert products
    $insert = $pdo->prepare("INSERT INTO products (farmer_id, category_id, name, description, price, unit, stock_quantity, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')");

    foreach ($products as $p) {
        $insert->execute([$farmer, $p['cat'], $p['name'], $p['desc'], $p['price'], $p['unit'], 50, $p['img']]);
    }

    echo "Successfully seeded " . count($products) . " products!\n";

} catch (Exception $e) {
    echo "ERROR during seeding: " . $e->getMessage() . "\n";
}

