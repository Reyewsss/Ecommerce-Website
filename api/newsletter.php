<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

global $db;

// Check if email already exists
$existing = $db->fetchOne("SELECT id FROM newsletter_subscribers WHERE email = ?", [$email]);

if ($existing) {
    echo json_encode(['success' => false, 'message' => 'Email already subscribed']);
    exit;
}

try {
    $db->query(
        "INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (?, NOW())",
        [$email]
    );
    
    echo json_encode(['success' => true, 'message' => 'Successfully subscribed to newsletter']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Subscription failed']);
}
?>
