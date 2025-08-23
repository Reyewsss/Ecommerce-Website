<?php
$page_title = 'Home';
include_once 'includes/header.php';
include_once 'config/database.php';
?>

<main class="homepage">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Discover Your <span class="highlight">Beauty</span></h1>
                <p>Experience the elegance of premium cosmetics designed for the sophisticated, modern woman. Enhance your natural radiance with our luxurious collection.</p>
                <a href="products.php" class="shop-now-btn">Shop Now</a>
            </div>
            <div class="hero-image">
                <img src="uploads/img/Hero Model/Model 2.png" alt="Beautiful woman with Calla Noa cosmetics" class="hero-model">
            </div>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section class="featured-products">
        <div class="container">
            <h2>Featured Products</h2>
            <div class="product-grid" id="featuredProducts">
                <!-- Featured products will be loaded here via JavaScript -->
            </div>
        </div>
    </section>
    
    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <h2>Categories</h2>
            <div class="category-grid">
                <div class="category-item">
                    <img src="uploads/img/Mockups/MockupDesign1.png" alt="Skincare">
                    <h3>Skincare</h3>
                    <a href="products.php?category=skincare" class="btn-secondary">Shop Skincare</a>
                </div>
                <div class="category-item">
                    <img src="uploads/img/Mockups/MockupDesign2.png" alt="Makeup">
                    <h3>Makeup</h3>
                    <a href="products.php?category=makeup" class="btn-secondary">Shop Makeup</a>
                </div>
                <div class="category-item">
                    <img src="uploads/img/Mockups/MockupDesign3.png" alt="Fragrance">
                    <h3>Fragrance</h3>
                    <a href="products.php?category=fragrance" class="btn-secondary">Shop Fragrance</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <h2>Stay Updated</h2>
                <p>Subscribe to our newsletter for the latest beauty tips and exclusive offers</p>
                <form id="newsletterForm" class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required style="font-family: Poppins, sans-serif;">
                    <button type="submit" class="btn-newsletter">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>