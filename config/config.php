<?php
/**
 * Configuration File for Cosmetics E-commerce Website
 * 
 * IMPORTANT: Update the values below with your actual settings
 * Do not commit sensitive information to version control
 */

// ===========================================
// ENVIRONMENT SETTINGS
// ===========================================
define('DEVELOPMENT', true); // Set to false for production
define('DEBUG_MODE', true);   // Set to false for production

// ===========================================
// SESSION CONFIGURATION (Must be set before session_start())
// ===========================================
// Only set session configuration if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);    // Prevent JavaScript access to session cookies
    ini_set('session.use_only_cookies', 1);   // Only use cookies for sessions
    ini_set('session.cookie_secure', 0);      // Set to 1 if using HTTPS
    ini_set('session.cookie_lifetime', 0);    // Session cookie expires when browser closes
    ini_set('session.gc_maxlifetime', 3600);  // Session data lifetime (1 hour)
}

// ===========================================
// DATABASE CONFIGURATION
// ===========================================
// Update these with your actual MySQL database credentials
define('DB_HOST', 'localhost');           // Usually 'localhost' for local development
define('DB_PORT', '3306');               // Default MySQL port
define('DB_USERNAME', 'root');           // Your MySQL username (default: 'root' for XAMPP/WAMP)
define('DB_PASSWORD', '');               // Your MySQL password (empty for XAMPP default)
define('DB_NAME', 'cosmetics_store');    // Your database name
define('DB_CHARSET', 'utf8mb4');         // Character set
define('DB_COLLATION', 'utf8mb4_unicode_ci'); // Collation

// ===========================================
// SITE CONFIGURATION
// ===========================================
define('SITE_URL', 'http://localhost:3000/Calla Noa Website/'); // Update to match your folder path
define('SITE_NAME', 'Beauty Cosmetics Store');
define('SITE_DESCRIPTION', 'Premium cosmetics and beauty products online store');
define('SITE_EMAIL', 'admin@cosmeticsstore.com');
define('CONTACT_EMAIL', 'contact@cosmeticsstore.com');
define('SUPPORT_EMAIL', 'support@cosmeticsstore.com');

// ===========================================
// FILE UPLOAD SETTINGS
// ===========================================
define('UPLOAD_DIR', 'uploads/');
define('PRODUCT_UPLOAD_DIR', 'uploads/products/');
define('PROFILE_UPLOAD_DIR', 'uploads/profiles/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// ===========================================
// SECURITY SETTINGS
// ===========================================
define('SECURITY_SALT', 'CoSm3t1cs_St0r3_2025_S3cur3_K3y!@#'); // Change this to a random string
define('SESSION_TIMEOUT', 3600);           // 1 hour in seconds
define('LOGIN_ATTEMPTS_LIMIT', 5);         // Max login attempts before lockout
define('LOGIN_LOCKOUT_TIME', 900);         // 15 minutes lockout time
define('ADMIN_SESSION_TIMEOUT', 7200);     // 2 hours for admin sessions

// ===========================================
// PAGINATION SETTINGS
// ===========================================
define('PRODUCTS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);
define('ORDERS_PER_PAGE', 10);

// ===========================================
// CURRENCY SETTINGS
// ===========================================
define('DEFAULT_CURRENCY', 'USD');
define('CURRENCY_SYMBOL', '$');
define('TAX_RATE', 8.5);                   // Tax percentage
define('SHIPPING_COST', 5.99);             // Default shipping cost
define('FREE_SHIPPING_MINIMUM', 50.00);    // Minimum order for free shipping

// ===========================================
// EMAIL SETTINGS (for notifications)
// ===========================================
define('SMTP_HOST', 'smtp.gmail.com');     // Update with your SMTP host
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');               // Your email
define('SMTP_PASSWORD', '');               // Your email password or app password
define('SMTP_ENCRYPTION', 'tls');          // 'tls' or 'ssl'

// ===========================================
// PAYMENT GATEWAY SETTINGS
// ===========================================
// PayPal Settings
define('PAYPAL_SANDBOX', true);            // Set to false for live payments
define('PAYPAL_CLIENT_ID', 'your_paypal_client_id_here');
define('PAYPAL_CLIENT_SECRET', 'your_paypal_client_secret_here');

// Stripe Settings (if you want to add Stripe)
define('STRIPE_PUBLISHABLE_KEY', 'your_stripe_publishable_key');
define('STRIPE_SECRET_KEY', 'your_stripe_secret_key');

// ===========================================
// SOCIAL MEDIA LINKS
// ===========================================
define('FACEBOOK_URL', 'https://facebook.com/yourpage');
define('INSTAGRAM_URL', 'https://instagram.com/yourpage');
define('TWITTER_URL', 'https://twitter.com/yourpage');

// ===========================================
// ERROR REPORTING & LOGGING
// ===========================================
// Ensure logs directory exists
$logs_dir = __DIR__ . '/../logs';
if (!is_dir($logs_dir)) {
    mkdir($logs_dir, 0755, true);
}

if (DEVELOPMENT) {
    // Development environment
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', $logs_dir . '/php_errors.log');
} else {
    // Production environment
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', $logs_dir . '/php_errors.log');
}

// ===========================================
// TIMEZONE SETTINGS
// ===========================================
date_default_timezone_set('America/New_York'); // Change to your timezone

// ===========================================
// HELPER FUNCTIONS
// ===========================================

/**
 * Get the full URL for a given path
 */
function site_url($path = '') {
    $baseUrl = rtrim(SITE_URL, '/');
    if (empty($path)) {
        return $baseUrl . '/';
    }
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Get the upload URL for a given file
 */
function upload_url($file = '') {
    return SITE_URL . UPLOAD_DIR . ltrim($file, '/');
}

/**
 * Format price with currency symbol
 */
function format_price($price) {
    return CURRENCY_SYMBOL . number_format((float)$price, 2);
}

/**
 * Calculate price with tax
 */
function calculate_price_with_tax($price) {
    return $price + ($price * TAX_RATE / 100);
}

/**
 * Check if we're in development mode
 */
function is_development() {
    return defined('DEVELOPMENT') && DEVELOPMENT;
}

?>
