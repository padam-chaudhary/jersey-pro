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