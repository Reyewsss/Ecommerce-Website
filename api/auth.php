<?php
header('Content-Type: application/json');
require_once '../includes/session.php';
require_once '../config/database.php';

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        login();
        break;
    case 'register':
        register();
        break;
    case 'logout':
        logout();
        break;
    case 'forgot_password':
        forgotPassword();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function login() {
    global $db;
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        return;
    }
    
    $user = $db->fetchOne("SELECT * FROM users WHERE email = ? AND status = 'active'", [$email]);
    
    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        return;
    }
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    
    // Update last login
    $db->query("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Login successful',
        'redirect' => $user['role'] === 'admin' ? '../admin/dashboard.php' : 'user/dashboard.php'
    ]);
}

function register() {
    global $db;
    
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        return;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        return;
    }
    
    // Check if email already exists
    $existing = $db->fetchOne("SELECT id FROM users WHERE email = ?", [$email]);
    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        return;
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $db->query(
            "INSERT INTO users (first_name, last_name, email, password, role, status, created_at) 
             VALUES (?, ?, ?, ?, 'customer', 'active', NOW())",
            [$firstName, $lastName, $email, $hashedPassword]
        );
        
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
    }
}

function logout() {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

function forgotPassword() {
    global $db;
    
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        return;
    }
    
    $user = $db->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Email not found']);
        return;
    }
    
    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $db->query(
        "INSERT INTO password_resets (email, token, expires_at, created_at) VALUES (?, ?, ?, NOW())",
        [$email, $token, $expires]
    );
    
    // In a real application, send email with reset link
    // For now, just return success
    echo json_encode(['success' => true, 'message' => 'Password reset link sent to email']);
}
?>
