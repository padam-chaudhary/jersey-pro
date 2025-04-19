<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - JERSEY PRO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        /* Navigation Bar */
        .navbar {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            padding: 12px 24px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-sizing: border-box;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-bottom: 1px solid #eaeaea;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        
        .logo-container img {
            height: 50px;
            margin-right: 10px;
            border-radius: 6px;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .logo-text .jersey {
            color: #333333;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .logo-text .pro {
            color: #4a90e2;
            font-size: 16px;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
            margin-left: auto;
            align-items: center;
        }
        
        .nav-links li {
            margin-left: 20px;
        }
        
        .nav-links a {
            color: #555555;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }
        
        .nav-links a:hover {
            color: #4a90e2;
            background-color: #f5f5f5;
        }
        
        .icon-link {
            position: relative;
        }
        
        .icon-link i {
            font-size: 18px;
            margin-right: 5px;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #4a90e2;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        
        /* Cart Page Content */
        .page-container {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }
        
        .page-title {
            color: #333333;
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .cart-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .cart-empty {
            padding: 60px 20px;
            text-align: center;
        }
        
        .cart-empty i {
            font-size: 4rem;
            color: #d1d1d1;
            margin-bottom: 20px;
        }
        
        .cart-empty h3 {
            font-size: 1.5rem;
            color: #555;
            margin-bottom: 20px;
        }
        
        .cart-empty .continue-shopping {
            display: inline-block;
            background-color: #4a90e2;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .cart-empty .continue-shopping:hover {
            background-color: #3a80d2;
            transform: translateY(-2px);
        }
        
        .cart-header {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr auto;
            padding: 15px 20px;
            border-bottom: 1px solid #eaeaea;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        
        .cart-item {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr auto;
            padding: 20px;
            border-bottom: 1px solid #eaeaea;
            align-items: center;
        }
        
        .item-info {
            display: flex;
            align-items: center;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
            border: 1px solid #eaeaea;
        }
        
        .item-details h3 {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
        }
        
        .item-details p {
            margin: 0;
            color: #777;
            font-size: 0.9rem;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            background-color: #f1f1f1;
            border: none;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
        }
        
        .quantity-btn:hover {
            background-color: #e0e0e0;
        }
        
        .quantity-input {
            width: 40px;
            height: 30px;
            text-align: center;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            margin: 0 5px;
        }
        
        .item-price, .item-total {
            font-weight: 600;
            text-align: center;
        }
        
        .item-remove {
            color: #ff5252;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.2s;
        }
        
        .item-remove:hover {
            color: #ff0000;
        }
        
        .cart-summary {
            padding: 20px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .promo-code {
            display: flex;
            gap: 10px;
        }
        
        .promo-input {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            width: 200px;
        }
        
        .apply-promo {
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .apply-promo:hover {
            background-color: #3a80d2;
        }
        
        .cart-totals {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
            width: 300px;
        }
        
        .totals-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .totals-row:last-of-type {
            border-bottom: none;
            margin-bottom: 20px;
        }
        
        .grand-total {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }
        
        .checkout-btn {
            display: block;
            width: 100%;
            background-color: #4a90e2;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .checkout-btn:hover {
            background-color: #3a80d2;
        }
        
        .continue-shopping-btn {
            display: block;
            width: 100%;
            background-color: transparent;
            color: #4a90e2;
            padding: 10px;
            border: 1px solid #4a90e2;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            transition: background-color 0.3s, color 0.3s;
        }
        
        .continue-shopping-btn:hover {
            background-color: #f0f7ff;
        }
        
        /* Footer */
        footer {
            background-color: #f1f1f1;
            color: #333333;
            padding: 40px 20px 20px 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            gap: 30px;
        }
        
        .contact h4, .social h4 {
            color: #4a90e2;
            font-size: 1.2rem;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .contact p {
            margin: 8px 0;
            color: #555555;
        }
        
        .social a {
            display: block;
            color: #555555;
            text-decoration: none;
            margin: 8px 0;
            transition: color 0.3s;
        }
        
        .social a:hover {
            color: #4a90e2;
        }
        
        .footer-bottom {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 0.9rem;
            color: #777777;
        }
        
        /* Responsive Design */
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
            
            .page-container {
                margin-top: 150px;
            }
            
            .cart-header {
                display: none;
            }
            
            .cart-item {
                grid-template-columns: 1fr;
                grid-gap: 15px;
                text-align: center;
                padding: 20px 10px;
            }
            
            .item-info {
                flex-direction: column;
                align-items: center;
            }
            
            .item-image {
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            .item-price::before {
                content: "Price: ";
                font-weight: normal;
            }
            
            .item-total::before {
                content: "Total: ";
                font-weight: normal;
            }
            
            .cart-summary {
                flex-direction: column;
                gap: 20px;
            }
            
            .promo-code {
                width: 100%;
                flex-direction: column;
            }
            
            .promo-input {
                width: 100%;
            }
            
            .cart-totals {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
            <a href="home.php">
                <img src="/api/placeholder/50/50" alt="JERSEY PRO Logo">
            </a>
            <div class="logo-text">
                <span class="jersey">JERSEY</span>
                <span class="pro">PRO</span>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="jerseys.php">Jerseys</a></li>
            <li><a href="aboutus.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login-signup.php" class="icon-link"><i class="fas fa-user"></i> </a></li>
            <li><a href="cart.php" class="icon-link">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">3</span>
            </a></li>
        </ul>
    </nav>

    <div class="page-container">
        <h1 class="page-title">Your Shopping Cart</h1>
        
        <div class="cart-container">
            <!-- If cart has items -->
            <div class="cart-with-items">
                <div class="cart-header">
                    <div>Product</div>
                    <div style="text-align: center;">Price</div>
                    <div style="text-align: center;">Quantity</div>
                    <div style="text-align: center;">Total</div>
                    <div></div>
                </div>
                
                <!-- Cart Item 1 -->
                <div class="cart-item">
                    <div class="item-info">
                        <img src="/api/placeholder/80/80" alt="NBA Lakers Jersey" class="item-image">
                        <div class="item-details">
                            <h3>NBA Lakers Jersey</h3>
                            <p>Size: L | Color: Yellow</p>
                        </div>
                    </div>
                    <div class="item-price">$89.99</div>
                    <div class="quantity-selector">
                        <button class="quantity-btn">-</button>
                        <input type="text" class="quantity-input" value="1">
                        <button class="quantity-btn">+</button>
                    </div>
                    <div class="item-total">$89.99</div>
                    <button class="item-remove"><i class="fas fa-trash"></i></button>
                </div>
                
                <!-- Cart Item 2 -->
                <div class="cart-item">
                    <div class="item-info">
                        <img src="/api/placeholder/80/80" alt="Premier League Chelsea Jersey" class="item-image">
                        <div class="item-details">
                            <h3>Premier League Chelsea Jersey</h3>
                            <p>Size: M | Color: Blue</p>
                        </div>
                    </div>
                    <div class="item-price">$79.99</div>
                    <div class="quantity-selector">
                        <button class="quantity-btn">-</button>
                        <input type="text" class="quantity-input" value="1">
                        <button class="quantity-btn">+</button>
                    </div>
                    <div class="item-total">$79.99</div>
                    <button class="item-remove"><i class="fas fa-trash"></i></button>
                </div>
                
                <!-- Cart Item 3 -->
                <div class="cart-item">
                    <div class="item-info">
                        <img src="/api/placeholder/80/80" alt="NFL Patriots Jersey" class="item-image">
                        <div class="item-details">
                            <h3>NFL Patriots Jersey</h3>
                            <p>Size: XL | Color: Navy</p>
                        </div>
                    </div>
                    <div class="item-price">$99.99</div>
                    <div class="quantity-selector">
                        <button class="quantity-btn">-</button>
                        <input type="text" class="quantity-input" value="1">
                        <button class="quantity-btn">+</button>
                    </div>
                    <div class="item-total">$99.99</div>
                    <button class="item-remove"><i class="fas fa-trash"></i></button>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="promo-code">
                        <input type="text" class="promo-input" placeholder="Promo Code">
                        <button class="apply-promo">Apply</button>
                    </div>
                    
                    <div class="cart-totals">
                        <h3 class="totals-title">Order Summary</h3>
                        <div class="totals-row">
                            <span>Subtotal</span>
                            <span>$269.97</span>
                        </div>
                        <div class="totals-row">
                            <span>Shipping</span>
                            <span>$15.00</span>
                        </div>
                        <div class="totals-row">
                            <span>Tax</span>
                            <span>$21.60</span>
                        </div>
                        <div class="totals-row grand-total">
                            <span>Total</span>
                            <span>$306.57</span>
                        </div>
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                        <a href="jerseys.php" class="continue-shopping-btn">Continue Shopping</a>
                    </div>
                </div>
            </div>
            
            <!-- If cart is empty (hidden by default, toggle with JavaScript in real implementation) -->
            <div class="cart-empty" style="display: none;">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added any jerseys to your cart yet.</p>
                <a href="jerseys.php" class="continue-shopping">Shop Now</a>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="contact">
                <h4>Contact Us</h4>
                <p>Email: support@jerseypro.com</p>
                <p>Phone: 01-5570204</p>
                <p>Address: Lalitpur metropoliton city-25, Bhainsepati, Lalitpur</p>
            </div>
            <div class="social">
                <h4>Follow Us</h4>
                <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
            </div>
        </div>
        <p class="footer-bottom">&copy; 2025 JERSEY PRO. All Rights Reserved.</p>
    </footer>

    <script>
        // Simple JavaScript for cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Get all quantity buttons and inputs
            const minusBtns = document.querySelectorAll('.quantity-btn:first-child');
            const plusBtns = document.querySelectorAll('.quantity-btn:last-child');
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const removeBtns = document.querySelectorAll('.item-remove');
            
            // Add event listeners to minus buttons
            minusBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    let currentVal = parseInt(quantityInputs[index].value);
                    if (currentVal > 1) {
                        quantityInputs[index].value = currentVal - 1;
                        updateItemTotal(index);
                    }
                });
            });
            
            // Add event listeners to plus buttons
            plusBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    let currentVal = parseInt(quantityInputs[index].value);
                    quantityInputs[index].value = currentVal + 1;
                    updateItemTotal(index);
                });
            });
            
            // Add event listeners to quantity inputs
            quantityInputs.forEach((input, index) => {
                input.addEventListener('change', function() {
                    if (this.value < 1 || isNaN(this.value)) {
                        this.value = 1;
                    }
                    updateItemTotal(index);
                });
            });
            
            // Add event listeners to remove buttons
            removeBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const cartItem = this.closest('.cart-item');
                    cartItem.remove();
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        document.querySelector('.cart-with-items').style.display = 'none';
                        document.querySelector('.cart-empty').style.display = 'block';
                        
                        // Update cart count
                        document.querySelector('.cart-count').textContent = '0';
                    } else {
                        // Recalculate totals
                        calculateCartTotals();
                        
                        // Update cart count
                        document.querySelector('.cart-count').textContent = remainingItems.length;
                    }
                });
            });
            
            // Function to update item total
            function updateItemTotal(index) {
                const priceElement = document.querySelectorAll('.item-price')[index];
                const totalElement = document.querySelectorAll('.item-total')[index];
                const quantity = parseInt(quantityInputs[index].value);
                
                const price = parseFloat(priceElement.textContent.replace('$', ''));
                const total = price * quantity;
                
                totalElement.textContent = '$' + total.toFixed(2);
                
                // Recalculate cart totals
                calculateCartTotals();
            }
            
            // Function to calculate cart totals
            function calculateCartTotals() {
                let subtotal = 0;
                
                document.querySelectorAll('.item-total').forEach(item => {
                    subtotal += parseFloat(item.textContent.replace('$', ''));
                });
                
                const shipping = 15.00;
                const tax = subtotal * 0.08; // 8% tax rate
                const total = subtotal + shipping + tax;
                
                // Update summary
                const totalsRows = document.querySelectorAll('.totals-row');
                totalsRows[0].querySelector('span:last-child').textContent = '$' + subtotal.toFixed(2);
                totalsRows[1].querySelector('span:last-child').textContent = '$' + shipping.toFixed(2);
                totalsRows[2].querySelector('span:last-child').textContent = '$' + tax.toFixed(2);
                totalsRows[3].querySelector('span:last-child').textContent = '$' + total.toFixed(2);
            }
        });
    </script>
</body>
</html>