<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$action = $_GET['action'] ?? 'all';

switch ($action) {
    case 'all':
        getAllProducts();
        break;
    case 'featured':
        getFeaturedProducts();
        break;
    case 'by_category':
        getProductsByCategory();
        break;
    case 'search':
        searchProducts();
        break;
    case 'filter':
        filterProducts();
        break;
    case 'single':
        getProduct();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getAllProducts() {
    global $db;
    
    $category = $_GET['category'] ?? '';
    $search = $_GET['search'] ?? '';
    $limit = intval($_GET['limit'] ?? 20);
    $offset = intval($_GET['offset'] ?? 0);
    
    $sql = "SELECT * FROM products WHERE status = 'active'";
    $params = [];
    
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    if ($search) {
        $sql .= " AND (name LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $products = $db->fetchAll($sql, $params);
    
    echo json_encode(['success' => true, 'products' => $products]);
}

function getFeaturedProducts() {
    global $db;
    
    $limit = intval($_GET['limit'] ?? 8);
    
    $products = $db->fetchAll(
        "SELECT * FROM products WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT ?",
        [$limit]
    );
    
    echo json_encode(['success' => true, 'products' => $products]);
}

function getProductsByCategory() {
    global $db;
    
    $category = $_GET['category'] ?? '';
    $limit = intval($_GET['limit'] ?? 20);
    
    if (!$category) {
        echo json_encode(['success' => false, 'message' => 'Category is required']);
        return;
    }
    
    $products = $db->fetchAll(
        "SELECT * FROM products WHERE status = 'active' AND category = ? ORDER BY name LIMIT ?",
        [$category, $limit]
    );
    
    echo json_encode(['success' => true, 'products' => $products]);
}

function searchProducts() {
    global $db;
    
    $query = $_GET['query'] ?? '';
    $limit = intval($_GET['limit'] ?? 20);
    
    if (!$query) {
        echo json_encode(['success' => false, 'message' => 'Search query is required']);
        return;
    }
    
    $products = $db->fetchAll(
        "SELECT * FROM products WHERE status = 'active' AND (name LIKE ? OR description LIKE ?) ORDER BY name LIMIT ?",
        ["%$query%", "%$query%", $limit]
    );
    
    echo json_encode(['success' => true, 'products' => $products]);
}

function filterProducts() {
    global $db;
    
    $min_price = floatval($_GET['min_price'] ?? 0);
    $max_price = floatval($_GET['max_price'] ?? 9999);
    $category = $_GET['category'] ?? '';
    $limit = intval($_GET['limit'] ?? 20);
    
    $sql = "SELECT * FROM products WHERE status = 'active' AND price BETWEEN ? AND ?";
    $params = [$min_price, $max_price];
    
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY price LIMIT ?";
    $params[] = $limit;
    
    $products = $db->fetchAll($sql, $params);
    
    echo json_encode(['success' => true, 'products' => $products]);
}

function getProduct() {
    global $db;
    
    $id = intval($_GET['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        return;
    }
    
    $product = $db->fetchOne("SELECT * FROM products WHERE id = ? AND status = 'active'", [$id]);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        return;
    }
    
    echo json_encode(['success' => true, 'product' => $product]);
}
?>
