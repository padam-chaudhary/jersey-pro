<?php
// admin/customers.php - Customer management

// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once '../includes/dbConnection.php';

// Initialize variables
$customers = [];
$message = '';
$error = '';
$search = '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Handle search
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// Get customer details if viewing specific customer
$customer = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'customer'");
    $stmt->execute([$_GET['id']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get customer orders
    if ($customer) {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
        $stmt->execute([$_GET['id']]);
        $customer_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

try {
    // Get total customers count for pagination
    $count_sql = "SELECT COUNT(*) FROM users WHERE role = 'customer'";
    $params = [];
    
    if (!empty($search)) {
        $count_sql .= " AND (username LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $search_param = "%$search%";
        $params = [$search_param, $search_param, $search_param];
    }
    
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_customers = $stmt->fetchColumn();
    $total_pages = ceil($total_customers / $per_page);
    
    // Get customers list
    $sql = "SELECT * FROM users WHERE role = 'customer'";
    
    if (!empty($search)) {
        $sql .= " AND (username LIKE ? OR email LIKE ? OR phone LIKE ?)";
    }
    
    $sql .= " ORDER BY id DESC LIMIT $per_page OFFSET $offset";
    
    $stmt = $pdo->prepare($sql);
    
    if (!empty($search)) {
        $search_param = "%$search%";
        $stmt->execute([$search_param, $search_param, $search_param]);
    } else {
        $stmt->execute();
    }
    
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
    <title>Customer Management - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Customer Management</h1>
                <div class="admin-user">
                    <span>Welcome, <?= htmlspecialchars($admin['username']) ?></span>
                    <a href="logout.php" class="btn btn-sm">Logout</a>
                </div>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($customer): ?>
                <!-- Single Customer View -->
                <div class="back-link">
                    <a href="customers.php" class="btn btn-link">&laquo; Back to Customers</a>
                </div>
                
                <div class="customer-profile">
                    <h2>Customer Profile</h2>
                    <div class="customer-details">
                        <div class="detail-row">
                            <div class="detail-label">Name:</div>
                            <div class="detail-value"><?= htmlspecialchars($customer['username']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value"><?= htmlspecialchars($customer['email']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Phone:</div>
                            <div class="detail-value"><?= htmlspecialchars($customer['phone'] ?? 'Not provided') ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Registered:</div>
                            <div class="detail-value"><?= date('M d, Y', strtotime($customer['created_at'])) ?></div>
                        </div>
                    </div>
                    
                    <h3>Order History</h3>
                    <?php if (!empty($customer_orders)): ?>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customer_orders as $order): ?>
                                    <tr>
                                        <td><?= $order['id'] ?></td>
                                        <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                        <td>$<?= number_format($order['total'], 2) ?></td>
                                        <td><span class="status-badge status-<?= strtolower($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                                        <td><a href="orders.php?id=<?= $order['id'] ?>" class="btn btn-sm">View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No orders found for this customer.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Customers List View -->
                <div class="admin-tools">
                    <form action="" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Search customers..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn">Search</button>
                        <?php if (!empty($search)): ?>
                            <a href="customers.php" class="btn btn-link">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <div class="customer-list">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registered</th>
                                <th>Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($customers) > 0): ?>
                                <?php foreach ($customers as $cust): ?>
                                    <tr>
                                        <td><?= $cust['id'] ?></td>
                                        <td><?= htmlspecialchars($cust['username']) ?></td>
                                        <td><?= htmlspecialchars($cust['email']) ?></td>
                                        <td><?= htmlspecialchars($cust['phone'] ?? 'Not provided') ?></td>
                                        <td><?= date('M d, Y', strtotime($cust['created_at'])) ?></td>
                                        <td>
                                            <?php
                                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
                                            $stmt->execute([$cust['id']]);
                                            echo $stmt->fetchColumn();
                                            ?>
                                        </td>
                                        <td>
                                            <a href="customers.php?id=<?= $cust['id'] ?>" class="btn btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">No customers found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="btn btn-sm">&laquo; Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="page-current"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="page-link"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="btn btn-sm">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>