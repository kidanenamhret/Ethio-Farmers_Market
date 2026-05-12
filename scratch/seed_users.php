<?php
require_once __DIR__ . '/../config/database.php';

// Enable error reporting
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$demoUsers = [
    [
        'username' => 'admin_demo',
        'full_name' => 'System Administrator',
        'email' => 'admin@ethiofarmers.com',
        'password' => 'password123',
        'role' => 'admin',
        'address' => 'Addis Ababa, Ethiopia'
    ],
    [
        'username' => 'farmer_demo',
        'full_name' => 'Abebe Farmer',
        'email' => 'farmer@demo.com',
        'password' => 'password123',
        'role' => 'farmer',
        'address' => 'Debre Zeit'
    ],
    [
        'username' => 'customer_demo',
        'full_name' => 'Tigist Buyer',
        'email' => 'tigist@buyer.com',
        'password' => 'password123',
        'role' => 'customer',
        'address' => 'Bole, Addis Ababa'
    ]
];

echo "--- User Seeding Started ---\n";

foreach ($demoUsers as $user) {
    try {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$user['email']]);
        $exists = $stmt->fetchColumn();

        if (!$exists) {
            echo "Creating {$user['role']} account: {$user['email']}...\n";
            $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, password, role, address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user['username'],
                $user['full_name'],
                $user['email'],
                password_hash($user['password'], PASSWORD_DEFAULT),
                $user['role'],
                $user['address']
            ]);
            echo "Successfully created {$user['role']}.\n";
        } else {
            echo "{$user['role']} account already exists ({$user['email']}). Skipping.\n";
        }
    } catch (Exception $e) {
        echo "Error creating {$user['role']}: " . $e->getMessage() . "\n";
    }
}

echo "--- User Seeding Completed ---\n";
