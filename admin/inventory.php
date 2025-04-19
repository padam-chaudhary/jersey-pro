<?php
// admin/inventory.php - Inventory management

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
$products = [];
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

// Handle bulk update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_update'])) {
    try {
        $pdo->beginTransaction();
        
        foreach ($_POST['stock'] as $product_id => $stock) {
            if (!is_numeric($stock) || $stock < 0) {
                continue; // Skip invalid values
            }
            
            $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $stmt->execute([$stock, $product_id]);
        }
        
        $pdo->commit();
        $message = "Inventory successfully updated!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error updating inventory: " . $e->getMessage();
    }
}

// Handle single product update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $stock = $_POST['stock'];
    
    try {
        $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->execute([$stock, $product_id]);
        
        $message = "Product stock updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating product: " . $e->getMessage();
    }
}

// Get single product details
$product = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

try {
    // Get total products count for pagination
    $count_sql = "SELECT COUNT(*) FROM products";
    $params = [];
    
    if (!empty($search)) {
        $count_sql .= " WHERE name LIKE ? OR sku LIKE ?";
        $search_param = "%$search%";
        $params = [$search_param, $search_param];
    }
    
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_products = $stmt->fetchColumn();
    $total_pages = ceil($total_products / $per_page);
    
    // Get products list
    $sql = "SELECT * FROM products";
    
    if (!empty($search)) {
        $sql .= " WHERE name LIKE ? OR sku LIKE ?";
    }
    
    $sql .= " ORDER BY stock ASC LIMIT $per_page OFFSET $offset";
    
    $stmt = $pdo->prepare($sql);
    
    if (!empty($search)) {
        $search_param = "%$search%";
        $stmt->execute([$search_param, $search_param]);
    } else {
        $stmt->execute();
    }
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
    <title>Inventory Management - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Inventory Management</h1>
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
            
            <?php if ($product): ?>
                <!-- Single Product View -->
                <div class="back-link">
                    <a href="inventory.php" class="btn btn-link">&laquo; Back to Inventory</a>
                </div>
                
                <div class="product-details">
                    <h2>Update Stock: <?= htmlspecialchars($product['name']) ?></h2>
                    
                    <div class="product-info">
                        <div class="info-row">
                            <div class="info-label">SKU:</div>
                            <div class="info-value"><?= htmlspecialchars($product['sku']) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Price:</div>
                            <div class="info-value">$<?= number_format($product['price'], 2) ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Current Stock:</div>
                            <div class="info-value"><?= $product['stock'] ?></div>
                        </div>
                    </div>
                    
                    <form action="" method="POST" class="product-form">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        
                        <div class="form-group">
                            <label for="stock">Update Stock:</label>
                            <input type="number" id="stock" name="stock" value="<?= $product['stock'] ?>" min="0" required>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_product" class="btn">Update Stock</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Inventory List View -->
                <div class="admin-tools">
                    <form action="" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn">Search</button>
                        <?php if (!empty($search)): ?>
                            <a href="inventory.php" class="btn btn-link">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <form action="" method="POST">
                    <div class="bulk-actions">
                        <button type="submit" name="bulk_update" class="btn">Update All Stock</button>
                    </div>
                    
                    <div class="inventory-list">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($products) > 0): ?>
                                    <?php foreach ($products as $prod): ?>
                                        <tr>
                                            <td><?= $prod['id'] ?></td>
                                            <td>
                                                <?php if (!empty($prod['image'])): ?>
                                                    <img src="../assets/images/products/<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="product-thumbnail">
                                                <?php else: ?>
                                                    <div class="no-image">No Image</div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($prod['name']) ?></td>
                                            <td><?= htmlspecialchars($prod['sku']) ?></td>
                                            <td>$<?= number_format($prod['price'], 2) ?></td>
                                            <td>
                                                <input type="number" name="stock[<?= $prod['id'] ?>]" value="<?= $prod['stock'] ?>" min="0" class="stock-input">
                                            </td>
                                            <td>
                                                <?php if ($prod['stock'] <= 0): ?>
                                                    <span class="status-badge status-outofstock">Out of Stock</span>
                                                <?php elseif ($prod['stock'] <= 5): ?>
                                                    <span class="status-badge status-lowstock">Low Stock</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-instock">In Stock</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="inventory.php?id=<?= $prod['id'] ?>" class="btn btn-sm">Update</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No products found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                
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
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>