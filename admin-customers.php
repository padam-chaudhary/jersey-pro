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
    <title>Customers - JERSEY PRO</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        /* Customers Table */
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
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
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
        
        /* Search and Filter */
        .search-container {
            display: flex;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            font-size: 14px;
        }
        
        .search-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        
        .pagination a {
            color: #4a90e2;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }
        
        .pagination a.active {
            background-color: #4a90e2;
            color: white;
            border: 1px solid #4a90e2;
        }
        
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
        
        /* Add New Button */
        .add-new-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .add-new-btn i {
            margin-right: 8px;
        }
        
        .add-new-btn:hover {
            background-color: #3a80d2;
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
        <?php if (isset($_SESSION['customer_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?php echo $_SESSION['customer_success']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['customer_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['customer_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?php echo $_SESSION['customer_error']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['customer_error']); ?>
        <?php endif; ?>
        
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2 class="page-title">Customers</h2>
                <a href="admin-add-customer.php" class="add-new-btn">
                    <i class="fas fa-plus"></i> Add New Customer
                </a>
            </div>
            
            <div class="dashboard-content">
                <!-- Sidebar Navigation -->
                <div class="dashboard-sidebar">
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="admin-dashboard.php" class="nav-link">
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
                            <a href="admin-customers.php" class="nav-link active">
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
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Search customers...">
                        <button class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                    
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered Date</th>
                                    <th>Orders</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1001</td>
                                    <td>John Smith</td>
                                    <td>john.smith@example.com</td>
                                    <td>Feb 15, 2025</td>
                                    <td>5</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#1002</td>
                                    <td>Emma Johnson</td>
                                    <td>emma.johnson@example.com</td>
                                    <td>Feb 28, 2025</td>
                                    <td>3</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#1003</td>
                                    <td>Michael Brown</td>
                                    <td>michael.brown@example.com</td>
                                    <td>Mar 10, 2025</td>
                                    <td>1</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#1004</td>
                                    <td>Sophia Garcia</td>
                                    <td>sophia.garcia@example.com</td>
                                    <td>Mar 15, 2025</td>
                                    <td>2</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#1005</td>
                                    <td>Robert Wilson</td>
                                    <td>robert.wilson@example.com</td>
                                    <td>Mar 22, 2025</td>
                                    <td>0</td>
                                    <td><span class="status-badge status-inactive">Inactive</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#1006</td>
                                    <td>Olivia Martinez</td>
                                    <td>olivia.martinez@example.com</td>
                                    <td>Apr 2, 2025</td>
                                    <td>1</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#1007</td>
                                    <td>William Davis</td>
                                    <td>william.davis@example.com</td>
                                    <td>Apr 10, 2025</td>
                                    <td>3</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#1008</td>
                                    <td>Jennifer Taylor</td>
                                    <td>jennifer.taylor@example.com</td>
                                    <td>Apr 18, 2025</td>
                                    <td>0</td>
                                    <td><span class="status-badge status-inactive">Inactive</span></td>
                                    <td>
                                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="pagination">
                            <a href="#">&laquo;</a>
                            <a href="#" class="active">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#">4</a>
                            <a href="#">5</a>
                            <a href="#">&raquo;</a>
                        </div>
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