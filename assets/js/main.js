// Simple Cart with Local Storage
$(document).ready(function() {
    // Header scroll effect
    $(window).on('scroll', function() {
        const scrollTop = $(window).scrollTop();
        const header = $('.header');
        
        if (scrollTop > 50) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }
    });
    
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

// Auth Modal Controls
$(document).ready(function() {
    // Check URL parameters to auto-open modals
    const urlParams = new URLSearchParams(window.location.search);
    const modal = urlParams.get('modal');
    
    if (modal === 'login') {
        openAuthModal('loginModal');
        // Remove the parameter from URL without refreshing
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (modal === 'register') {
        openAuthModal('registerModal');
        // Remove the parameter from URL without refreshing
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Open Login Modal
    $('#openLoginModal').on('click', function() {
        openAuthModal('loginModal');
    });

    // Open Register Modal
    $('#openRegisterModal').on('click', function() {
        openAuthModal('registerModal');
    });

    // Get Started Button - opens register modal by default
    $('#getStartedBtn').on('click', function() {
        openAuthModal('registerModal');
    });

    // Close modals
    $('#closeLogin, #loginOverlay').on('click', function() {
        closeAuthModal('loginModal');
    });

    $('#closeRegister, #registerOverlay').on('click', function() {
        closeAuthModal('registerModal');
    });

    // Switch between login and register
    $('#switchToRegister').on('click', function(e) {
        e.preventDefault();
        closeAuthModal('loginModal');
        setTimeout(() => openAuthModal('registerModal'), 300);
    });

    $('#switchToLogin').on('click', function(e) {
        e.preventDefault();
        closeAuthModal('registerModal');
        setTimeout(() => openAuthModal('loginModal'), 300);
    });

    // Handle form submissions
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        handleLogin();
    });

    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        handleRegister();
    });

    // Close modal on Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            if ($('#loginModal').hasClass('active')) {
                closeAuthModal('loginModal');
            }
            if ($('#registerModal').hasClass('active')) {
                closeAuthModal('registerModal');
            }
        }
    });
});

function openAuthModal(modalId) {
    $('body').addClass('modal-open');
    $('#' + modalId).addClass('active');
    
    // Focus on the first input field for better UX
    setTimeout(() => {
        $('#' + modalId).find('input:first').focus();
    }, 300);
}

function closeAuthModal(modalId) {
    $('body').removeClass('modal-open');
    $('#' + modalId).removeClass('active');
    
    // Clear form data when closing
    $('#' + modalId).find('form')[0].reset();
    clearFormErrors();
}

function handleLogin() {
    const form = $('#loginForm');
    const submitBtn = form.find('button[type="submit"]');
    const formData = {
        email: $('#loginEmail').val(),
        password: $('#loginPassword').val()
    };

    // Basic validation
    if (!validateEmail(formData.email)) {
        showFieldError('loginEmail', 'Please enter a valid email address');
        return;
    }

    if (!formData.password) {
        showFieldError('loginPassword', 'Password is required');
        return;
    }

    // Clear any existing errors
    clearFormErrors();

    // Show loading state
    submitBtn.prop('disabled', true).addClass('btn-loading').text('Logging in...');

    $.ajax({
        url: 'api/auth.php',
        type: 'POST',
        data: {
            action: 'login',
            ...formData
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('Login successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(response.message || 'Login failed', 'error');
            }
        },
        error: function() {
            showNotification('An error occurred. Please try again.', 'error');
        },
        complete: function() {
            // Reset loading state
            submitBtn.prop('disabled', false).removeClass('btn-loading').text('Login');
        }
    });
}

