<?php 

// Initialize cart_count before including header
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}

require_once 'includes/header.php';
require_once 'includes/dbConnection.php';  // Assumes this file creates a PDO connection stored in $pdo

// Get category filter from URL if it exists
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

// Prepare the SQL query based on filter
$sql = "SELECT p.*, c.name as category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id";

// Apply category filter if not "all"
$params = [];
if ($category_filter != 'all') {
    // Get category_id for the selected sport
    $category_sql = "SELECT category_id FROM categories WHERE name LIKE :category LIMIT 1";
    $stmt = $pdo->prepare($category_sql);
    $stmt->execute([':category' => '%' . $category_filter . '%']);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($category) {
        $sql .= " WHERE p.category_id = :category_id";
        $params[':category_id'] = $category['category_id'];
    }
}

// Add ordering
$sql .= " ORDER BY p.product_id DESC";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all unique categories for the filter dropdown
$categories_sql = "SELECT DISTINCT name FROM categories ORDER BY name";
$stmt = $pdo->prepare($categories_sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <link rel="stylesheet" href="assets/css/jerseys.css">

</head>
<body>
    <section class="page-banner"> 
        <h1>Shop Premium Jerseys</h1>
    </section>

    <section class="filter-section">
        <div class="filter-container">
            <div class="filter-group">
                <form method="GET" action="jerseys.php">
                    <label for="category">Sport:</label>
                    <select id="category" name="category" onchange="this.form.submit()">
                        <option value="all" <?php echo $category_filter == 'all' ? 'selected' : ''; ?>>All Sports</option>
                        <?php
                        // Map common sports categories to display names
                        $sport_mapping = [
                            'Basketball' => 'Basketball',
                            'Football' => 'Football',
                            'Soccer' => 'Soccer',
                            'Baseball' => 'Baseball',
                            'Hockey' => 'Hockey'
                        ];
                        
                        foreach ($categories as $category) {
                            // Extract the sport name from category
                            $sport = '';
                            foreach ($sport_mapping as $key => $value) {
                                if (stripos($category['name'], $key) !== false) {
                                    $sport = strtolower($value);
                                    break;
                                }
                            }
                            
                            if (!empty($sport)) {
                                $selected = ($category_filter == $sport) ? 'selected' : '';
                                echo "<option value=\"{$sport}\" {$selected}>{$sport_mapping[ucfirst($sport)]}</option>";
                            }
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="products-grid">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="team"><?php echo htmlspecialchars($product['description']); ?></p>
                        </div>
                        <div class="product-footer">
                            <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                            <button class="add-to-cart" data-product-id="<?php echo $product['product_id']; ?>">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <p>No products found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Add toast notification container -->
    <div class="toast-notification" id="toastNotification">
        Product added to cart!
    </div>

   <?php 
   include_once 'includes/footer.php'; // Include the footer file
   ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add to cart functionality
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
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
        
        // Function to update all cart count elements
        function updateCartCount(count) {
            // Use ID selector for more reliable targeting
            const cartCountElement = document.getElementById('navCartCount');
            
            // Debug to console
            console.log('Updating cart count to:', count);
            console.log('Cart count element found:', cartCountElement);
            
            if (cartCountElement) {
                cartCountElement.textContent = count;
                
                // Add animation to highlight the change
                cartCountElement.classList.add('pulse-animation');
                setTimeout(() => {
                    cartCountElement.classList.remove('pulse-animation');
                }, 500);
            } else {
                console.error('Cart count element not found!');
            }
        }
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                
                // Log the action
                console.log('Adding product to cart:', productId);
                
                // AJAX request to add product to cart
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId + '&quantity=1'
                })
                .then(response => {
                    console.log('Response received');
                    return response.json();
                })
                .then(data => {
                    // Log the data for debugging
                    console.log('Data received:', data);
                    
                    if(data.success) {
                        // Show toast notification instead of alert
                        showToast('Product added to cart!');
                        
                        // Update cart count in the navbar using our helper function
                        updateCartCount(data.cart_count);
                    } else {
                        showToast('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.');
                });
            });
        });
    });
    </script>
</body>
</html>