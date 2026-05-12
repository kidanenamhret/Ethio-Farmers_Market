<?php
require_once __DIR__ . '/../config/database.php';
$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
$stmt->execute(['%Cabbage%']);
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
