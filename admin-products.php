<?php
// Start session at the top
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page if not admin
    $_SESSION['login_error'] = "You must be logged in as an administrator to access the products page.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - JERSEY PRO</title>
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
        
        /* Products Table */
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
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .product-name {
            font-weight: 500;
            color: #333;
        }
        
        .product-sku {
            font-size: 12px;
            color: #777;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-instock {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-lowstock {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-outofstock {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-draft {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .status-featured {
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
        
        /* Product Grid View */
        .view-toggle {
            display: flex;
            gap: 10px;
        }
        
        .view-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            color: #555;
            cursor: pointer;
        }
        
        .view-btn.active {
            background-color: #4a90e2;
            color: white;
            border-color: #4a90e2;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .product-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-card-image {
            height: 180px;
            width: 100%;
            object-fit: cover;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .product-card-content {
            padding: 15px;
        }
        
        .product-card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .product-card-sku {
            font-size: 12px;
            color: #777;
            margin-bottom: 8px;
        }
        
        .product-card-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .product-card-price {
            font-weight: 600;
            color: #4a90e2;
        }
        
        .product-card-actions {
            display: flex;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
<?php require_once 'includes/header.php'; ?>

    <div class="page-container">
        <?php if (isset($_SESSION['product_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?php echo $_SESSION['product_success']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['product_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['product_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?php echo $_SESSION['product_error']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['product_error']); ?>
        <?php endif; ?>
        
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2 class="page-title">Products Management</h2>
                <a href="admin-add-product.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Product</a>
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
                            <a href="admin-products.php" class="nav-link active">
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
                            <select class="filter-select" id="category-filter">
                                <option value="all">All Categories</option>
                                <option value="nba">NBA</option>
                                <option value="nfl">NFL</option>
                                <option value="soccer">Soccer</option>
                                <option value="mlb">MLB</option>
                                <option value="nhl">NHL</option>
                            </select>
                            <select class="filter-select" id="status-filter">
                                <option value="all">All Status</option>
                                <option value="instock">In Stock</option>
                                <option value="lowstock">Low Stock</option>
                                <option value="outofstock">Out of Stock</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="view-toggle">
                            <button class="view-btn active" id="table-view-btn"><i class="fas fa-list"></i> List</button>
                            <button class="view-btn" id="grid-view-btn"><i class="fas fa-th"></i> Grid</button>
                        </div>
                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="Search products...">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                    
                    <div class="bulk-actions">
                        <select class="filter-select">
                            <option value="">Bulk Actions</option>
                            <option value="mark-instock">Mark as In Stock</option>
                            <option value="mark-outofstock">Mark as Out of Stock</option>
                            <option value="mark-featured">Mark as Featured</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button class="btn btn-primary">Apply</button>
                    </div>
                    
                    <!-- Table View (Default) -->
                    <div class="table-container" id="table-view">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="Los Angeles Lakers Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">Los Angeles Lakers Home Jersey</div>
                                    </td>
                                    <td>LAK-001-HJ</td>
                                    <td>NBA</td>
                                    <td>$99.99</td>
                                    <td>32</td>
                                    <td><span class="status-badge status-instock">In Stock</span></td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="Manchester United Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">Manchester United Away Jersey</div>
                                    </td>
                                    <td>MUN-002-AJ</td>
                                    <td>Soccer</td>
                                    <td>$89.95</td>
                                    <td>18</td>
                                    <td><span class="status-badge status-instock">In Stock</span></td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="New England Patriots Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">New England Patriots Home Jersey</div>
                                    </td>
                                    <td>NEP-003-HJ</td>
                                    <td>NFL</td>
                                    <td>$119.99</td>
                                    <td>5</td>
                                    <td><span class="status-badge status-lowstock">Low Stock</span></td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="Chicago Bulls Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">Chicago Bulls Retro Jersey</div>
                                    </td>
                                    <td>CHB-004-RJ</td>
                                    <td>NBA</td>
                                    <td>$129.99</td>
                                    <td>0</td>
                                    <td><span class="status-badge status-outofstock">Out of Stock</span></td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="Real Madrid Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">Real Madrid Home Jersey</div>
                                    </td>
                                    <td>RMD-005-HJ</td>
                                    <td>Soccer</td>
                                    <td>$94.95</td>
                                    <td>27</td>
                                    <td><span class="status-badge status-instock">In Stock</span></td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="New York Yankees Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">New York Yankees Home Jersey</div>
                                    </td>
                                    <td>NYY-006-HJ</td>
                                    <td>MLB</td>
                                    <td>$89.99</td>
                                    <td>21</td>
                                    <td><span class="status-badge status-instock">In Stock</span></td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="FC Barcelona Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">FC Barcelona Away Jersey</div>
                                    </td>
                                    <td>FCB-007-AJ</td>
                                    <td>Soccer</td>
                                    <td>$94.95</td>
                                    <td>0</td>
                                    <td><span class="status-badge status-outofstock">Out of Stock</span></td>
                                    <td>
                                        <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="select-product"></td>
                                    <td><img src="/api/placeholder/60/60" alt="Dallas Cowboys Jersey" class="product-image"></td>
                                    <td>
                                        <div class="product-name">Dallas Cowboys Home Jersey</div>
                                    </td>
                                    <td>DAL-008-HJ</td>
                                    <td>NFL</td>
                                    <td>$119.99</td>
                                    <td>3</td>
                                    <td><span class="status-badge status-lowstock">Low Stock</span></td>
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
                    
                    <!-- Grid View (Hidden by default) -->
                    <div class="products-grid" id="grid-view" style="display: none;">
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="Los Angeles Lakers Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">Los Angeles Lakers Home Jersey</h3>
                                <div class="product-card-sku">SKU: LAK-001-HJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$99.99</span>
                                    <span class="status-badge status-instock">In Stock (32)</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="Manchester United Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">Manchester United Away Jersey</h3>
                                <div class="product-card-sku">SKU: MUN-002-AJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$89.95</span>
                                    <span class="status-badge status-instock">In Stock (18)</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="New England Patriots Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">New England Patriots Home Jersey</h3>
                                <div class="product-card-sku">SKU: NEP-003-HJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$119.99</span>
                                    <span class="status-badge status-lowstock">Low Stock (5)</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="Chicago Bulls Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">Chicago Bulls Retro Jersey</h3>
                                <div class="product-card-sku">SKU: CHB-004-RJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$129.99</span>
                                    <span class="status-badge status-outofstock">Out of Stock</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="Real Madrid Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">Real Madrid Home Jersey</h3>
                                <div class="product-card-sku">SKU: RMD-005-HJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$94.95</span>
                                    <span class="status-badge status-instock">In Stock (27)</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="New York Yankees Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">New York Yankees Home Jersey</h3>
                                <div class="product-card-sku">SKU: NYY-006-HJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$89.99</span>
                                    <span class="status-badge status-instock">In Stock (21)</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="FC Barcelona Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">FC Barcelona Away Jersey</h3>
                                <div class="product-card-sku">SKU: FCB-007-AJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$94.95</span>
                                    <span class="status-badge status-outofstock">Out of Stock</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <img src="/api/placeholder/250/180" alt="Dallas Cowboys Jersey" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title">Dallas Cowboys Home Jersey</h3>
                                <div class="product-card-sku">SKU: DAL-008-HJ</div>
                                <div class="product-card-details">
                                    <span class="product-card-price">$119.99</span>
                                    <span class="status-badge status-lowstock">Low Stock (3)</span>
                                </div>
                                <div class="product-card-actions">
                                    <button class="action-btn" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    <input type="checkbox" class="select-product">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination for grid view -->
                        <div class="pagination" style="grid-column: 1 / -1;">
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
        // Toggle between table and grid view
        document.getElementById('table-view-btn').addEventListener('click', function() {
            document.getElementById('table-view').style.display = 'block';
            document.getElementById('grid-view').style.display = 'none';
            this.classList.add('active');
            document.getElementById('grid-view-btn').classList.remove('active');
        });
        
        document.getElementById('grid-view-btn').addEventListener('click', function() {
            document.getElementById('grid-view').style.display = 'grid';
            document.getElementById('table-view').style.display = 'none';
            this.classList.add('active');
            document.getElementById('table-view-btn').classList.remove('active');
        });
        
        // Select all checkbox functionality
        document.getElementById('select-all').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.select-product');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });
        
        // Close alert messages
        var closeButtons = document.querySelectorAll('.alert .close');
        closeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        
        // Category and status filtering
        document.getElementById('category-filter').addEventListener('change', function() {
            // Add filtering logic here
            console.log('Category filter changed to: ' + this.value);
        });
        
        document.getElementById('status-filter').addEventListener('change', function() {
            // Add filtering logic here
            console.log('Status filter changed to: ' + this.value);
        });
        
        // Search functionality
        document.querySelector('.search-container .btn').addEventListener('click', function() {
            var searchText = document.querySelector('.search-input').value;
            // Add search logic here
            console.log('Searching for: ' + searchText);
        });
        
        // Bulk actions functionality
        document.querySelector('.bulk-actions .btn').addEventListener('click', function() {
            var action = document.querySelector('.bulk-actions .filter-select').value;
            if (action) {
                // Get selected products
                var selectedProducts = [];
                var checkboxes = document.querySelectorAll('.select-product:checked');
                checkboxes.forEach(function(checkbox) {
                    // Logic to identify the product
                    var productRow = checkbox.closest('tr');
                    if (productRow) {
                        // For table view
                        var productName = productRow.querySelector('.product-name').textContent;
                        selectedProducts.push(productName);
                    } else {
                        // For grid view
                        var productCard = checkbox.closest('.product-card');
                        if (productCard) {
                            var productName = productCard.querySelector('.product-card-title').textContent;
                            selectedProducts.push(productName);
                        }
                    }
                });
                
                // Add bulk action logic here
                console.log('Applying action: ' + action + ' to products: ', selectedProducts);
            } else {
                alert('Please select an action');
            }
        });
    </script>
</body>
</html>