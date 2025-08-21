<?php
include_once 'includes/header.php';
include_once 'config/database.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<main class="product-details">
    <div class="container">
        <div class="product-detail-container" id="productDetail">
            <!-- Product details will be loaded here -->
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
