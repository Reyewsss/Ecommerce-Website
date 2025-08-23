<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Calla Noa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>
    <div class="checkout-page">
        <div class="checkout-container">
            <!-- Header -->
            <div class="checkout-header">
                <h1><i class="fas fa-shield-alt"></i> Secure Checkout</h1>
                <div class="security-badges">
                    <div class="security-badge">
                        <i class="fas fa-lock"></i>
                        <span>SSL Secured</span>
                    </div>
                    <div class="security-badge">
                        <i class="fas fa-credit-card"></i>
                        <span>Safe Payments</span>
                    </div>
                    <div class="security-badge">
                        <i class="fas fa-truck"></i>
                        <span>Fast Delivery</span>
                    </div>
                </div>
            </div>

            <div class="checkout-content">
                <!-- Main Form -->
                <div class="checkout-main">
                    <!-- Shipping Information -->
                    <div class="form-section">
                        <h3><i class="fas fa-shipping-fast"></i> Shipping Information</h3>
                        <form>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">First Name *</label>
                                    <input type="text" id="firstName" name="firstName" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name *</label>
                                    <input type="text" id="lastName" name="lastName" required>
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
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City *</label>
                                    <input type="text" id="city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="state">State *</label>
                                    <input type="text" id="state" name="state" required>
                                </div>
                                <div class="form-group">
                                    <label for="zip">ZIP Code *</label>
                                    <input type="text" id="zip" name="zip" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="country">Country *</label>
                                <select id="country" name="country" required>
                                    <option value="">Select Country</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="AU">Australia</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Shipping Method -->
                    <div class="form-section">
                        <h3><i class="fas fa-truck"></i> Shipping Method</h3>
                        <div class="shipping-methods">
                            <div class="shipping-method">
                                <input type="radio" name="shipping" value="standard" checked>
                                <div class="method-details">
                                    <div class="method-info">
                                        <h5>Standard Shipping</h5>
                                        <p>5-7 business days</p>
                                    </div>
                                    <div class="method-price">$5.99</div>
                                </div>
                            </div>
                            <div class="shipping-method">
                                <input type="radio" name="shipping" value="express">
                                <div class="method-details">
                                    <div class="method-info">
                                        <h5>Express Shipping</h5>
                                        <p>2-3 business days</p>
                                    </div>
                                    <div class="method-price">$12.99</div>
                                </div>
                            </div>
                            <div class="shipping-method">
                                <input type="radio" name="shipping" value="overnight">
                                <div class="method-details">
                                    <div class="method-info">
                                        <h5>Overnight Shipping</h5>
                                        <p>Next business day</p>
                                    </div>
                                    <div class="method-price">$24.99</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                        
                        <div class="payment-methods">
                            <div class="payment-method selected">
                                <i class="fas fa-credit-card"></i>
                                Credit Card
                            </div>
                            <div class="payment-method">
                                <i class="fab fa-paypal"></i>
                                PayPal
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cardNumber">Card Number *</label>
                            <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry">Expiry Date *</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV *</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="cardName">Name on Card *</label>
                            <input type="text" id="cardName" name="cardName" required>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="checkout-sidebar">
                    <div class="order-summary">
                        <h4>Order Summary</h4>
                        
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span>$99.99</span>
                        </div>
                        <div class="summary-item">
                            <span>Shipping</span>
                            <span>$5.99</span>
                        </div>
                        <div class="summary-item">
                            <span>Tax</span>
                            <span>$8.50</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Total</span>
                            <span>$114.48</span>
                        </div>
                        
                        <button type="button" class="btn btn-primary">
                            <i class="fas fa-lock"></i> Place Order
                        </button>
                        
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-shopping-cart"></i> Continue Shopping
                        </a>
                        
                        <div class="trust-badges">
                            <div class="trust-badge">
                                <i class="fas fa-undo"></i>
                                <span>30-Day Returns</span>
                            </div>
                            <div class="trust-badge">
                                <i class="fas fa-shipping-fast"></i>
                                <span>Free Shipping Over $50</span>
                            </div>
                            <div class="trust-badge">
                                <i class="fas fa-headset"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple interactivity
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        document.querySelectorAll('.shipping-method').forEach(method => {
            method.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });
    </script>
</body>
</html>