function handleRegister() {
    const form = $('#registerForm');
    const submitBtn = form.find('button[type="submit"]');
    const formData = {
        firstName: $('#registerFirstName').val().trim(),
        lastName: $('#registerLastName').val().trim(),
        email: $('#registerEmail').val().trim(),
        password: $('#registerPassword').val(),
        confirmPassword: $('#registerConfirmPassword').val()
    };

    // Clear any existing errors
    clearFormErrors();

    // Validation
    let hasErrors = false;

    if (!formData.firstName) {
        showFieldError('registerFirstName', 'First name is required');
        hasErrors = true;
    }

    if (!formData.lastName) {
        showFieldError('registerLastName', 'Last name is required');
        hasErrors = true;
    }

    if (!validateEmail(formData.email)) {
        showFieldError('registerEmail', 'Please enter a valid email address');
        hasErrors = true;
    }

    if (formData.password.length < 6) {
        showFieldError('registerPassword', 'Password must be at least 6 characters long');
        hasErrors = true;
    }

    if (formData.password !== formData.confirmPassword) {
        showFieldError('registerConfirmPassword', 'Passwords do not match');
        hasErrors = true;
    }

    if (hasErrors) return;

    // Show loading state
    submitBtn.prop('disabled', true).addClass('btn-loading').text('Creating Account...');

    $.ajax({
        url: 'api/auth.php',
        type: 'POST',
        data: {
            action: 'register',
            ...formData
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('Registration successful! Please login.', 'success');
                closeAuthModal('registerModal');
                setTimeout(() => openAuthModal('loginModal'), 1000);
            } else {
                showNotification(response.message || 'Registration failed', 'error');
            }
        },
        error: function() {
            showNotification('An error occurred. Please try again.', 'error');
        },
        complete: function() {
            // Reset loading state
            submitBtn.prop('disabled', false).removeClass('btn-loading').text('Register');
        }
    });
}

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(fieldId, message) {
    const field = $('#' + fieldId);
    const formGroup = field.closest('.form-group');
    
    formGroup.addClass('error');
    
    // Remove existing error message
    formGroup.find('.error-message').remove();
    
    // Add new error message
    field.after(`<div class="error-message">${message}</div>`);
}

function clearFormErrors() {
    $('.form-group').removeClass('error success');
    $('.error-message').remove();
}

// Checkout Page Functionality
$(document).ready(function() {
    if (window.location.pathname.includes('checkout.php')) {
        initCheckout();
    }
});

