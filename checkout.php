<?php 
session_start();
require_once 'includes/header.php';
require_once 'includes/dbConnection.php';  // Assumes this file creates a PDO connection stored in $pdo
// require_once 'includes/functions.php';

// Redirect to cart if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php?message=' . urlencode('Your cart is empty. Please add items before checkout.'));
    exit;
}

// Calculate cart total and get items
$cart_count = 0;
$cart_total = 0;
$cart_items = [];

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
            'price' => $product['price'],
            'quantity' => $item['quantity'],
            'item_total' => $item_total
        ];
    }
}

// Shipping cost calculation
$shipping_cost = 0;
if ($cart_total < 100) {
    $shipping_cost = 9.99;
}

// Calculate final total
$final_total = $cart_total + $shipping_cost;

// Process checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form submission
    $errors = [];
    
    // Basic validation
    if (empty($_POST['first_name'])) $errors[] = "First name is required";
    if (empty($_POST['last_name'])) $errors[] = "Last name is required";
    if (empty($_POST['email'])) $errors[] = "Email is required";
    if (empty($_POST['address'])) $errors[] = "Address is required";
    if (empty($_POST['city'])) $errors[] = "City is required";
    if (empty($_POST['postal_code'])) $errors[] = "Postal code is required";
    if (empty($_POST['country'])) $errors[] = "Country is required";
    if (empty($_POST['payment_method'])) $errors[] = "Payment method is required";
    
    // If payment method is credit card, validate card details
    if ($_POST['payment_method'] === 'credit_card') {
        if (empty($_POST['card_number'])) $errors[] = "Card number is required";
        if (empty($_POST['card_expiry'])) $errors[] = "Card expiry date is required";
        if (empty($_POST['card_cvv'])) $errors[] = "CVV is required";
    }
    
    // Process order if no errors
    if (empty($errors)) {
        // In a real application, you would:
        // 1. Save order to database
        // 2. Process payment
        // 3. Send confirmation email
        // 4. Clear cart
        
        // For demo, just clear cart and redirect to confirmation
        $order_id = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
        $_SESSION['last_order_id'] = $order_id;
        $_SESSION['last_order_total'] = $final_total;
        $_SESSION['cart'] = [];
        
        header('Location: order_confirmation.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - JERSEY PRO</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Page Banner */
        .page-banner {
            background-color: #4a90e2; /* Accent blue */
            background-size: cover;
            background-position: center;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 74px; /* To account for the fixed navbar */
        }
        
        .page-banner h1 {
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        /* Checkout Section */
        .checkout-section {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        /* Form Styles */
        .checkout-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a90e2;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .form-column {
            flex: 1;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            color: #333;
            background-color: #f9f9f9;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }
        
        .radio-group {
            margin-top: 10px;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .radio-option:hover {
            background-color: #f0f7ff;
            border-color: #4a90e2;
        }
        
        .radio-option.selected {
            background-color: #ebf5ff;
            border-color: #4a90e2;
        }
        
        .radio-option input {
            width: auto;
            margin-right: 10px;
        }
        
        .payment-details {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            display: none;
        }
        
        .payment-details.active {
            display: block;
        }
        
        .card-icons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .card-icons i {
            font-size: 24px;
            color: #555;
        }
        
        .required {
            color: #ff4747;
        }
        
        .error-message {
            color: #ff4747;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        /* Order Summary */
        .order-summary {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
            align-self: flex-start;
            position: sticky;
            top: 100px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .summary-item:last-of-type {
            border-bottom: none;
        }
        
        .product-name {
            font-weight: 600;
        }
        
        .quantity {
            color: #777;
            font-size: 0.9rem;
        }
        
        .subtotal, .shipping, .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .total-row {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #eaeaea;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .total-amount {
            color: #4a90e2;
        }
        
        .free-shipping {
            color: #4CAF50;
            font-size: 0.9rem;
        }
        
        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .checkout-btn:hover {
            background-color: #3d9c40;
        }
        
        .back-to-cart {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #555;
            text-decoration: none;
        }
        
        .back-to-cart:hover {
            color: #4a90e2;
        }
        
        .secure-checkout {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            color: #777;
            font-size: 0.9rem;
        }
        
        .secure-checkout i {
            margin-right: 5px;
            color: #4CAF50;
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 4px;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        /* Responsive Design */
        @media (max-width: 992px) {
            .checkout-section {
                grid-template-columns: 1fr;
            }
            
            .order-summary {
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 10px;
            }
            
            .logo-container {
                margin-bottom: 10px;
            }
            
            .nav-links {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .nav-links li {
                margin: 5px 10px;
            }
            
            .page-banner {
                margin-top: 120px; /* Adjusted for mobile navbar */
                height: 150px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
 <section class="page-banner">
        <h1>Checkout</h1>
    </section>

    <section class="checkout-section">
        <!-- Checkout Form -->
        <div class="checkout-form">
            <h2 class="section-title">Shipping Information</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error-summary">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li class="error-message"><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="checkout.php" id="checkout-form">
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="required">*</span></label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="required">*</span></label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="address">Address <span class="required">*</span></label>
                    <input type="text" id="address" name="address" required>
                </div>
                
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="city">City <span class="required">*</span></label>
                            <input type="text" id="city" name="city" required>
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label for="postal_code">Postal/ZIP Code <span class="required">*</span></label>
                            <input type="text" id="postal_code" name="postal_code" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="nepal">Nepal Country</label>
                   <input type="text" id="country" name="country" value="Nepal" readonly>
                </div>
            </form>
        </div>
        
        <!-- Order Summary and Payment Method -->
        <div>
            <!-- Payment Method -->
            <div class="order-summary" style="margin-bottom: 20px;">
                <h2 class="section-title">Payment Method</h2>
                
                <div class="form-group">
                    <div class="radio-group">
                        <div class="radio-option selected" data-payment="khalti">
                            <input type="radio" id="payment_khalti" name="payment_method" value="khalti" form="checkout-form" checked>
                            <label for="payment_khalti">Khalti</label>
                        </div>
                        
                        <div class="payment-details active" id="khalti_details">
                            <p>You will be redirected to Khalti to complete your payment.</p>
                        </div>
                        
                        <div class="radio-option" data-payment="cash_on_delivery">
                            <input type="radio" id="payment_cod" name="payment_method" value="cash_on_delivery" form="checkout-form">
                            <label for="payment_cod">Cash On Delivery</label>
                        </div>
                        
                        <div class="payment-details" id="cash_on_delivery_details">
                            <p>Pay with cash upon delivery. Available only for selected locations.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="section-title">Order Summary</h2>
                
                <?php foreach ($cart_items as $item): ?>
                    <div class="summary-item">
                        <div>
                            <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="quantity">Qty: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div>$<?php echo number_format($item['item_total'], 2); ?></div>
                    </div>
                <?php endforeach; ?>
                
                <div class="subtotal">
                    <div>Subtotal</div>
                    <div>$<?php echo number_format($cart_total, 2); ?></div>
                </div>
                
                <div class="shipping">
                    <div>Shipping</div>
                    <?php if ($shipping_cost > 0): ?>
                        <div>$<?php echo number_format($shipping_cost, 2); ?></div>
                    <?php else: ?>
                        <div class="free-shipping">FREE</div>
                    <?php endif; ?>
                </div>
                
                <div class="total-row">
                    <div>Total</div>
                    <div class="total-amount">$<?php echo number_format($final_total, 2); ?></div>
                </div>
                
                <!-- Move Complete Order button here -->
                <button type="submit" form="checkout-form" class="checkout-btn">Complete Order</button>
                
                <div class="secure-checkout">
                    <i class="fas fa-lock"></i> Secure Checkout
                </div>
                
                <a href="cart.php" class="back-to-cart">
                    <i class="fas fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>
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
        
        // Payment method selection
        const radioOptions = document.querySelectorAll('.radio-option');
        const paymentDetails = document.querySelectorAll('.payment-details');
        
        radioOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Update selected state
                radioOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                
                // Check the radio button
                const radioInput = this.querySelector('input[type="radio"]');
                radioInput.checked = true;
                
                // Show/hide payment details
                const paymentMethod = this.getAttribute('data-payment');
                paymentDetails.forEach(detail => detail.classList.remove('active'));
                document.getElementById(paymentMethod + '_details').classList.add('active');
            });
        });
        
        // Form validation
        const checkoutForm = document.getElementById('checkout-form');
        checkoutForm.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Get selected payment method
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
            
            // Validate required fields
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ff4747';
                    
                    // Add error message if it doesn't exist
                    let errorSpan = field.nextElementSibling;
                    if (!errorSpan || !errorSpan.classList.contains('error-message')) {
                        errorSpan = document.createElement('span');
                        errorSpan.classList.add('error-message');
                        errorSpan.textContent = 'This field is required';
                        field.parentNode.insertBefore(errorSpan, field.nextSibling);
                    }
                } else {
                    field.style.borderColor = '#ddd';
                    
                    // Remove error message if it exists
                    let errorSpan = field.nextElementSibling;
                    if (errorSpan && errorSpan.classList.contains('error-message')) {
                        errorSpan.remove();
                    }
                }
            });
            
            // Validate email format
            const emailField = document.getElementById('email');
            if (emailField.value && !validateEmail(emailField.value)) {
                isValid = false;
                emailField.style.borderColor = '#ff4747';
                
                // Add error message if it doesn't exist
                let errorSpan = emailField.nextElementSibling;
                if (!errorSpan || !errorSpan.classList.contains('error-message')) {
                    errorSpan = document.createElement('span');
                    errorSpan.classList.add('error-message');
                    errorSpan.textContent = 'Please enter a valid email address';
                    emailField.parentNode.insertBefore(errorSpan, emailField.nextSibling);
                }
            }
            
            // If form is not valid, prevent submission
            if (!isValid) {
                event.preventDefault();
                showToast('Please check the form for errors');
                
                // Scroll to the first error
                const firstError = document.querySelector('.error-message');
                if (firstError) {
                    firstError.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Helper functions for validation
        function validateEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        
    });
    </script>
</body>
</html>