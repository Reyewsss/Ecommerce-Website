<?php
header('Content-Type: application/json');
require_once '../includes/session.php';
require_once '../config/database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'place_order':
        placeOrder();
        break;
    case 'create_order':
        createOrder();
        break;
    case 'get_order':
        getOrder();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function placeOrder() {
    global $db;
    
    try {
        // Get cart items
        $user_id = $_SESSION['user_id'] ?? null;
        $items = [];
        $total = 0;
        
        if ($user_id) {
            $cart_items = $db->fetchAll(
                "SELECT c.*, p.name, p.price FROM cart c 
                 JOIN products p ON c.product_id = p.id 
                 WHERE c.user_id = ?", 
                [$user_id]
            );
            
            foreach ($cart_items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                $items[] = $item;
            }
        } else {
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $product = $db->fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
                    if ($product) {
                        $subtotal = $product['price'] * $quantity;
                        $total += $subtotal;
                        $items[] = [
                            'product_id' => $product_id,
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'quantity' => $quantity
                        ];
                    }
                }
            }
        }
        
        if (empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Cart is empty']);
            return;
        }
        
        // Get form data
        $shipping = $_POST['shipping'];
        $payment = $_POST['payment'];
        
        // Validate required fields
        $required_shipping = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zipCode'];
        foreach ($required_shipping as $field) {
            if (empty($shipping[$field])) {
                echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                return;
            }
        }
        
        // Begin transaction
        $db->beginTransaction();
        
        // Create order
        $order_number = 'ORD' . time() . rand(100, 999);
        $subtotal = $total * 0.9;
        $tax = $total * 0.1;
        
        $order_id = $db->insert(
            "INSERT INTO orders (user_id, order_number, total_amount, subtotal, tax_amount, status, 
             shipping_first_name, shipping_last_name, shipping_email, shipping_phone, 
             shipping_address, shipping_city, shipping_state, shipping_zip, 
             payment_method, created_at) 
             VALUES (?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $user_id,
                $order_number,
                $total,
                $subtotal,
                $tax,
                $shipping['firstName'],
                $shipping['lastName'],
                $shipping['email'],
                $shipping['phone'],
                $shipping['address'],
                $shipping['city'],
                $shipping['state'],
                $shipping['zipCode'],
                $payment['method']
            ]
        );
        
        // Add order items
        foreach ($items as $item) {
            $db->query(
                "INSERT INTO order_items (order_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)",
                [
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                    $item['price'] * $item['quantity']
                ]
            );
        }
        
        // Clear cart
        if ($user_id) {
            $db->query("DELETE FROM cart WHERE user_id = ?", [$user_id]);
        } else {
            unset($_SESSION['cart']);
        }
        
        // Commit transaction
        $db->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Order placed successfully',
            'order_id' => $order_id,
            'order_number' => $order_number
        ]);
        
    } catch (Exception $e) {
        $db->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to place order: ' . $e->getMessage()]);
    }
}

function getOrder() {
    global $db;
    
    $order_id = intval($_GET['order_id'] ?? 0);
    $user_id = $_SESSION['user_id'] ?? null;
    
    if (!$order_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
        return;
    }
    
    // Get order details
    $where_clause = "WHERE id = ?";
    $params = [$order_id];
    
    if ($user_id) {
        $where_clause .= " AND user_id = ?";
        $params[] = $user_id;
    }
    
    $order = $db->fetchOne("SELECT * FROM orders $where_clause", $params);
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        return;
    }
    
    // Get order items
    $items = $db->fetchAll(
        "SELECT oi.*, p.name, p.image FROM order_items oi 
         JOIN products p ON oi.product_id = p.id 
         WHERE oi.order_id = ?",
        [$order_id]
    );
    
    $order['items'] = $items;
    
    echo json_encode(['success' => true, 'order' => $order]);
}

