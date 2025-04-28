<?php 
require_once 'includes/header.php';
require_once 'includes/dbConnection.php';  // Assumes this file creates a PDO connection stored in $pdo
require_once 'includes/functions.php';

// Calculate current cart count
$cart_count = 0;
$cart_total = 0;
$cart_items = [];

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $cart_count += $item['quantity'];
        
        // Get product details from database
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id");
        $stmt->execute([':product_id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $item_total = $product['price'] * $item['quantity'];
            $cart_total += $item_total;
            
            // Add product info to cart item
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'quantity' => $item['quantity'],
                'item_total' => $item_total
            ];
        }
    }
}

// Process POST requests for updating or removing cart items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
            
            // Redirect to refresh the page with updated cart
            header('Location: cart.php');
            exit;
        } elseif ($_POST['action'] === 'remove' && isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
            unset($_SESSION['cart'][$product_id]);
            
            // Redirect to refresh the page with updated cart
            header('Location: cart.php');
            exit;
        } elseif ($_POST['action'] === 'clear') {
            // Clear the entire cart
            $_SESSION['cart'] = [];
            
            // Redirect to refresh the page with empty cart
            header('Location: cart.php');
            exit;
        }
    }
}
?>

    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
 <section class="page-banner">
        <h1>Your Shopping Cart</h1>
    </section>

    <section class="cart-section">
        <?php if (!empty($cart_items)): ?>
            <div class="cart-container">
                <div class="cart-header">
                    <div>Image</div>
                    <div>Product</div>
                    <div>Price</div>
                    <div>Quantity</div>
                    <div>Total</div>
                    <div></div>
                </div>
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p><?php echo htmlspecialchars($item['description']); ?></p>
                            </div>
                            <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
                            <div class="item-quantity">
                                <form method="POST" action="cart.php" class="update-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10">
                                    <button type="submit" class="update-btn">Update</button>
                                </form>
                            </div>
                            <div class="item-total">$<?php echo number_format($item['item_total'], 2); ?></div>
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <button type="submit" class="remove-btn"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cart-summary">
                    <div class="cart-total">
                        <span>Total:</span>
                        <span class="total-amount">$<?php echo number_format($cart_total, 2); ?></span>
                    </div>
                    <div class="cart-actions">
                        <a href="jerseys.php" class="continue-shopping"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                        <form method="POST" action="cart.php" style="display: inline;">
                            <input type="hidden" name="action" value="clear">
                            <button type="submit" class="clear-cart"><i class="fas fa-trash"></i> Clear Cart</button>
                        </form>
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="jerseys.php" class="continue-shopping">Start Shopping</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Add toast notification container -->
    <div class="toast-notification" id="toastNotification"></div>

    <?php 
    // Include footer
    require_once 'includes/footer.php';
    
    ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toast notification functionality
        const toastNotification = document.getElementById('toastNotification');
        
        // Function to show toast notification
        function showToast(message) {
            toastNotification.textContent = message;
            toastNotification.style.display = 'block';
            
            // Hide after 3 seconds
            setTimeout(function() {
                toastNotification.style.display = 'none';
            }, 3000);
        }
        
        // Check for URL parameters - for showing messages after redirect
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        if (message) {
            showToast(decodeURIComponent(message));
        }
        
        // Handle quantity changes to enable update button
        const quantityInputs = document.querySelectorAll('.item-quantity input');
        const updateBtns = document.querySelectorAll('.update-btn');
        
        quantityInputs.forEach((input, index) => {
            input.addEventListener('change', function() {
                // Make update button more visible when quantity changes
                updateBtns[index].style.backgroundColor = '#ff9800';
                updateBtns[index].textContent = 'Update!';
            });
        });
    });
    </script>
</body>
</html>