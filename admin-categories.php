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
    <title>Categories - JERSEY PRO</title>
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
        
        /* Categories Table */
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
        
        /* Category cards */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .category-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #e0e0e0;
            transition: box-shadow 0.3s;
        }
        
        .category-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .category-image {
            height: 150px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .category-image i {
            font-size: 50px;
            color: #4a90e2;
        }
        
        .category-details {
            padding: 15px;
        }
        
        .category-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        
        .category-count {
            color: #777;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .category-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
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
        <?php if (isset($_SESSION['category_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?php echo $_SESSION['category_success']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['category_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['category_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?php echo $_SESSION['category_error']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['category_error']); ?>
        <?php endif; ?>
        
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2 class="page-title">Categories</h2>
                <a href="admin-add-category.php" class="add-new-btn">
                    <i class="fas fa-plus"></i> Add New Category
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
                            <a href="admin-customers.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-users"></i></span>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-categories.php" class="nav-link active">
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
                    <div class="categories-grid">
                        <div class="category-card">
                            <div class="category-image">
                                <i class="fas fa-basketball-ball"></i>
                            </div>
                            <div class="category-details">
                                <div class="category-name">NBA</div>
                                <div class="category-count">28 Products</div>
                                <div class="category-actions">
                                    <a href="admin-edit-category.php?id=1" class="action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="admin-delete-category.php?id=1" class="action-btn" onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="category-card">
                            <div class="category-image">
                                <i class="fas fa-futbol"></i>
                            </div>
                            <div class="category-details">
                                <div class="category-name">Soccer</div>
                                <div class="category-count">42 Products</div>
                                <div class="category-actions">
                                    <a href="admin-edit-category.php?id=2" class="action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="admin-delete-category.php?id=2" class="action-btn" onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="category-card">
                            <div class="category-image">
                                <i class="fas fa-football-ball"></i>
                            </div>
                            <div class="category-details">
                                <div class="category-name">NFL</div>
                                <div class="category-count">35 Products</div>
                                <div class="category-actions">
                                    <a href="admin-edit-category.php?id=3" class="action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="admin-delete-category.php?id=3" class="action-btn" onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="category-card">
                            <div class="category-image">
                                <i class="fas fa-hockey-puck"></i>
                            </div>
                            <div class="category-details">
                                <div class="category-name">NHL</div>
                                <div class="category-count">21 Products</div>
                                <div class="category-actions">
                                    <a href="admin-edit-category.php?id=4" class="action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="admin-delete-category.php?id=4" class="action-btn" onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="category-card">
                            <div class="category-image">
                                <i class="fas fa-baseball-ball"></i>
                            </div>
                            <div class="category-details">
                                <div class="category-name">MLB</div>
                                <div class="category-count">18 Products</div>
                                <div class="category-actions">
                                    <a href="admin-edit-category.php?id=5" class="action-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="admin-delete-category.php?id=5" class="action-btn" onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Empty card for adding new category -->
                        <a href="admin-add-category.php" class="category-card" style="text-decoration: none; display: flex; justify-content: center; align-items: center; border: 2px dashed #e0e0e0;">
                            <div style="text-align: center; padding: 30px;">
                                <i class="fas fa-plus" style="font-size: 40px; color: #4a90e2; margin-bottom: 15px;"></i>
                                <div style="color: #777; font-weight: 600;">Add New Category</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // JavaScript to close alert messages
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.close');
            closeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const alert = this.parentElement;
                    alert.style.display = 'none';
                });
            });
        });
    </script>
    
</body>
</html>