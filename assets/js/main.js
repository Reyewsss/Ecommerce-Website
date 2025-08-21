// Simple Cart with Local Storage
$(document).ready(function() {
    // Initialize cart
    loadCartFromStorage();
    updateCartCount();
    
    // Cart Modal Controls
    $('.cart-icon').on('click', function(e) {
        e.preventDefault();
        openCartModal();
    });
    
    $('#closeCart, #cartOverlay').on('click', function() {
        closeCartModal();
    });
    
    // Add to cart functionality
    $(document).on('click', '.add-to-cart', function() {
        const productCard = $(this).closest('.product-card');
        const productId = $(this).data('product-id');
        const productName = productCard.find('.product-title').text();
        const productPrice = parseFloat(productCard.find('.product-price').text().replace('$', ''));
        const quantity = parseInt(productCard.find('.quantity-input').val()) || 1;
        
        addToCart(productId, productName, productPrice, quantity);
    });
    
    // Remove from cart
    $(document).on('click', '.remove-item', function() {
        const productId = $(this).data('product-id');
        removeFromCart(productId);
    });
    
    // Cart page specific functionality
    $(document).ready(function() {
        // Cart page quantity controls
        $(document).on('click', '.qty-btn', function() {
            const isIncrease = $(this).hasClass('qty-increase');
            const productId = $(this).data('product-id');
            let input;
            
            if ($(this).closest('.cart-item').length) {
                // Modal cart
                input = $(this).closest('.cart-item').find('.qty-input');
            } else {
                // Cart page
                input = $(this).closest('.cart-item-page').find('.qty-input');
            }
            
            const currentVal = parseInt(input.val()) || 1;
            let newVal = isIncrease ? currentVal + 1 : Math.max(1, currentVal - 1);
            input.val(newVal);
            
            updateCartQuantity(productId, newVal);
            
            // Refresh cart page if we're on it
            if (window.location.pathname.includes('cart.php') && typeof displayCartPage === 'function') {
                displayCartPage();
            }
        });
        
        // Direct input change on cart page
        $(document).on('change', '.qty-input', function() {
            const productId = $(this).data('product-id');
            const newVal = Math.max(1, parseInt($(this).val()) || 1);
            $(this).val(newVal);
            
            updateCartQuantity(productId, newVal);
            
            if (window.location.pathname.includes('cart.php') && typeof displayCartPage === 'function') {
                displayCartPage();
            }
        });
    });
    
    // Load products dynamically
    if ($('#featuredProducts').length) {
        loadProducts('#featuredProducts');
    }
    
    if ($('#productGrid').length) {
        loadProducts('#productGrid');
    }
    
    // Mobile menu toggle
    $('.mobile-menu-btn').on('click', function() {
        $('.nav-menu').toggleClass('active');
        const icon = $(this).find('i');
        if ($('.nav-menu').hasClass('active')) {
            icon.removeClass('fa-bars').addClass('fa-times');
        } else {
            icon.removeClass('fa-times').addClass('fa-bars');
        }
    });
});

// Cart storage
let cartItems = [];

// Load products with test data
function loadProducts(container) {
    const testProducts = [
        { id: 1, name: 'Luxury Matte Lipstick', price: 25.99 },
        { id: 2, name: 'Flawless Foundation', price: 35.99 },
        { id: 3, name: 'Volume Max Mascara', price: 19.99 },
        { id: 4, name: 'Perfect Blush', price: 22.99 },
        { id: 5, name: 'Eye Shadow Palette', price: 45.99 },
        { id: 6, name: 'Long-Lasting Eyeliner', price: 18.99 }
    ];
    
    displayProducts(testProducts, container);
}

