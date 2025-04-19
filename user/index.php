<?php
// index.php - User dashboard main page

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
require_once '../includes/db_connect.php';

try {
    // Get user info
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get recent orders
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get wishlist count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $wishlist_count = $stmt->fetchColumn();
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="dashboard-container">
        <h1>Welcome, <?= htmlspecialchars($user['username']) ?>!</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="dashboard-summary">
            <div class="dashboard-card">
                <h2>Account Summary</h2>
                <p>Name: <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                <a href="profile.php" class="btn">Edit Profile</a>
            </div>
            
            <div class="dashboard-card">
                <h2>Recent Orders</h2>
                <?php if (count($recent_orders) > 0): ?>
                    <ul>
                        <?php foreach ($recent_orders as $order): ?>
                            <li>
                                Order #<?= $order['id'] ?> - 
                                <?= date('M d, Y', strtotime($order['order_date'])) ?> - 
                                $<?= number_format($order['total'], 2) ?>
                                <a href="orders.php?id=<?= $order['id'] ?>">View</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="orders.php" class="btn">View All Orders</a>
                <?php else: ?>
                    <p>You have no recent orders.</p>
                <?php endif; ?>
            </div>
            
            <div class="dashboard-card">
                <h2>Wishlist</h2>
                <p>You have <?= $wishlist_count ?> items in your wishlist.</p>
                <a href="wishlist.php" class="btn">View Wishlist</a>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>

<?php
// orders.php - Order history

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
require_once '../includes/db_connect.php';

try {
    // If specific order is requested
    if (isset($_GET['id'])) {
        $order_id = $_GET['id'];
        
        // Get order details
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$order_id, $_SESSION['user_id']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            $error = "Order not found";
        } else {
            // Get order items
            $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image 
                                  FROM order_items oi
                                  JOIN products p ON oi.product_id = p.id
                                  WHERE oi.order_id = ?");
            $stmt->execute([$order_id]);
            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        // Get all orders
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="orders-container">
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (isset($order)): ?>
            <!-- Single Order View -->
            <h1>Order #<?= $order['id'] ?></h1>
            <div class="order-details">
                <p><strong>Date:</strong> <?= date('F d, Y', strtotime($order['order_date'])) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
                
                <h2>Items</h2>
                <div class="order-items">
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <?php if ($item['image']): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <?php endif; ?>
                            <div class="item-details">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p>Quantity: <?= $item['quantity'] ?></p>
                                <p>Price: $<?= number_format($item['price'], 2) ?></p>
                                <p>Subtotal: $<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <a href="orders.php" class="btn">Back to All Orders</a>
            
        <?php else: ?>
            <!-- All Orders View -->
            <h1>Order History</h1>
            
            <?php if (count($orders) > 0): ?>
                <div class="orders-list">
                    <table>
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $order['id'] ?></td>
                                    <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                    <td><?= htmlspecialchars($order['status']) ?></td>
                                    <td>$<?= number_format($order['total'], 2) ?></td>
                                    <td><a href="orders.php?id=<?= $order['id'] ?>">View Details</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>You haven't placed any orders yet.</p>
            <?php endif; ?>
            
        <?php endif; ?>
        
        <a href="index.php" class="btn">Back to Dashboard</a>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>

<?php
// profile.php - User profile settings

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
require_once '../includes/db_connect.php';

$success_message = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $zip = trim($_POST['zip'] ?? '');
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Check if the email already exists for another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Email is already in use by another account");
        }
        
        // Update user profile
        $stmt = $pdo->prepare("UPDATE users SET 
                              first_name = ?, 
                              last_name = ?, 
                              email = ?, 
                              phone = ?, 
                              address = ?, 
                              city = ?, 
                              state = ?, 
                              zip = ? 
                              WHERE id = ?");
        
        $stmt->execute([
            $first_name, 
            $last_name, 
            $email, 
            $phone, 
            $address, 
            $city, 
            $state, 
            $zip, 
            $_SESSION['user_id']
        ]);
        
        $success_message = "Profile updated successfully!";
        
        // Check if password change is requested
        if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Verify passwords match
            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match");
            }
            
            // Check password strength
            if (strlen($new_password) < 8) {
                throw new Exception("Password must be at least 8 characters long");
            }
            
            // Get current password hash
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify current password
            if (!password_verify($current_password, $user['password'])) {
                throw new Exception("Current password is incorrect");
            }
            
            // Update password
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$password_hash, $_SESSION['user_id']]);
            
            $success_message = "Profile and password updated successfully!";
        }
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

try {
    // Get user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="profile-container">
        <h1>Profile Settings</h1>
        
        <?php if ($success_message): ?>
            <div class="success-message"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="profile.php" class="profile-form">
            <div class="form-section">
                <h2>Personal Information</h2>
                
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Address</h2>
                
                <div class="form-group">
                    <label for="address">Street Address</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="<?= htmlspecialchars($user['state'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="zip">ZIP Code</label>
                        <input type="text" id="zip" name="zip" value="<?= htmlspecialchars($user['zip'] ?? '') ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Change Password</h2>
                <p><small>Leave blank if you don't want to change your password</small></p>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="index.php" class="btn">Cancel</a>
            </div>
        </form>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>

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