function createOrder() {
    global $db;
    
    try {
        // Parse order data from JSON
        $orderData = json_decode($_POST['orderData'], true);
        
        if (!$orderData) {
            echo json_encode(['success' => false, 'message' => 'Invalid order data']);
            return;
        }
        
        $shipping = $orderData['shipping'];
        $payment = $orderData['payment'];
        $items = $orderData['items'];
        $promoCode = $orderData['promoCode'] ?? '';
        
        // Validate required fields
        $requiredShipping = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zipCode'];
        foreach ($requiredShipping as $field) {
            if (empty($shipping[$field])) {
                echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                return;
            }
        }
        
        if (empty($items)) {
            echo json_encode(['success' => false, 'message' => 'No items in order']);
            return;
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Calculate shipping
        $shippingCost = 0;
        switch ($shipping['shippingMethod']) {
            case 'express':
                $shippingCost = 9.99;
                break;
            case 'overnight':
                $shippingCost = 19.99;
                break;
            default:
                $shippingCost = 0;
        }
        
        // Calculate tax (8%)
        $taxAmount = $subtotal * 0.08;
        
        // Apply promo discount
        $discountAmount = 0;
        $promoApplied = '';
        
        if ($promoCode) {
            $validCodes = [
                'SAVE10' => ['discount' => 0.10, 'type' => 'percentage'],
                'WELCOME' => ['discount' => 5.00, 'type' => 'fixed'],
                'BEAUTY20' => ['discount' => 0.20, 'type' => 'percentage']
            ];
            
            if (isset($validCodes[strtoupper($promoCode)])) {
                $discount = $validCodes[strtoupper($promoCode)];
                if ($discount['type'] === 'percentage') {
                    $discountAmount = $subtotal * $discount['discount'];
                } else {
                    $discountAmount = $discount['discount'];
                }
                $promoApplied = strtoupper($promoCode);
            }
        }
        
        $totalAmount = $subtotal + $shippingCost + $taxAmount - $discountAmount;
        
        // Begin transaction
        $db->beginTransaction();
        
        // Generate order number
        $orderNumber = 'ORD' . date('Ymd') . rand(1000, 9999);
        
        // Insert order
        $orderId = $db->insert(
            "INSERT INTO orders (
                user_id, order_number, total_amount, subtotal, tax_amount, 
                shipping_amount, discount_amount, promo_code, status, 
                shipping_first_name, shipping_last_name, shipping_email, shipping_phone,
                shipping_address, shipping_city, shipping_state, shipping_zip,
                shipping_method, payment_method, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $_SESSION['user_id'] ?? null,
                $orderNumber,
                $totalAmount,
                $subtotal,
                $taxAmount,
                $shippingCost,
                $discountAmount,
                $promoApplied,
                $shipping['firstName'],
                $shipping['lastName'],
                $shipping['email'],
                $shipping['phone'],
                $shipping['address'],
                $shipping['city'],
                $shipping['state'],
                $shipping['zipCode'],
                $shipping['shippingMethod'],
                $payment['method']
            ]
        );
        
        // Insert order items
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $db->query(
                "INSERT INTO order_items (order_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)",
                [$orderId, $item['id'], $item['quantity'], $item['price'], $itemTotal]
            );
        }
        
        // Store payment information (in a real app, this should be encrypted or tokenized)
        if ($payment['method'] === 'credit_card') {
            $maskedCard = '****-****-****-' . substr($payment['cardNumber'], -4);
            $db->query(
                "INSERT INTO order_payments (order_id, payment_method, card_last_four, card_type, created_at) 
                 VALUES (?, ?, ?, 'credit_card', NOW())",
                [$orderId, $payment['method'], substr($payment['cardNumber'], -4)]
            );
        }
        
        // Clear user's cart if logged in
        if (isset($_SESSION['user_id'])) {
            $db->query("DELETE FROM cart WHERE user_id = ?", [$_SESSION['user_id']]);
        }
        
        // Commit transaction
        $db->commit();
        
        // Send confirmation email (in a real app)
        // sendOrderConfirmationEmail($orderId, $shipping['email']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => $orderId,
            'order_number' => $orderNumber,
            'total' => $totalAmount
        ]);
        
    } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollback();
        }
        
        error_log('Order creation failed: ' . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to process order. Please try again.'
        ]);
    }
}
?>
