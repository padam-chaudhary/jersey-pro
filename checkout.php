<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/dbConnection.php';  // Assumes this file creates a PDO connection stored in $pdo
require_once 'includes/functions.php';

// Redirect to cart if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    // Store current page as redirect destination after login
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('Location: login-signup.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($user_query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's saved addresses
$address_query = "SELECT * FROM user_addresses WHERE user_id = ?";
$stmt = $pdo->prepare($address_query);
$stmt->execute([$user_id]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate cart totals
$cart_items = [];
$subtotal = 0;
$tax_rate = 0.07; // 7% tax
$shipping_rate = 8.99;

foreach ($_SESSION['cart'] as $product_id => $item) {
    // Get product details from database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$product_id]);
    
    if ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Add product to cart items
        $cart_items[$product_id] = $product;
        $cart_items[$product_id]['quantity'] = $item['quantity'];
        $cart_items[$product_id]['size'] = $item['size'];
        $cart_items[$product_id]['customization'] = isset($item['customization']) ? $item['customization'] : null;
        
        // Calculate item subtotal
        $item_subtotal = $product['price'] * $item['quantity'];
        $cart_items[$product_id]['subtotal'] = $item_subtotal;
        $subtotal += $item_subtotal;
    }
}

// Calculate tax and total
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax + $shipping_rate;

// Process the order when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];
    
    // Shipping details validation
    $shipping_name = trim($_POST['shipping_name']);
    $shipping_address = trim($_POST['shipping_address']);
    $shipping_city = trim($_POST['shipping_city']);
    $shipping_state = trim($_POST['shipping_state']);
    $shipping_zip = trim($_POST['shipping_zip']);
    $shipping_country = trim($_POST['shipping_country']);
    $shipping_phone = trim($_POST['shipping_phone']);
    
    if (empty($shipping_name)) $errors[] = "Shipping name is required";
    if (empty($shipping_address)) $errors[] = "Shipping address is required";
    if (empty($shipping_city)) $errors[] = "City is required";
    if (empty($shipping_state)) $errors[] = "State is required";
    if (empty($shipping_zip)) $errors[] = "ZIP code is required";
    if (empty($shipping_country)) $errors[] = "Country is required";
    
    // Payment details validation
    $card_number = trim($_POST['card_number']);
    $card_name = trim($_POST['card_name']);
    $expiry_month = trim($_POST['expiry_month']);
    $expiry_year = trim($_POST['expiry_year']);
    $cvv = trim($_POST['cvv']);
    
    if (empty($card_number)) $errors[] = "Card number is required";
    if (empty($card_name)) $errors[] = "Cardholder name is required";
    if (empty($expiry_month)) $errors[] = "Expiry month is required";
    if (empty($expiry_year)) $errors[] = "Expiry year is required";
    if (empty($cvv)) $errors[] = "CVV is required";
    
    // If no errors, process the order
    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Create order in database
            $order_query = "INSERT INTO orders (user_id, total_amount, subtotal, tax, shipping, order_status) 
                           VALUES (?, ?, ?, ?, ?, 'pending')";
            $stmt = $pdo->prepare($order_query);
            $stmt->execute([$user_id, $total, $subtotal, $tax, $shipping_rate]);
            $order_id = $pdo->lastInsertId();
            
            // Create order details for each item
            foreach ($cart_items as $product_id => $item) {
                $item_price = $item['price'];
                $item_quantity = $item['quantity'];
                $item_size = $item['size'];
                $customization = isset($item['customization']) ? json_encode($item['customization']) : null;
                
                $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price, size, customization) 
                                    VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($order_item_query);
                $stmt->execute([$order_id, $product_id, $item_quantity, $item_price, $item_size, $customization]);
            }
            
            // Save shipping address
            $shipping_query = "INSERT INTO order_shipping (order_id, name, address, city, state, zip, country, phone) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($shipping_query);
            $stmt->execute([$order_id, $shipping_name, $shipping_address, $shipping_city, 
                         $shipping_state, $shipping_zip, $shipping_country, $shipping_phone]);
            
            // Save payment info (in a real system, you would use a payment processor)
            $payment_query = "INSERT INTO order_payments (order_id, payment_method, amount, status) 
                             VALUES (?, 'credit_card', ?, 'completed')";
            $stmt = $pdo->prepare($payment_query);
            $stmt->execute([$order_id, $total]);
            
            // Save address for future use if requested
            if (isset($_POST['save_address']) && $_POST['save_address'] == 1) {
                // Check if address already exists
                $check_query = "SELECT id FROM user_addresses WHERE user_id = ? AND address = ? AND city = ? AND state = ? AND zip = ?";
                $stmt = $pdo->prepare($check_query);
                $stmt->execute([$user_id, $shipping_address, $shipping_city, $shipping_state, $shipping_zip]);
                
                // If address doesn't exist, save it
                if ($stmt->rowCount() == 0) {
                    $save_address_query = "INSERT INTO user_addresses (user_id, name, address, city, state, zip, country, phone) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($save_address_query);
                    $stmt->execute([$user_id, $shipping_name, $shipping_address, $shipping_city, 
                                 $shipping_state, $shipping_zip, $shipping_country, $shipping_phone]);
                }
            }
            
            // Commit transaction
            $pdo->commit();
            
            // Clear the cart
            $_SESSION['cart'] = array();
            
            // Set success message and redirect to order confirmation
            $_SESSION['order_success'] = true;
            $_SESSION['order_id'] = $order_id;
            
            header('Location: order-confirmation.php?id=' . $order_id);
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            $error_message = "Sorry, there was an error processing your order. Please try again.";
        }
    }
}
?>

