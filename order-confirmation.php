<?php
$page_title = "Order Confirmation";
require_once 'includes/header.php';

$order_id = intval($_GET['order_id'] ?? $_GET['order'] ?? 0);
if (!$order_id) {
    header('Location: index.php');
    exit;
}
?>

<main class="order-confirmation">
    <div class="container">
        <div class="confirmation-wrapper">
            <div class="confirmation-card">
                <div class="confirmation-header">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1>Order Confirmed!</h1>
                    <p class="confirmation-message">Thank you for your purchase. Your order has been successfully placed and will be processed shortly.</p>
                </div>
                
                <div id="orderDetails" class="order-details">
                    <div class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Loading order details...</p>
                    </div>
                </div>
            </div>
            
            <div class="confirmation-sidebar">
                <div class="next-steps">
                    <h3><i class="fas fa-list-ul"></i> What's Next?</h3>
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Order Processing</h4>
                            <p>We'll prepare your items for shipping</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Shipping Notification</h4>
                            <p>You'll receive a tracking number via email</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Delivery</h4>
                            <p>Your order will arrive at your doorstep</p>
                        </div>
                    </div>
                </div>
                
                <div class="support-info">
                    <h3><i class="fas fa-headset"></i> Need Help?</h3>
                    <p>Our customer service team is here to help you with any questions.</p>
                    <div class="contact-methods">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>1-800-BEAUTY</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>support@beautystore.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <span>Mon-Fri 9AM-6PM EST</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="confirmation-actions">
            <a href="products.php" class="btn btn-secondary btn-large">
                <i class="fas fa-shopping-bag"></i>
                Continue Shopping
            </a>
            <a href="index.php" class="btn btn-primary btn-large">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
            <button onclick="window.print()" class="btn btn-outline">
                <i class="fas fa-print"></i>
                Print Receipt
            </button>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    loadOrderDetails(<?php echo $order_id; ?>);
});

function loadOrderDetails(orderId) {
    $.ajax({
        url: 'api/orders.php',
        type: 'GET',
        data: {
            action: 'get_order',
            order_id: orderId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayOrderDetails(response.order);
            } else {
                $('#orderDetails').html(`
                    <div class="error-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Unable to load order details. Please contact customer service.</p>
                    </div>
                `);
            }
        },
        error: function() {
            $('#orderDetails').html(`
                <div class="error-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Unable to load order details. Please try again later.</p>
                </div>
            `);
        }
    });
}

function displayOrderDetails(order) {
    const orderDate = new Date(order.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    let itemsHtml = '';
    if (order.items && order.items.length > 0) {
        order.items.forEach(item => {
            const bgColor = getRandomColor();
            itemsHtml += `
                <div class="order-item">
                    <div class="item-image-placeholder" style="width: 60px; height: 60px; background: linear-gradient(135deg, ${bgColor}, ${bgColor}99); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.7rem; border-radius: 8px; font-weight: bold; text-align: center;">
                        ${item.name.substring(0, 2).toUpperCase()}
                    </div>
                    <div class="item-details">
                        <h4>${item.name}</h4>
                        <p>Quantity: ${item.quantity}</p>
                        <p class="item-price">$${parseFloat(item.total).toFixed(2)}</p>
                    </div>
                </div>
            `;
        });
    }
    
    const orderHtml = `
        <div class="order-summary-section">
            <div class="order-info">
                <div class="info-row">
                    <span class="label">Order Number:</span>
                    <span class="value order-number">${order.order_number}</span>
                </div>
                <div class="info-row">
                    <span class="label">Order Date:</span>
                    <span class="value">${orderDate}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value status-badge status-${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
                </div>
            </div>
        </div>
        
        <div class="order-items-section">
            <h3><i class="fas fa-box"></i> Order Items</h3>
            <div class="items-list">
                ${itemsHtml}
            </div>
        </div>
        
        <div class="shipping-billing-section">
            <div class="shipping-info">
                <h3><i class="fas fa-truck"></i> Shipping Address</h3>
                <div class="address-block">
                    <p><strong>${order.shipping_first_name} ${order.shipping_last_name}</strong></p>
                    <p>${order.shipping_address}</p>
                    <p>${order.shipping_city}, ${order.shipping_state} ${order.shipping_zip}</p>
                    <p>Phone: ${order.shipping_phone}</p>
                    <p>Email: ${order.shipping_email}</p>
                </div>
                ${order.shipping_method ? `<p class="shipping-method"><strong>Shipping Method:</strong> ${order.shipping_method.charAt(0).toUpperCase() + order.shipping_method.slice(1)} Shipping</p>` : ''}
            </div>
            
            <div class="payment-info">
                <h3><i class="fas fa-credit-card"></i> Payment Method</h3>
                <div class="payment-details">
                    <p>${order.payment_method === 'credit_card' ? 'Credit Card' : 'PayPal'}</p>
                    ${order.payment_method === 'credit_card' ? '<p class="card-info">Card ending in ****</p>' : ''}
                </div>
            </div>
        </div>
        
        <div class="order-totals-section">
            <h3><i class="fas fa-calculator"></i> Order Summary</h3>
            <div class="totals-breakdown">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>$${parseFloat(order.subtotal || 0).toFixed(2)}</span>
                </div>
                ${order.shipping_amount > 0 ? `
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>$${parseFloat(order.shipping_amount).toFixed(2)}</span>
                </div>
                ` : `
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                `}
                <div class="total-row">
                    <span>Tax:</span>
                    <span>$${parseFloat(order.tax_amount || 0).toFixed(2)}</span>
                </div>
                ${order.discount_amount > 0 ? `
                <div class="total-row discount">
                    <span>Discount${order.promo_code ? ` (${order.promo_code})` : ''}:</span>
                    <span>-$${parseFloat(order.discount_amount).toFixed(2)}</span>
                </div>
                ` : ''}
                <div class="total-row total-final">
                    <span><strong>Total:</strong></span>
                    <span><strong>$${parseFloat(order.total_amount).toFixed(2)}</strong></span>
                </div>
            </div>
        </div>
    `;
    
    $('#orderDetails').html(orderHtml);
}

function getRandomColor() {
    const colors = ['#ff6b9d', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dda0dd', '#98d8c8', '#f7dc6f'];
    return colors[Math.floor(Math.random() * colors.length)];
}
</script>

<?php include_once 'includes/footer.php'; ?>
    $.ajax({
        url: 'api/orders.php',
        method: 'GET',
        data: { 
            action: 'get_order',
            order_id: orderId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayOrderDetails(response.order);
            } else {
                $('#orderDetails').html('<p class="text-center">Order not found.</p>');
            }
        },
        error: function() {
            $('#orderDetails').html('<p class="text-center">Error loading order details.</p>');
        }
    });
}

