<?php
// admin/orders.php - Order management

// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once '../includes/dbConnection.php';

// Handle order status update
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['status'])) {
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['order_id']]);
        $success_message = "Order status updated successfully.";
    } catch (PDOException $e) {
        $error = "Error updating order: " . $e->getMessage();
    }
}

// Get specific order details if ID is provided
$order_details = null;
$order_items = null;
if (isset($_GET['id'])) {
    try {
        // Get order details
        $stmt = $pdo->prepare("SELECT o.*, u.username, u.email, u.phone, u.address 
                              FROM orders o 
                              JOIN users u ON o.user_id = u.id 
                              WHERE o.id = ?");
        $stmt->execute([$_GET['id']]);
        $order_details = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($order_details) {
            // Get order items
            $stmt = $pdo->prepare("SELECT oi.*, p.name, p.sku 
                                  FROM order_items oi 
                                  JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id = ?");
            $stmt->execute([$_GET['id']]);
            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $error = "Error fetching order details: " . $e->getMessage();
    }
}

// Get orders list (with filters if provided)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

try {
    // Base query
    $query = "FROM orders o JOIN users u ON o.user_id = u.id";
    $count_query = "SELECT COUNT(*) " . $query;
    $data_query = "SELECT o.*, u.username, u.email " . $query;
    
    // Apply filters
    $where_conditions = [];
    $params = [];
    
    if (isset($_GET['status']) && $_GET['status'] !== '') {
        $where_conditions[] = "o.status = ?";
        $params[] = $_GET['status'];
    }
    
    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $search = '%' . $_GET['search'] . '%';
        $where_conditions[] = "(o.id LIKE ? OR u.username LIKE ? OR u.email LIKE ?)";
        $params = array_merge($params, [$search, $search, $search]);
    }
    
    if (!empty($where_conditions)) {
        $where_clause = " WHERE " . implode(" AND ", $where_conditions);
        $count_query .= $where_clause;
        $data_query .= $where_clause;
    }
    
    // Order and limit
    $data_query .= " ORDER BY o.order_date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    // Get total count
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_orders = $stmt->fetchColumn();
    
    // Get orders data
    $stmt = $pdo->prepare($data_query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate total pages
    $total_pages = ceil($total_orders / $limit);
    
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
    <title>Order Management - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1><?= isset($_GET['id']) ? "Order #" . $_GET['id'] : "Orders Management" ?></h1>
                <div class="admin-user">
                    <span>Welcome, <?= htmlspecialchars($admin['username']) ?></span>
                    <a href="logout.php" class="btn btn-sm">Logout</a>
                </div>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?= $success_message ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['id']) && $order_details): ?>
                <!-- Single Order View -->
                <div class="order-details">
                    <div class="order-header">
                        <h2>Order Information</h2>
                        <div class="order-actions">
                            <form method="post" class="inline-form">
                                <input type="hidden" name="order_id" value="<?= $order_details['id'] ?>">
                                <select name="status" class="form-control">
                                    <option value="pending" <?= $order_details['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= $order_details['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="shipped" <?= $order_details['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= $order_details['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="cancelled" <?= $order_details['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn">Update Status</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="order-info-grid">
                        <div class="order-info-card">
                            <h3>Order Summary</h3>
                            <table class="info-table">
                                <tr>
                                    <th>Order ID:</th>
                                    <td>#<?= $order_details['id'] ?></td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td><?= date('F j, Y, g:i a', strtotime($order_details['order_date'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td><span class="status-badge status-<?= strtolower($order_details['status']) ?>"><?= htmlspecialchars($order_details['status']) ?></span></td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>$<?= number_format($order_details['total'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td><?= htmlspecialchars($order_details['payment_method']) ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="order-info-card">
                            <h3>Customer Information</h3>
                            <table class="info-table">
                                <tr>
                                    <th>Name:</th>
                                    <td><?= htmlspecialchars($order_details['username']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?= htmlspecialchars($order_details['email']) ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?= htmlspecialchars($order_details['phone']) ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?= nl2br(htmlspecialchars($order_details['address'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <h3>Order Items</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><?= htmlspecialchars($item['sku']) ?></td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Subtotal</th>
                                <td>$<?= number_format($order_details['subtotal'], 2) ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Shipping</th>
                                <td>$<?= number_format($order_details['shipping'], 2) ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Tax</th>
                                <td>$<?= number_format($order_details['tax'], 2) ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Total</th>
                                <td>$<?= number_format($order_details['total'], 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <a href="orders.php" class="btn btn-link">Back to Orders List</a>
                </div>
                
            <?php else: ?>
                <!-- Orders List View -->
                <div class="filters-bar">
                    <form method="get" class="filters-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="pending" <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= isset($_GET['status']) && $_GET['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="shipped" <?= isset($_GET['status']) && $_GET['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= isset($_GET['status']) && $_GET['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="cancelled" <?= isset($_GET['status']) && $_GET['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group search-group">
                                <label for="search">Search</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Order #, Email or Name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            </div>
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn">Filter</button>
                                <a href="orders.php" class="btn btn-outline">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($orders) > 0): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= $order['id'] ?></td>
                                        <td>
                                            <div><?= htmlspecialchars($order['username']) ?></div>
                                            <div class="text-muted"><?= htmlspecialchars($order['email']) ?></div>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                        <td>$<?= number_format($order['total'], 2) ?></td>
                                        <td><span class="status-badge status-<?= strtolower($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                                        <td>
                                            <a href="orders.php?id=<?= $order['id'] ?>" class="btn btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No orders found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="page-link">&laquo; Previous</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" class="page-link">Next &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>