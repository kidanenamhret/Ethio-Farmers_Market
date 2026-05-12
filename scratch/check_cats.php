<?php
require_once __DIR__ . '/../config/database.php';

echo "--- Categories ---\n";
$stmt = $pdo->query("SELECT * FROM categories");
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($cats as $cat) {
    $p_stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $p_stmt->execute([$cat['id']]);
    $count = $p_stmt->fetchColumn();
    echo "ID: {$cat['id']} | Name: {$cat['name']} | Products: {$count}\n";
}
