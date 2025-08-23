<?php
$page_title = "Shopping Cart";
include_once 'includes/header.php';
?>

<main class="cart-page">
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="cart-container" id="cartContainer">
            <div class="loading-cart">Loading cart items...</div>
        </div>
        <div class="cart-summary" id="cartSummary" style="display: none;">
            <div class="total-section">
                <h3>Total: <span id="cartTotal">$0.00</span></h3>
                <div class="cart-actions">
                    <button class="btn btn-secondary" onclick="window.location.href='products.php'">Continue Shopping</button>
                    <button class="btn btn-primary" id="checkoutBtn">Proceed to Checkout</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    displayCartPage();
    
    // Checkout button
    $('#checkoutBtn').on('click', function() {
        if (cartItems.length > 0) {
            window.location.href = 'checkout.php';
        } else {
            alert('Your cart is empty!');
        }
    });
});

function displayCartPage() {
    loadCartFromStorage();
    
    if (cartItems.length === 0) {
        $('#cartContainer').html(`
            <div class="empty-cart-page">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p>Add some products to your cart to continue shopping</p>
                <a href="products.php" class="btn btn-primary">Shop Now</a>
            </div>
        `);
        $('#cartSummary').hide();
        return;
    }
    
    const colors = ['#ff6b9d', '#74b9ff', '#fd79a8', '#fdcb6e', '#a29bfe', '#6c5ce7'];
    let html = '<div class="cart-items-list">';
    let total = 0;
    
    cartItems.forEach(function(item, index) {
        const bgColor = colors[index % colors.length];
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        html += `
            <div class="cart-item-page" data-product-id="${item.id}">
                <div class="cart-item-image-placeholder" style="width: 100px; height: 100px; background: linear-gradient(135deg, ${bgColor}, ${bgColor}99); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; border-radius: 8px; font-weight: bold; text-align: center; padding: 10px;">
                    ${item.name.substring(0, 12)}
                </div>
                <div class="cart-item-details">
                    <h4 class="cart-item-name">${item.name}</h4>
                    <p class="cart-item-price">$${item.price.toFixed(2)} each</p>
                    <div class="cart-item-controls">
                        <div class="quantity-controls">
                            <button class="qty-btn qty-decrease" data-product-id="${item.id}">-</button>
                            <input type="number" class="qty-input" value="${item.quantity}" min="1" data-product-id="${item.id}">
                            <button class="qty-btn qty-increase" data-product-id="${item.id}">+</button>
                        </div>
                        <button class="remove-item" data-product-id="${item.id}" title="Remove item">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
                <div class="cart-item-total">
                    <strong>$${itemTotal.toFixed(2)}</strong>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    $('#cartContainer').html(html);
    $('#cartTotal').text('$' + total.toFixed(2));
    $('#cartSummary').show();
}
</script>

<?php include_once 'includes/footer.php'; ?>
