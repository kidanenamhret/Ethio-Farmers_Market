<?php
// ajax/subscribe.php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit();
    }
    
    // In a real app, you would save this to a 'subscribers' table
    // For this project, we'll return success to demonstrate the AJAX flow
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you! You have been subscribed to our newsletter.'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
