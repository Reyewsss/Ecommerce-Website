<?php
// Initialize session and configuration
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>  

    <!-- jQuery (load before other scripts) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <!-- Logo/Brand -->
                <div class="nav-brand">
                    <a href="index.php" class="logo">
                        <span class="logo-text">Beauty Store</span>
                    </a>
                </div>
                
                <!-- Navigation Menu -->
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                
                <!-- Navigation Actions -->
                <div class="nav-actions">
                    <!-- Shopping Cart -->
                    <button class="cart-icon" id="openCartModal">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </button>
                    
                    <!-- User Account -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="user-menu">
                            <a href="user/dashboard.php" class="btn-secondary">
                                <i class="fas fa-user"></i> Dashboard
                            </a>
                            <a href="user/logout.php" class="btn-primary">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="login.php" class="btn-secondary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="register.php" class="btn-primary">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Cart Modal -->
    <div class="cart-modal" id="cartModal">
        <div class="cart-overlay" id="cartOverlay"></div>
        <div class="cart-sidebar">
            <div class="cart-header">
                <h3>Shopping Cart</h3>
                <button class="close-cart" id="closeCart">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="cart-body">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>No items found</p>
                </div>
            </div>
        </div>
    </div>
