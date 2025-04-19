<?php
// wishlist.php - User wishlist

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
require_once '../includes/db_connect.php';

// Handle add to cart action
if (isset($_POST['action']) && $_POST['action'] === 'add_to_cart' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    try {
        // Check if the product exists in the wishlist
        $stmt = $pdo->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        if ($stmt->rowCount() > 0) {
            // Add to cart logic (simplified - would normally insert into cart table)
            // Then remove from wishlist
            $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$_SESSION['user_id'], $product_id]);
            
            $success_message = "Product added to cart and removed from wishlist!";
        } else {
            $error = "Product not found in your wishlist";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Handle remove from wishlist action
if (isset($_POST['action']) && $_POST['action'] === 'remove' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        
        $success_message = "Product removed from wishlist!";
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

try {
    // Get wishlist items with product details
    $stmt = $pdo->prepare("SELECT w.*, p.name, p.price, p.image, p.stock 
                          FROM wishlist w 
                          JOIN products p ON w.product_id = p.id 
                          WHERE w.user_id = ?
                          ORDER BY w.date_added DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="wishlist-container">
        <h1>My Wishlist</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (count($wishlist_items) > 0): ?>
            <div class="wishlist-items">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="wishlist-item">
                        <div class="item-image">
                            <?php if ($item['image']): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <?php else: ?>
                                <div class="no-image">No Image</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="price">$<?= number_format($item['price'], 2) ?></p>
                            <p class="stock">
                                <?php if ($item['stock'] > 0): ?>
                                    <span class="in-stock">In Stock</span>
                                <?php else: ?>
                                    <span class="out-of-stock">Out of Stock</span>
                                <?php endif; ?>
                            </p>
                            <p class="date-added">Added on <?= date('M d, Y', strtotime($item['date_added'])) ?></p>
                            
                            <div class="item-actions">
                                <?php if ($item['stock'] > 0): ?>
                                    <form method="POST">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        <input type="hidden" name="action" value="add_to_cart">
                                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                                    </form>
                                <?php endif; ?>
                                
                                <form method="POST">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                                
                                <a href="../product.php?id=<?= $item['product_id'] ?>" class="btn">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-wishlist">
                <p>Your wishlist is empty.</p>
                <a href="../shop.php" class="btn btn-primary">Browse Products</a>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="btn">Back to Dashboard</a>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>