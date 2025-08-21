<?php
$page_title = "Order Confirmation";
require_once 'includes/header.php';

$order_id = intval($_GET['order'] ?? 0);
if (!$order_id) {
    header('Location: index.php');
    exit;
}
?>

<main class="order-confirmation">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="confirmation-card">
                    <div class="confirmation-header">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h1>Order Confirmed!</h1>
                        <p>Thank you for your purchase. Your order has been successfully placed.</p>
                    </div>
                    
                    <div id="orderDetails" class="order-details">
                        <!-- Order details will be loaded here -->
                    </div>
                    
                    <div class="confirmation-actions">
                        <a href="products.php" class="btn-secondary">
                            <i class="fas fa-shopping-bag"></i>
                            Continue Shopping
                        </a>
                        <a href="index.php" class="btn-primary">
                            <i class="fas fa-home"></i>
                            Go to Homepage
                        </a>
                    </div>
                </div>
            </div>
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
