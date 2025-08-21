<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

global $db;

try {
    $db->query(
        "INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())",
        [$name, $email, $message]
    );
    
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
}
?>
