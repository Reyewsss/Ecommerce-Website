<?php
require_once '../includes/session.php';
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user information
$user = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$user_id]);

// Get recent orders
$recentOrders = $db->fetchAll(
    "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
    [$user_id]
);

$page_title = 'My Account';
include_once '../includes/header.php';
?>

<main class="user-dashboard">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="user-sidebar">
                    <div class="user-info">
                        <h4><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h4>
                        <p><?php echo $user['email']; ?></p>
                    </div>
                    
                    <nav class="user-menu">
                        <ul>
                            <li><a href="dashboard.php" class="active">Dashboard</a></li>
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="orders.php">Orders</a></li>
                            <li><a href="addresses.php">Addresses</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="dashboard-content">
                    <h1>Dashboard</h1>
                    
                    <div class="dashboard-stats">
                        <div class="stat-card">
                            <h3><?php echo count($recentOrders); ?></h3>
                            <p>Total Orders</p>
                        </div>
                        <div class="stat-card">
                            <h3><?php echo $user['status']; ?></h3>
                            <p>Account Status</p>
                        </div>
                        <div class="stat-card">
                            <h3><?php echo date('M Y', strtotime($user['created_at'])); ?></h3>
                            <p>Member Since</p>
                        </div>
                    </div>
                    
                    <div class="recent-orders">
                        <h2>Recent Orders</h2>
                        <?php if (empty($recentOrders)): ?>
                            <p>No orders found. <a href="../products.php">Start shopping</a></p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td><?php echo $order['order_number']; ?></td>
                                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td>
                                                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.user-dashboard {
    padding: 80px 0;
    min-height: calc(100vh - 160px);
}

.user-sidebar {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.user-info {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.user-menu ul {
    list-style: none;
    padding: 0;
}

.user-menu li {
    margin-bottom: 10px;
}

.user-menu a {
    display: block;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s;
}

.user-menu a:hover,
.user-menu a.active {
    background: #ff6b9d;
    color: white;
}

.dashboard-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.dashboard-content .stat-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.dashboard-content .stat-card h3 {
    font-size: 1.5rem;
    color: #ff6b9d;
    margin-bottom: 5px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #d1ecf1; color: #0c5460; }
.status-shipped { background: #d1ecf1; color: #0c5460; }
.status-delivered { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
</style>

<?php include_once '../includes/footer.php'; ?>
