<?php
// admin/index.php - Admin dashboard

// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once '../includes/dbConnection.php';

try {
    // Get stats for dashboard
    
    // Total products
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $total_products = $stmt->fetchColumn();
    
    // Total orders
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $total_orders = $stmt->fetchColumn();
    
    // Total customers
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $total_customers = $stmt->fetchColumn();
    
    // Total revenue
    $stmt = $pdo->query("SELECT SUM(total) FROM orders WHERE status != 'cancelled'");
    $total_revenue = $stmt->fetchColumn();
    
    // Recent orders
    $stmt = $pdo->query("SELECT o.*, u.username, u.email 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        ORDER BY o.order_date DESC LIMIT 5");
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Low stock products
    $stmt = $pdo->query("SELECT * FROM products WHERE stock <= 5 ORDER BY stock ASC LIMIT 5");
    $low_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Get admin info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'admin'");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <span>Welcome, <?= htmlspecialchars($admin['username']) ?></span>
                    <a href="logout.php" class="btn btn-sm">Logout</a>
                </div>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-value"><?= $total_products ?></div>
                    <div class="stat-label">Products</div>
                    <a href="products.php" class="stat-link">Manage</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?= $total_orders ?></div>
                    <div class="stat-label">Orders</div>
                    <a href="orders.php" class="stat-link">Manage</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?= $total_customers ?></div>
                    <div class="stat-label">Customers</div>
                    <a href="customers.php" class="stat-link">Manage</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value">$<?= number_format($total_revenue, 2) ?></div>
                    <div class="stat-label">Revenue</div>
                    <a href="reports.php" class="stat-link">Reports</a>
                </div>
            </div>
            
            <div class="dashboard-row">
                <div class="dashboard-column">
                    <div class="dashboard-card">
                        <h2>Recent Orders</h2>
                        <?php if (count($recent_orders) > 0): ?>
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td><?= $order['id'] ?></td>
                                            <td><?= htmlspecialchars($order['username']) ?></td>
                                            <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                            <td>$<?= number_format($order['total'], 2) ?></td>
                                            <td><span class="status-badge status-<?= strtolower($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                                            <td><a href="orders.php?id=<?= $order['id'] ?>" class="btn btn-sm">View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="orders.php" class="btn btn-link">View All Orders</a>
                        <?php else: ?>
                            <p>No recent orders found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="dashboard-column">
                    <div class="dashboard-card">
                        <h2>Low Stock Products</h2>
                        <?php if (count($low_stock) > 0): ?>
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($low_stock as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['name']) ?></td>
                                            <td><?= $product['stock'] ?></td>
                                            <td><a href="inventory.php?id=<?= $product['id'] ?>" class="btn btn-sm">Update</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="inventory.php" class="btn btn-link">Manage Inventory</a>
                        <?php else: ?>
                            <p>No low stock products found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>