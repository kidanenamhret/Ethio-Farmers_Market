<?php
require_once __DIR__ . '/../config/database.php';

echo "--- Products ---\n";
$stmt = $pdo->query("SELECT p.id, p.name, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($products as $p) {
    echo "ID: {$p['id']} | Name: {$p['name']} | Category: {$p['cat_name']}\n";
}
if (empty($products)) echo "No products found.\n";