function initCheckout() {
    let currentStep = 1;
    const totalSteps = 3;
    
    // Load cart summary
    loadCheckoutSummary();
    
    // Form validation
    initFormValidation();
    
    // Step navigation
    $('#continueToPayment').on('click', function() {
        if (validateShippingForm()) {
            nextStep();
        }
    });
    
    $('#backToShipping').on('click', function() {
        previousStep();
    });
    
    $('#reviewOrder').on('click', function() {
        if (validatePaymentForm()) {
            populateOrderReview();
            nextStep();
        }
    });
    
    $('#backToPayment').on('click', function() {
        previousStep();
    });
    
    // Payment method switching
    $('input[name="paymentMethod"]').on('change', function() {
        togglePaymentForm();
    });
    
    // Card formatting
    initCardFormatting();
    
    // Promo code
    $('#applyPromo').on('click', function() {
        applyPromoCode();
    });
    
    // Form submission
    $('#checkoutForm').on('submit', function(e) {
        e.preventDefault();
        if (validateReviewForm()) {
            processOrder();
        }
    });
    
    // Shipping method change
    $('input[name="shippingMethod"]').on('change', function() {
        updateShippingCost();
    });
    
    function nextStep() {
        if (currentStep < totalSteps) {
            // Hide current step
            $(`.form-section:nth-child(${currentStep})`).removeClass('active');
            $('.step').eq(currentStep - 1).removeClass('active');
            
            currentStep++;
            
            // Show next step
            $(`.form-section:nth-child(${currentStep})`).addClass('active');
            $('.step').eq(currentStep - 1).addClass('active');
            
            // Scroll to top
            $('html, body').animate({ scrollTop: $('.checkout-form').offset().top - 100 }, 500);
        }
    }
    
    function previousStep() {
        if (currentStep > 1) {
            // Hide current step
            $(`.form-section:nth-child(${currentStep})`).removeClass('active');
            $('.step').eq(currentStep - 1).removeClass('active');
            
            currentStep--;
            
            // Show previous step
            $(`.form-section:nth-child(${currentStep})`).addClass('active');
            $('.step').eq(currentStep - 1).addClass('active');
            
            // Scroll to top
            $('html, body').animate({ scrollTop: $('.checkout-form').offset().top - 100 }, 500);
        }
    }
    
    function validateShippingForm() {
        let isValid = true;
        const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zipCode'];
        
        clearFormErrors();
        
        requiredFields.forEach(field => {
            const value = $(`#${field}`).val().trim();
            if (!value) {
                showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                // Specific validations
                if (field === 'email' && !validateEmail(value)) {
                    showFieldError(field, 'Please enter a valid email address');
                    isValid = false;
                } else if (field === 'phone' && !validatePhone(value)) {
                    showFieldError(field, 'Please enter a valid phone number');
                    isValid = false;
                } else if (field === 'zipCode' && !validateZipCode(value)) {
                    showFieldError(field, 'Please enter a valid ZIP code');
                    isValid = false;
                } else {
                    $(`#${field}`).closest('.form-group').addClass('success');
                }
            }
        });
        
        return isValid;
    }
    
    function validatePaymentForm() {
        const paymentMethod = $('input[name="paymentMethod"]:checked').val();
        
        if (paymentMethod === 'credit_card') {
            return validateCreditCardForm();
        } else if (paymentMethod === 'paypal') {
            return true; // PayPal validation handled by PayPal
        }
        
        return false;
    }
    
    function validateCreditCardForm() {
        let isValid = true;
        const requiredFields = ['cardNumber', 'expiryDate', 'cvv', 'cardName'];
        
        clearFormErrors();
        
        requiredFields.forEach(field => {
            const value = $(`#${field}`).val().trim();
            if (!value) {
                showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                // Specific validations
                if (field === 'cardNumber' && !validateCardNumber(value)) {
                    showFieldError(field, 'Please enter a valid card number');
                    isValid = false;
                } else if (field === 'expiryDate' && !validateExpiryDate(value)) {
                    showFieldError(field, 'Please enter a valid expiry date (MM/YY)');
                    isValid = false;
                } else if (field === 'cvv' && !validateCVV(value)) {
                    showFieldError(field, 'Please enter a valid CVV');
                    isValid = false;
                } else {
                    $(`#${field}`).closest('.form-group').addClass('success');
                }
            }
        });
        
        return isValid;
    }
    
    function validateReviewForm() {
        const agreeTerms = $('#agreeTerms').is(':checked');
        
        if (!agreeTerms) {
            showFieldError('agreeTerms', 'You must agree to the terms and conditions');
            return false;
        }
        
        return true;
    }
    
    function initFormValidation() {
        // Real-time validation
        $('#email').on('blur', function() {
            const email = $(this).val().trim();
            if (email && !validateEmail(email)) {
                showFieldError('email', 'Please enter a valid email address');
            } else if (email) {
                $(this).closest('.form-group').removeClass('error').addClass('success');
            }
        });
        
        $('#phone').on('blur', function() {
            const phone = $(this).val().trim();
            if (phone && !validatePhone(phone)) {
                showFieldError('phone', 'Please enter a valid phone number');
            } else if (phone) {
                $(this).closest('.form-group').removeClass('error').addClass('success');
            }
        });
        
        $('#zipCode').on('blur', function() {
            const zipCode = $(this).val().trim();
            if (zipCode && !validateZipCode(zipCode)) {
                showFieldError('zipCode', 'Please enter a valid ZIP code');
            } else if (zipCode) {
                $(this).closest('.form-group').removeClass('error').addClass('success');
            }
        });
    }
    
    function validatePhone(phone) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''));
    }
    
    function validateZipCode(zipCode) {
        const zipRegex = /^\d{5}(-\d{4})?$/;
        return zipRegex.test(zipCode);
    }
    
    function validateCardNumber(cardNumber) {
        const cleaned = cardNumber.replace(/\s/g, '');
        return /^\d{13,19}$/.test(cleaned) && luhnCheck(cleaned);
    }
    
    function validateExpiryDate(expiry) {
        const regex = /^(0[1-9]|1[0-2])\/\d{2}$/;
        if (!regex.test(expiry)) return false;
        
        const [month, year] = expiry.split('/').map(num => parseInt(num));
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear() % 100;
        const currentMonth = currentDate.getMonth() + 1;
        
        return year > currentYear || (year === currentYear && month >= currentMonth);
    }
    
    function validateCVV(cvv) {
        return /^\d{3,4}$/.test(cvv);
    }
    
    function luhnCheck(cardNumber) {
        let sum = 0;
        let isEven = false;
        
        for (let i = cardNumber.length - 1; i >= 0; i--) {
            let digit = parseInt(cardNumber[i]);
            
            if (isEven) {
                digit *= 2;
                if (digit > 9) {
                    digit -= 9;
                }
            }
            
            sum += digit;
            isEven = !isEven;
        }
        
        return sum % 10 === 0;
    }
    
    function initCardFormatting() {
        // Card number formatting
        $('#cardNumber').on('input', function() {
            let value = $(this).val().replace(/\s/g, '');
            let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
            if (formattedValue !== $(this).val()) {
                $(this).val(formattedValue);
            }
        });
        
        // Expiry date formatting
        $('#expiryDate').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            $(this).val(value);
        });
        
        // CVV numeric only
        $('#cvv').on('input', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
    }
    
    function togglePaymentForm() {
        const paymentMethod = $('input[name="paymentMethod"]:checked').val();
        
        if (paymentMethod === 'credit_card') {
            $('#creditCardForm').show();
            $('#paypalForm').hide();
        } else if (paymentMethod === 'paypal') {
            $('#creditCardForm').hide();
            $('#paypalForm').show();
        }
    }
    
    function populateOrderReview() {
        // Shipping address review
        const shippingInfo = {
            name: `${$('#firstName').val()} ${$('#lastName').val()}`,
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val(),
            city: $('#city').val(),
            state: $('#state').val(),
            zipCode: $('#zipCode').val(),
            shippingMethod: $('input[name="shippingMethod"]:checked').next('label').find('strong').text()
        };
        
        $('#shippingReview').html(`
            <p><strong>${shippingInfo.name}</strong></p>
            <p>${shippingInfo.address}</p>
            <p>${shippingInfo.city}, ${shippingInfo.state} ${shippingInfo.zipCode}</p>
            <p>Phone: ${shippingInfo.phone}</p>
            <p>Email: ${shippingInfo.email}</p>
            <p>Shipping: ${shippingInfo.shippingMethod}</p>
        `);
        
        // Payment method review
        const paymentMethod = $('input[name="paymentMethod"]:checked').val();
        let paymentInfo = '';
        
        if (paymentMethod === 'credit_card') {
            const cardNumber = $('#cardNumber').val();
            const maskedCard = '**** **** **** ' + cardNumber.slice(-4);
            paymentInfo = `<p>Credit Card ending in ${cardNumber.slice(-4)}</p>`;
        } else {
            paymentInfo = '<p>PayPal</p>';
        }
        
        $('#paymentReview').html(paymentInfo);
        
        // Order items
        loadOrderItems();
    }
    
    function loadOrderItems() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let itemsHtml = '';
        
        cart.forEach(item => {
            const bgColor = getRandomColor();
            itemsHtml += `
                <div class="order-item">
                    <div class="item-image-placeholder" style="width: 60px; height: 60px; background: linear-gradient(135deg, ${bgColor}, ${bgColor}99); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.7rem; border-radius: 4px; font-weight: bold; text-align: center;">
                        ${item.name.substring(0, 2).toUpperCase()}
                    </div>
                    <div class="item-details">
                        <h4>${item.name}</h4>
                        <p>Quantity: ${item.quantity}</p>
                        <p class="item-price">$${(item.price * item.quantity).toFixed(2)}</p>
                    </div>
                </div>
            `;
        });
        
        $('#orderItems').html(itemsHtml);
    }
    
    function loadCheckoutSummary() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let itemsHtml = '';
        let subtotal = 0;
        
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            const bgColor = getRandomColor();
            
            itemsHtml += `
                <div class="checkout-summary-item">
                    <div class="item-image-placeholder" style="width: 50px; height: 50px; background: linear-gradient(135deg, ${bgColor}, ${bgColor}99); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.6rem; border-radius: 4px; font-weight: bold; text-align: center; flex-shrink: 0;">
                        ${item.name.substring(0, 2).toUpperCase()}
                    </div>
                    <div class="item-info">
                        <h4>${item.name}</h4>
                        <p>Qty: ${item.quantity} × $${item.price.toFixed(2)}</p>
                    </div>
                    <div class="item-price">$${itemTotal.toFixed(2)}</div>
                </div>
            `;
        });
        
        const shippingCost = getShippingCost();
        const tax = subtotal * 0.08; // 8% tax
        const total = subtotal + shippingCost + tax;
        
        const summaryHtml = `
            ${itemsHtml}
            <div class="summary-totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>$${subtotal.toFixed(2)}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span id="shippingCostDisplay">${shippingCost === 0 ? 'Free' : '$' + shippingCost.toFixed(2)}</span>
                </div>
                <div class="total-row">
                    <span>Tax:</span>
                    <span>$${tax.toFixed(2)}</span>
                </div>
                <div class="total-row total-final">
                    <span><strong>Total:</strong></span>
                    <span><strong id="finalTotal">$${total.toFixed(2)}</strong></span>
                </div>
            </div>
        `;
        
        $('#checkoutSummary').html(summaryHtml);
    }
    
    function getShippingCost() {
        const shippingMethod = $('input[name="shippingMethod"]:checked').val();
        
        switch (shippingMethod) {
            case 'express': return 9.99;
            case 'overnight': return 19.99;
            default: return 0; // standard shipping is free
        }
    }
    
    function updateShippingCost() {
        const shippingCost = getShippingCost();
        $('#shippingCostDisplay').text(shippingCost === 0 ? 'Free' : '$' + shippingCost.toFixed(2));
        
        // Recalculate total
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
        });
        
        const tax = subtotal * 0.08;
        const total = subtotal + shippingCost + tax;
        $('#finalTotal').text('$' + total.toFixed(2));
    }
    
    function applyPromoCode() {
        const promoCode = $('#promoCode').val().trim().toUpperCase();
        const promoMessage = $('#promoMessage');
        
        if (!promoCode) {
            promoMessage.removeClass('success').addClass('error').text('Please enter a promo code');
            return;
        }
        
        // Simulate promo code validation
        const validCodes = {
            'SAVE10': { discount: 0.10, type: 'percentage' },
            'WELCOME': { discount: 5.00, type: 'fixed' },
            'BEAUTY20': { discount: 0.20, type: 'percentage' }
        };
        
        if (validCodes[promoCode]) {
            const discount = validCodes[promoCode];
            promoMessage.removeClass('error').addClass('success').text(`Promo code applied! You saved ${discount.type === 'percentage' ? (discount.discount * 100) + '%' : '$' + discount.discount.toFixed(2)}`);
            // Apply discount logic here
        } else {
            promoMessage.removeClass('success').addClass('error').text('Invalid promo code');
        }
    }
    
    function processOrder() {
        // Show loading
        $('#loadingOverlay').addClass('active');
        
        // Gather all form data
        const orderData = {
            shipping: {
                firstName: $('#firstName').val(),
                lastName: $('#lastName').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                address: $('#address').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                zipCode: $('#zipCode').val(),
                shippingMethod: $('input[name="shippingMethod"]:checked').val()
            },
            payment: {
                method: $('input[name="paymentMethod"]:checked').val(),
                cardNumber: $('#cardNumber').val(),
                expiryDate: $('#expiryDate').val(),
                cvv: $('#cvv').val(),
                cardName: $('#cardName').val()
            },
            items: JSON.parse(localStorage.getItem('cart')) || [],
            promoCode: $('#promoCode').val()
        };
        
        // Simulate order processing
        $.ajax({
            url: 'api/orders.php',
            type: 'POST',
            data: {
                action: 'create_order',
                orderData: JSON.stringify(orderData)
            },
            dataType: 'json',
            success: function(response) {
                $('#loadingOverlay').removeClass('active');
                
                if (response.success) {
                    // Clear cart
                    localStorage.removeItem('cart');
                    updateCartCount();
                    
                    // Redirect to confirmation page
                    window.location.href = `order-confirmation.php?order_id=${response.order_id}`;
                } else {
                    showNotification(response.message || 'Order failed. Please try again.', 'error');
                }
            },
            error: function() {
                $('#loadingOverlay').removeClass('active');
                showNotification('An error occurred while processing your order. Please try again.', 'error');
            }
        });
    }
}

