<?php
header('Content-Type: application/json');
require_once '../includes/session.php';
require_once '../config/database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'place_order':
        placeOrder();
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
?>
