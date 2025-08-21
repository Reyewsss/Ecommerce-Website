<?php
/**
 * Session Initialization
 * 
 * This file must be included before any session operations
 * It ensures session settings are configured before session_start()
 */

// Only include config if not already included
if (!defined('DEVELOPMENT')) {
    require_once __DIR__ . '/../config/config.php';
}

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set session timeout
if (isset($_SESSION['last_activity'])) {
    $timeout = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' 
        ? ADMIN_SESSION_TIMEOUT 
        : SESSION_TIMEOUT;
        
    if (time() - $_SESSION['last_activity'] > $timeout) {
        session_destroy();
        session_start();
    }
}

$_SESSION['last_activity'] = time();

// Regenerate session ID periodically for security
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} else if (time() - $_SESSION['created'] > 1800) { // 30 minutes
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

?>