function displayProducts(products, container) {
    let html = '';
    const colors = ['#ff6b9d', '#74b9ff', '#fd79a8', '#fdcb6e', '#a29bfe', '#6c5ce7'];
    
    products.forEach(function(product, index) {
        const bgColor = colors[index % colors.length];
        html += `
            <div class="product-card">
                <div class="product-image-placeholder" style="width: 100%; height: 200px; background: linear-gradient(135deg, ${bgColor}, ${bgColor}99); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border-radius: 8px 8px 0 0; font-size: 0.9rem; text-align: center; padding: 10px;">
                    ${product.name}
                </div>
                <div class="product-info">
                    <h3 class="product-title">${product.name}</h3>
                    <p class="product-price">$${product.price}</p>
                    <div class="product-actions">
                        <input type="number" class="quantity-input" value="1" min="1" max="10">
                        <button class="btn-primary add-to-cart" data-product-id="${product.id}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    $(container).html(html);
}

// Cart Modal Functions
function openCartModal() {
    $('#cartModal').addClass('active');
    $('body').addClass('modal-open');
    displayCartItems();
}

function closeCartModal() {
    $('#cartModal').removeClass('active');
    $('body').removeClass('modal-open');
}

// Cart functionality with localStorage
function addToCart(productId, productName, productPrice, quantity) {
    const existingItem = cartItems.find(item => item.id == productId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cartItems.push({
            id: productId,
            name: productName,
            price: productPrice,
            quantity: quantity
        });
    }
    
    saveCartToStorage();
    updateCartCount();
    showNotification('Product added to cart!', 'success');
    
    // Auto-open cart modal
    setTimeout(function() {
        openCartModal();
    }, 300);
}

function removeFromCart(productId) {
    cartItems = cartItems.filter(item => item.id != productId);
    saveCartToStorage();
    updateCartCount();
    displayCartItems();
    showNotification('Product removed from cart', 'success');
}

function updateCartQuantity(productId, quantity) {
    const item = cartItems.find(item => item.id == productId);
    if (item) {
        item.quantity = quantity;
        saveCartToStorage();
        updateCartCount();
        displayCartItems();
    }
}

function displayCartItems() {
    if (cartItems.length === 0) {
        $('.cart-body').html(`
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>No items found</p>
            </div>
        `);
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
            <div class="cart-item" data-product-id="${item.id}">
                <div class="cart-item-image-placeholder" style="width: 60px; height: 60px; background: linear-gradient(135deg, ${bgColor}, ${bgColor}99); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.7rem; border-radius: 4px; font-weight: bold; text-align: center; padding: 5px;">
                    ${item.name.substring(0, 8)}
                </div>
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">$${item.price.toFixed(2)} × ${item.quantity}</div>
                </div>
                <div class="cart-item-controls">
                    <div class="quantity-controls">
                        <button class="qty-btn qty-decrease">-</button>
                        <input type="number" class="qty-input" value="${item.quantity}" min="1" readonly>
                        <button class="qty-btn qty-increase">+</button>
                    </div>
                    <button class="remove-item" data-product-id="${item.id}" title="Remove item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    html += `
        <div class="cart-footer">
            <div class="cart-total">
                <strong>Total: $${total.toFixed(2)}</strong>
            </div>
            <div class="cart-actions">
                <button class="btn-secondary" onclick="window.location.href='cart.php'">View Cart</button>
                <button class="btn-primary" onclick="window.location.href='checkout.php'">Checkout</button>
            </div>
        </div>
    `;
    
    $('.cart-body').html(html);
}

function updateCartCount() {
    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
    $('.cart-count').text(totalItems);
}

function saveCartToStorage() {
    localStorage.setItem('cart', JSON.stringify(cartItems));
}

function loadCartFromStorage() {
    const saved = localStorage.getItem('cart');
    if (saved) {
        cartItems = JSON.parse(saved);
    }
}

// Simple notification function
function showNotification(message, type = 'info') {
    $('.notification').remove();
    
    const notification = $(`
        <div class="notification notification-${type}" style="position: fixed; top: 20px; right: 20px; background: #ff6b9d; color: white; padding: 15px 20px; border-radius: 5px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
            ${message}
            <button class="notification-close" style="background: none; border: none; color: white; margin-left: 10px; cursor: pointer;">&times;</button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(function() {
        notification.fadeOut(function() {
            notification.remove();
        });
    }, 3000);
    
    notification.find('.notification-close').on('click', function() {
        notification.fadeOut(function() {
            notification.remove();
        });
    });
}