function displayOrderDetails(order) {
    let itemsHtml = '';
    order.items.forEach(function(item) {
        itemsHtml += `
            <div class="order-item">
                <img src="assets/images/products/${item.image || 'placeholder.jpg'}" alt="${item.name}" class="item-image">
                <div class="item-info">
                    <h4>${item.name}</h4>
                    <p>Quantity: ${item.quantity}</p>
                    <p class="item-price">$${parseFloat(item.total).toFixed(2)}</p>
                </div>
            </div>
        `;
    });
    
    const html = `
        <div class="order-summary">
            <h3>Order #${order.order_number}</h3>
            <p><strong>Order Date:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
            <p><strong>Status:</strong> <span class="order-status ${order.status}">${order.status.toUpperCase()}</span></p>
        </div>
        
        <div class="order-items">
            <h4>Items Ordered:</h4>
            ${itemsHtml}
        </div>
        
        <div class="order-totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>$${parseFloat(order.subtotal).toFixed(2)}</span>
            </div>
            <div class="total-row">
                <span>Tax:</span>
                <span>$${parseFloat(order.tax_amount).toFixed(2)}</span>
            </div>
            <div class="total-row total-final">
                <span>Total:</span>
                <span>$${parseFloat(order.total_amount).toFixed(2)}</span>
            </div>
        </div>
        
        <div class="shipping-info">
            <h4>Shipping Address:</h4>
            <p>
                ${order.shipping_first_name} ${order.shipping_last_name}<br>
                ${order.shipping_address}<br>
                ${order.shipping_city}, ${order.shipping_state} ${order.shipping_zip}<br>
                Phone: ${order.shipping_phone}<br>
                Email: ${order.shipping_email}
            </p>
        </div>
    `;
    
    $('#orderDetails').html(html);
}
</script>

<style>
.order-confirmation {
    padding: 3rem 0;
    background: #f8f9fa;
    min-height: 70vh;
}

.confirmation-card {
    background: white;
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.confirmation-header {
    margin-bottom: 3rem;
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 1rem;
}

.confirmation-header h1 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.order-details {
    text-align: left;
    margin-bottom: 3rem;
}

.order-summary {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.order-status {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.875rem;
    font-weight: 500;
}

.order-status.pending {
    background: #fff3cd;
    color: #856404;
}

.order-items {
    margin-bottom: 2rem;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 1rem;
}

.item-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
}

.item-info p {
    margin: 0.25rem 0;
    color: #666;
}

.item-price {
    color: var(--primary-color);
    font-weight: 600;
}

.order-totals {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.total-final {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary-color);
    padding-top: 0.5rem;
    border-top: 1px solid #dee2e6;
}

.shipping-info {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.confirmation-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

@media (max-width: 768px) {
    .confirmation-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
