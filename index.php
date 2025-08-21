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
                <a href="products.php" class="btn-primary">Shop Now</a>
            </div>
            <div class="hero-image">
                <img src="uploads/img/Model 1.png" alt="Beautiful woman with Calla Noa cosmetics" class="hero-model">
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
            <h2>Shop by Category</h2>
            <div class="category-grid">
                <div class="category-item">
                    <img src="assets/images/category-skincare.jpg" alt="Skincare">
                    <h3>Skincare</h3>
                    <a href="products.php?category=skincare" class="btn-secondary">Shop Skincare</a>
                </div>
                <div class="category-item">
                    <img src="assets/images/category-makeup.jpg" alt="Makeup">
                    <h3>Makeup</h3>
                    <a href="products.php?category=makeup" class="btn-secondary">Shop Makeup</a>
                </div>
                <div class="category-item">
                    <img src="assets/images/category-fragrance.jpg" alt="Fragrance">
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
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>