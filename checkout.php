<?php
$page_title = "Checkout";
require_once 'includes/header.php';

// Check if cart has items
if ((!isset($_SESSION['cart']) || empty($_SESSION['cart'])) && !isset($_SESSION['user_id'])) {
    header('Location: products.php');
    exit;
}
?>

<main class="checkout-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Checkout</h1>
                <div class="checkout-progress">
                    <div class="step active">1. Shipping Information</div>
                    <div class="step">2. Payment</div>
                    <div class="step">3. Review Order</div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="checkout-form">
                    <form id="checkoutForm">
                        <!-- Shipping Information -->
                        <div class="form-section" id="shippingSection">
                            <h3>Shipping Information</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstName">First Name *</label>
                                        <input type="text" id="firstName" name="firstName" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastName">Last Name *</label>
                                        <input type="text" id="lastName" name="lastName" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Street Address *</label>
                                <input type="text" id="address" name="address" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City *</label>
                                        <input type="text" id="city" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State *</label>
                                        <input type="text" id="state" name="state" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="zipCode">ZIP Code *</label>
                                        <input type="text" id="zipCode" name="zipCode" required>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn-primary" id="continueToPayment">Continue to Payment</button>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="form-section" id="paymentSection" style="display: none;">
                            <h3>Payment Information</h3>
                            <div class="payment-methods">
                                <div class="payment-method">
                                    <input type="radio" id="creditCard" name="paymentMethod" value="credit_card" checked>
                                    <label for="creditCard">
                                        <i class="fas fa-credit-card"></i>
                                        Credit Card
                                    </label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="paypal" name="paymentMethod" value="paypal">
                                    <label for="paypal">
                                        <i class="fab fa-paypal"></i>
                                        PayPal
                                    </label>
                                </div>
                            </div>
                            
                            <div id="creditCardForm" class="payment-form">
                                <div class="form-group">
                                    <label for="cardNumber">Card Number *</label>
                                    <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expiryDate">Expiry Date *</label>
                                            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cvv">CVV *</label>
                                            <input type="text" id="cvv" name="cvv" placeholder="123" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cardName">Cardholder Name *</label>
                                    <input type="text" id="cardName" name="cardName" required>
                                </div>
                            </div>
                            
                            <div class="checkout-actions">
                                <button type="button" class="btn-secondary" id="backToShipping">Back</button>
                                <button type="button" class="btn-primary" id="reviewOrder">Review Order</button>
                            </div>
                        </div>
                        
                        <!-- Order Review -->
                        <div class="form-section" id="reviewSection" style="display: none;">
                            <h3>Review Your Order</h3>
                            <div class="order-review">
                                <div id="orderItems" class="order-items">
                                    <!-- Order items will be loaded here -->
                                </div>
                            </div>
                            
                            <div class="checkout-actions">
                                <button type="button" class="btn-secondary" id="backToPayment">Back</button>
                                <button type="submit" class="btn-primary" id="placeOrder">Place Order</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div id="checkoutSummary" class="checkout-summary">
                        <!-- Order summary will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