<div class="container my-5">
    <h1 class="mb-4">Checkout</h1>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h4>Please correct the following errors:</h4>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <form method="post" action="checkout.php">
                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td>
                                            <?php echo $item['name']; ?>
                                            <?php if (!empty($item['customization'])): ?>
                                                <br><small class="text-muted">
                                                    Name: <?php echo $item['customization']['name']; ?>, 
                                                    #<?php echo $item['customization']['number']; ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $item['size']; ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Shipping Information</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($addresses)): ?>
                            <div class="mb-3">
                                <label>Select a saved address:</label>
                                <select class="form-control" id="saved_address">
                                    <option value="">-- Select Address --</option>
                                    <?php foreach ($addresses as $address): ?>
                                        <option value="<?php echo $address['id']; ?>" 
                                                data-name="<?php echo $address['name']; ?>"
                                                data-address="<?php echo $address['address']; ?>"
                                                data-city="<?php echo $address['city']; ?>"
                                                data-state="<?php echo $address['state']; ?>"
                                                data-zip="<?php echo $address['zip']; ?>"
                                                data-country="<?php echo $address['country']; ?>"
                                                data-phone="<?php echo $address['phone']; ?>">
                                            <?php echo $address['name'] . ' - ' . $address['address'] . ', ' . $address['city']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_name">Full Name</label>
                                <input type="text" class="form-control" id="shipping_name" name="shipping_name" 
                                       value="<?php echo isset($shipping_name) ? $shipping_name : $user['name']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_phone">Phone Number</label>
                                <input type="text" class="form-control" id="shipping_phone" name="shipping_phone" 
                                       value="<?php echo isset($shipping_phone) ? $shipping_phone : $user['phone']; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_address">Address</label>
                            <input type="text" class="form-control" id="shipping_address" name="shipping_address" 
                                   value="<?php echo isset($shipping_address) ? $shipping_address : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="shipping_city">City</label>
                                <input type="text" class="form-control" id="shipping_city" name="shipping_city" 
                                       value="<?php echo isset($shipping_city) ? $shipping_city : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_state">State</label>
                                <input type="text" class="form-control" id="shipping_state" name="shipping_state" 
                                       value="<?php echo isset($shipping_state) ? $shipping_state : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_zip">ZIP Code</label>
                                <input type="text" class="form-control" id="shipping_zip" name="shipping_zip" 
                                       value="<?php echo isset($shipping_zip) ? $shipping_zip : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_country">Country</label>
                            <select class="form-control" id="shipping_country" name="shipping_country" required>
                                <option value="USA" <?php echo (isset($shipping_country) && $shipping_country == 'USA') ? 'selected' : ''; ?>>United States</option>
                                <option value="Canada" <?php echo (isset($shipping_country) && $shipping_country == 'Canada') ? 'selected' : ''; ?>>Canada</option>
                            </select>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="save_address" name="save_address" value="1">
                            <label class="form-check-label" for="save_address">Save this address for future orders</label>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Payment Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="card_name">Cardholder Name</label>
                            <input type="text" class="form-control" id="card_name" name="card_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="card_number">Card Number</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" 
                                   placeholder="XXXX XXXX XXXX XXXX" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="expiry_month">Expiry Month</label>
                                <select class="form-control" id="expiry_month" name="expiry_month" required>
                                    <option value="">Month</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?php echo sprintf('%02d', $i); ?>">
                                            <?php echo sprintf('%02d', $i); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="expiry_year">Expiry Year</label>
                                <select class="form-control" id="expiry_year" name="expiry_year" required>
                                    <option value="">Year</option>
                                    <?php $current_year = date('Y'); ?>
                                    <?php for ($i = $current_year; $i <= $current_year + 10; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cvv">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" 
                                       placeholder="XXX" maxlength="4" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success btn-lg btn-block mb-4">
                    <i class="fa fa-lock"></i> Place Order
                </button>
            </form>
        </div>
        
        <div class="col-md-4">
            <!-- Order Total -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Total</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-right">$<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Tax (<?php echo $tax_rate * 100; ?>%):</td>
                            <td class="text-right">$<?php echo number_format($tax, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Shipping:</td>
                            <td class="text-right">$<?php echo number_format($shipping_rate, 2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr></td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td class="text-right"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Need Help -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p>Have questions about your order?</p>
                    <p><i class="fa fa-phone"></i> Call us: (555) 123-4567</p>
                    <p><i class="fa fa-envelope"></i> Email: support@jerseypro.com</p>
                    <a href="contact.php" class="btn btn-outline-primary">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
<!-- JavaScript for saved address selection -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const savedAddressSelect = document.getElementById('saved_address');
    if (savedAddressSelect) {
        savedAddressSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                document.getElementById('shipping_name').value = selectedOption.getAttribute('data-name');
                document.getElementById('shipping_address').value = selectedOption.getAttribute('data-address');
                document.getElementById('shipping_city').value = selectedOption.getAttribute('data-city');
                document.getElementById('shipping_state').value = selectedOption.getAttribute('data-state');
                document.getElementById('shipping_zip').value = selectedOption.getAttribute('data-zip');
                document.getElementById('shipping_country').value = selectedOption.getAttribute('data-country');
                document.getElementById('shipping_phone').value = selectedOption.getAttribute('data-phone');
            }
        });
    }
});
</script>

