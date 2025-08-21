<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Calla Noa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<style>
    /* Simple static checkout styles */
    .checkout-page {
            padding: 2rem 0;
            background-color: #f8f9fa;
            min-height: calc(100vh - 100px);
        }
        
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .checkout-header {
            text-align: center;
            margin-bottom: 3rem;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .checkout-header h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        
        .security-badges {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #28a745;
            font-size: 0.9rem;
        }
        
        .checkout-content {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }
        
        .checkout-main {
            flex: 2;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .checkout-sidebar {
            flex: 1;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h3 {
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .shipping-methods {
            margin: 1.5rem 0;
        }
        
        .shipping-method {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        
        .shipping-method:hover {
            border-color: #007bff;
        }
        
        .shipping-method input {
            margin-right: 0.5rem;
        }
        
        .method-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .method-info h5 {
            margin: 0;
            color: #333;
        }
        
        .method-info p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .method-price {
            font-weight: bold;
            color: #007bff;
        }
        
        .payment-methods {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .payment-method {
            flex: 1;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-method.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        
        .payment-method i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .order-summary h4 {
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.25rem 0;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 1.2rem;
            padding: 1rem 0;
            border-top: 2px solid #f0f0f0;
            margin-top: 1rem;
        }
        
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 1.1rem;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .trust-badges {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }
        
        .trust-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: #28a745;
            font-size: 0.9rem;
        }
        
        .btn-continue-shopping {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 1rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-continue-shopping:hover {
            background-color: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            .checkout-content {
                flex-direction: column;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .payment-methods {
                flex-direction: column;
            }
            
            .security-badges {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>

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
                        
                        <button type="button" class="btn-primary">
                            <i class="fas fa-lock"></i> Place Order
                        </button>
                        
                        <a href="index.php" class="btn-continue-shopping">
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
