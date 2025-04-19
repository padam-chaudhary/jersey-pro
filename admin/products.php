<?php
// admin/products.php - Product management

// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once '../includes/dbConnection.php';

$success_message = '';
$error = '';

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or update product
    if (isset($_POST['action']) && ($_POST['action'] === 'add' || $_POST['action'] === 'update')) {
        try {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = (float) $_POST['price'];
            $stock = (int) $_POST['stock'];
            $category_id = (int) $_POST['category_id'];
            
            // Basic validation
            if (empty($name) || $price <= 0) {
                throw new Exception("Please fill in all required fields");
            }
            
            // Handle image upload
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/products/';
                $temp_name = $_FILES['image']['tmp_name'];
                $file_name = time() . '_' . $_FILES['image']['name'];
                $image_path = $upload_dir . $file_name;
                
                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($temp_name, $image_path)) {
                    $image_path = str_replace('../', '/', $image_path);
                } else {
                    throw new Exception("Failed to upload image");
                }
            }
            
            if ($_POST['action'] === 'add') {
                // Insert new product
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category_id, image) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $name,
                    $description,
                    $price,
                    $stock,
                    $category_id,
                    $image_path
                ]);
                
                $success_message = "Product added successfully!";
                
            } else if ($_POST['action'] === 'update') {
                // Update existing product
                $product_id = (int) $_POST['product_id'];
                
                // If no new image uploaded, keep existing image
                if ($image_path === null) {
                    $stmt = $pdo->prepare("UPDATE products SET 
                                          name = ?, 
                                          description = ?, 
                                          price = ?, 
                                          stock = ?,
                                          category_id = ?
                                          WHERE id = ?");
                    $stmt->execute([
                        $name,
                        $description,
                        $price,
                        $stock,
                        $category_id,
                        $product_id
                    ]);
                } else {
                    $stmt = $pdo->prepare("UPDATE products SET 
                                          name = ?, 
                                          description = ?, 
                                          price = ?, 
                                          stock = ?,
                                          category_id = ?,
                                          image = ?
                                          WHERE id = ?");
                    $stmt->execute([
                        $name,
                        $description,
                        $price,
                        $stock,
                        $category_id,
                        $image_path,
                        $product_id
                    ]);
                }
                
                $success_message = "Product updated successfully!";
            }
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
    
    // Delete product
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['product_id'])) {
        try {
            $product_id = (int) $_POST['product_id'];
            
            // Check if product exists
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product) {
                throw new Exception("Product not found");
            }
            
            // Delete product
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            
            $success_message = "Product deleted successfully!";
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Check if editing a product
$edit_mode = false;
$product_to_edit = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    try {
        $product_id = (int) $_GET['edit'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product_to_edit) {
            $edit_mode = true;
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Get all categories
try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get products with pagination
try {
    // Count total products
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $total_products = $stmt->fetchColumn();
    $total_pages = ceil($total_products / $per_page);
    
    // Get products for current page
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name 
                           FROM products p
                           LEFT JOIN categories c ON p.category_id = c.id
                           ORDER BY p.id DESC
                           LIMIT ? OFFSET ?");
    $stmt->execute([$per_page, $offset]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Product Management</h1>
                <div class="admin-actions">
                    <?php if (!$edit_mode): ?>
                        <button id="addProductBtn" class="btn btn-primary">Add New Product</button>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= $success_message ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <div id="productFormContainer" <?= $edit_mode ? '' : 'class="hidden"' ?>>
                <div class="admin-card">
                    <h2><?= $edit_mode ? 'Edit Product' : 'Add New Product' ?></h2>
                    
                    <form method="POST" action="products.php" enctype="multipart/form-data" class="admin-form">
                        <input type="hidden" name="action" value="<?= $edit_mode ? 'update' : 'add' ?>">
                        <?php if ($edit_mode): ?>
                            <input type="hidden" name="product_id" value="<?= $product_to_edit['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" value="<?= $edit_mode ? htmlspecialchars($product_to_edit['name']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="5"><?= $edit_mode ? htmlspecialchars($product_to_edit['description']) : '' ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Price ($) *</label>
                                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= $edit_mode ? $product_to_edit['price'] : '' ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock">Stock *</label>
                                <input type="number" id="stock" name="stock" min="0" value="<?= $edit_mode ? $product_to_edit['stock'] : '0' ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id">
                                <option value="0">-- Select Category --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= $edit_mode && $product_to_edit['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <?php if ($edit_mode && $product_to_edit['image']): ?>
                                <div class="current-image">
                                    <img src="<?= htmlspecialchars($product_to_edit['image']) ?>" alt="Current Image" style="max-width: 200px;">
                                    <p>Current image. Upload a new one to replace it.</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="image" name="image" accept="image/*">
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" class="btn btn-primary"><?= $edit_mode ? 'Update Product' : 'Add Product' ?></button>
                            <?php if ($edit_mode): ?>
                                <a href="products.php" class="btn">Cancel</a>
                            <?php else: ?>
                                <button type="button" id="cancelAddProductBtn" class="btn">Cancel</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="admin-card">
                <h2>Product List</h2>
                
                <?php if (count($products) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product['id'] ?></td>
                                    <td>
                                        <?php if ($product['image']): ?>
                                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumbnail">
                                        <?php else: ?>
                                            <div class="no-image">No Image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>
                                    <td>$<?= number_format($product['price'], 2) ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="products.php?edit=<?= $product['id'] ?>" class="btn btn-sm">Edit</a>
                                            <form method="POST" action="products.php" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="products.php?page=<?= $page - 1 ?>" class="btn btn-sm">&laquo; Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="current-page"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="products.php?page=<?= $i ?>" class="btn btn-sm"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="products.php?page=<?= $page + 1 ?>" class="btn btn-sm">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <p>No products found. Add your first product!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Show/hide product form
        document.addEventListener('DOMContentLoaded', function() {
            const addProductBtn = document.getElementById('addProductBtn');
            const cancelAddProductBtn = document.getElementById('cancelAddProductBtn');
            const productFormContainer = document.getElementById('productFormContainer');
            
            if (addProductBtn) {
                addProductBtn.addEventListener('click', function() {
                    productFormContainer.classList.remove('hidden');
                    window.scrollTo({top: 0, behavior: 'smooth'});
                });
            }
            
            if (cancelAddProductBtn) {
                cancelAddProductBtn.addEventListener('click', function() {
                    productFormContainer.classList.add('hidden');
                });
            }
        });
    </script>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>