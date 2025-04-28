<?php
// Start session at the top
session_start();

// Check if user is logged in as admin
// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
//     // Redirect to login page if not admin
//     $_SESSION['login_error'] = "You must be logged in as an administrator to access the orders page.";
//     header("Location: login.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - JERSEY PRO</title>
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
        
        /* Filter and Search Bar */
        .filters-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .filter-group {
            display: flex;
            gap: 10px;
        }
        
        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            color: #555;
        }
        
        .search-container {
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 240px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a80d2;
        }
        
        /* Orders Table */
        .table-container {
            margin-top: 20px;
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
        
        .status-shipped {
            background-color: #d1ecf1;
            color: #0c5460;
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
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 5px;
        }
        
        .pagination-link {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .pagination-link:hover {
            background-color: #f8f9fa;
        }
        
        .pagination-link.active {
            background-color: #4a90e2;
            color: white;
            border-color: #4a90e2;
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
        
        /* Bulk action toolbar */
        .bulk-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<?php require_once 'includes/header.php'; ?>

    <div class="page-container">
        <?php if (isset($_SESSION['orders_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?php echo $_SESSION['orders_success']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['orders_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['orders_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?php echo $_SESSION['orders_error']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['orders_error']); ?>
        <?php endif; ?>
        
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2 class="page-title">Orders Management</h2>
                <a href="admin-add-order.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Order</a>
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
                            <a href="admin-orders.php" class="nav-link active">
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
                    <div class="filters-container">
                        <div class="filter-group">
                            <select class="filter-select" id="status-filter">
                                <option value="all">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <select class="filter-select" id="date-filter">
                                <option value="all">All Dates</option>
                                <option value="today">Today</option>
                                <option value="this-week">This Week</option>
                                <option value="this-month">This Month</option>
                                <option value="last-month">Last Month</option>
                            </select>
                        </div>
                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="Search orders...">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                    
                    <div class="bulk-actions">
                        <select class="filter-select">
                            <option value="">Bulk Actions</option>
                            <option value="mark-processing">Mark as Processing</option>
                            <option value="mark-shipped">Mark as Shipped</option>
                            <option value="mark-completed">Mark as Completed</option>
                            <option value="mark-cancelled">Mark as Cancelled</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button class="btn btn-primary">Apply</button>
                    </div>
                    
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5723</td>
                                    <td>John Smith</td>
                                    <td>Apr 23, 2025</td>
                                    <td>$129.99</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td>Credit Card</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5722</td>
                                    <td>Emma Johnson</td>
                                    <td>Apr 22, 2025</td>
                                    <td>$89.95</td>
                                    <td><span class="status-badge status-processing">Processing</span></td>
                                    <td>PayPal</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5721</td>
                                    <td>Michael Brown</td>
                                    <td>Apr 22, 2025</td>
                                    <td>$149.99</td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>Credit Card</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5720</td>
                                    <td>Sophia Garcia</td>
                                    <td>Apr 21, 2025</td>
                                    <td>$75.50</td>
                                    <td><span class="status-badge status-shipped">Shipped</span></td>
                                    <td>Credit Card</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5719</td>
                                    <td>Robert Wilson</td>
                                    <td>Apr 20, 2025</td>
                                    <td>$210.75</td>
                                    <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                    <td>PayPal</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5718</td>
                                    <td>David Lee</td>
                                    <td>Apr 19, 2025</td>
                                    <td>$95.00</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td>Credit Card</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5717</td>
                                    <td>Sarah Miller</td>
                                    <td>Apr 18, 2025</td>
                                    <td>$175.25</td>
                                    <td><span class="status-badge status-shipped">Shipped</span></td>
                                    <td>PayPal</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5716</td>
                                    <td>James Taylor</td>
                                    <td>Apr 17, 2025</td>
                                    <td>$249.99</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td>Credit Card</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5715</td>
                                    <td>Lisa Anderson</td>
                                    <td>Apr 17, 2025</td>
                                    <td>$120.00</td>
                                    <td><span class="status-badge status-processing">Processing</span></td>
                                    <td>PayPal</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-order"></td>
                                    <td>#ORD-5714</td>
                                    <td>Brian White</td>
                                    <td>Apr 16, 2025</td>
                                    <td>$85.50</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td>Credit Card</td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="pagination">
                            <a href="#" class="pagination-link"><i class="fas fa-angle-double-left"></i></a>
                            <a href="#" class="pagination-link"><i class="fas fa-angle-left"></i></a>
                            <a href="#" class="pagination-link active">1</a>
                            <a href="#" class="pagination-link">2</a>
                            <a href="#" class="pagination-link">3</a>
                            <a href="#" class="pagination-link">4</a>
                            <a href="#" class="pagination-link">5</a>
                            <a href="#" class="pagination-link"><i class="fas fa-angle-right"></i></a>
                            <a href="#" class="pagination-link"><i class="fas fa-angle-double-right"></i></a>
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
        
        // Select all functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const orderCheckboxes = document.querySelectorAll('.select-order');
        
        selectAllCheckbox.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        // Check if all checkboxes are selected
        orderCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = [...orderCheckboxes].every(c => c.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
        
        // Filter functionality (basic simulation)
        const statusFilter = document.getElementById('status-filter');
        statusFilter.addEventListener('change', function() {
            console.log('Filtering by status:', this.value);
            // In a real app, this would trigger an AJAX call or page reload with filter parameters
        });
    });
    </script>
</body>
</html>