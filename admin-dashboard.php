<?php
// Start session at the top
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page if not admin
    $_SESSION['login_error'] = "You must be logged in as an administrator to access the dashboard.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - JERSEY PRO</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Page Content */
        .page-container {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }
        
        /* Dashboard Container */
        .dashboard-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .dashboard-header {
            background-color: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .page-title {
            margin: 0;
            color: #333;
        }
        
        .dashboard-content {
            display: flex;
            min-height: 600px;
        }
        
        /* Sidebar Navigation */
        .dashboard-sidebar {
            width: 250px;
            background-color: #f8f9fa;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
        }
        
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #555;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .nav-link:hover {
            background-color: #e9ecef;
            color: #4a90e2;
        }
        
        .nav-link.active {
            background-color: #4a90e2;
            color: white;
            font-weight: 600;
        }
        
        .nav-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Area */
        .dashboard-main {
            flex: 1;
            padding: 30px;
        }
        
        /* Dashboard Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 20px;
            border-left: 4px solid #4a90e2;
        }
        
        .stat-card.orders {
            border-left-color: #4a90e2;
        }
        
        .stat-card.revenue {
            border-left-color: #28a745;
        }
        
        .stat-card.users {
            border-left-color: #ffc107;
        }
        
        .stat-card.products {
            border-left-color: #17a2b8;
        }
        
        .stat-title {
            color: #777;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #333;
        }
        
        .stat-change {
            font-size: 13px;
            color: #28a745;
        }
        
        .stat-change.negative {
            color: #dc3545;
        }
        
        /* Recent Orders Table */
        .table-container {
            margin-top: 30px;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .section-title {
            margin: 0;
            color: #333;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }
        
        .table tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .action-btn {
            background-color: transparent;
            border: none;
            color: #4a90e2;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .action-btn:hover {
            color: #3a80d2;
        }
        
        .view-all {
            display: block;
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            color: #4a90e2;
            text-decoration: none;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        
        .view-all:hover {
            background-color: #e9ecef;
        }
        
        /* Alert messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .alert-dismissible {
            position: relative;
        }
        
        .alert-dismissible .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 15px;
            color: inherit;
            cursor: pointer;
            background: transparent;
            border: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
<?php require_once 'includes/header.php'; ?>

    <div class="page-container">
        <?php if (isset($_SESSION['dashboard_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?php echo $_SESSION['dashboard_success']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['dashboard_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['dashboard_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?php echo $_SESSION['dashboard_error']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['dashboard_error']); ?>
        <?php endif; ?>
        
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2 class="page-title">Admin Dashboard</h2>
            </div>
            
            <div class="dashboard-content">
                <!-- Sidebar Navigation -->
                <div class="dashboard-sidebar">
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="admin-dashboard.php" class="nav-link active">
                                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-orders.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-products.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-tshirt"></i></span>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-customers.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-users"></i></span>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-categories.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-tags"></i></span>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-settings.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-cog"></i></span>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Main Content -->
                <div class="dashboard-main">
                    <div class="stats-container">
                        <div class="stat-card orders">
                            <div class="stat-title">TOTAL ORDERS</div>
                            <div class="stat-value">147</div>
                            <div class="stat-change">+12.5% <i class="fas fa-arrow-up"></i></div>
                        </div>
                        
                        <div class="stat-card revenue">
                            <div class="stat-title">TOTAL REVENUE</div>
                            <div class="stat-value">$8,549</div>
                            <div class="stat-change">+8.2% <i class="fas fa-arrow-up"></i></div>
                        </div>
                        
                        <div class="stat-card users">
                            <div class="stat-title">TOTAL CUSTOMERS</div>
                            <div class="stat-value">293</div>
                            <div class="stat-change">+5.7% <i class="fas fa-arrow-up"></i></div>
                        </div>
                        
                        <div class="stat-card products">
                            <div class="stat-title">TOTAL PRODUCTS</div>
                            <div class="stat-value">85</div>
                            <div class="stat-change negative">-2.3% <i class="fas fa-arrow-down"></i></div>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="section-title">Recent Orders</h3>
                        </div>
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-5723</td>
                                    <td>John Smith</td>
                                    <td>Apr 23, 2025</td>
                                    <td>$129.99</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-5722</td>
                                    <td>Emma Johnson</td>
                                    <td>Apr 22, 2025</td>
                                    <td>$89.95</td>
                                    <td><span class="status-badge status-processing">Processing</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-5721</td>
                                    <td>Michael Brown</td>
                                    <td>Apr 22, 2025</td>
                                    <td>$149.99</td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-5720</td>
                                    <td>Sophia Garcia</td>
                                    <td>Apr 21, 2025</td>
                                    <td>$75.50</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-5719</td>
                                    <td>Robert Wilson</td>
                                    <td>Apr 20, 2025</td>
                                    <td>$210.75</td>
                                    <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <a href="admin-orders.php" class="view-all">View All Orders <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="table-container">
                        <div class="table-header">
                            <h3 class="section-title">Latest Products</h3>
                        </div>
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Los Angeles Lakers Home Jersey</td>
                                    <td>NBA</td>
                                    <td>$99.99</td>
                                    <td>32</td>
                                    <td><span class="status-badge status-completed">In Stock</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Manchester United Away Jersey</td>
                                    <td>Soccer</td>
                                    <td>$89.95</td>
                                    <td>18</td>
                                    <td><span class="status-badge status-completed">In Stock</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New England Patriots Home Jersey</td>
                                    <td>NFL</td>
                                    <td>$119.99</td>
                                    <td>5</td>
                                    <td><span class="status-badge status-pending">Low Stock</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Chicago Bulls Retro Jersey</td>
                                    <td>NBA</td>
                                    <td>$129.99</td>
                                    <td>0</td>
                                    <td><span class="status-badge status-cancelled">Out of Stock</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Real Madrid Home Jersey</td>
                                    <td>Soccer</td>
                                    <td>$94.95</td>
                                    <td>27</td>
                                    <td><span class="status-badge status-completed">In Stock</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <a href="admin-products.php" class="view-all">View All Products <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Close alert messages
        const closeButtons = document.querySelectorAll('.alert .close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
    });
    </script>
</body>
</html>