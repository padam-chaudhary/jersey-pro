
<?php 
session_start();
require_once 'includes/header.php';
require_once 'includes/dbConnection.php';  // Assumes this file creates a PDO connection stored in $pdo
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Jerseys - JERSEY PRO</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        /* Navigation Bar - Updated with gray/white color scheme */
        .navbar {
            display: flex;
            align-items: center;
            background-color: #ffffff; /* White background */
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
            color: #333333; /* Dark gray text */
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .logo-text .pro {
            color: #4a90e2; /* Accent blue */
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
            color: #555555; /* Medium gray text */
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
            color: #4a90e2; /* Accent blue */
            background-color: #f5f5f5; /* Light gray background on hover */
        }
        
        /* Icons for user and cart */
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
            background-color: #4a90e2; /* Accent blue */
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
        
        /* Banner for jerseys page */
        .page-banner {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/css/images/jerseys-banner.jpg');
            background-size: cover;
            background-position: center;
            height: 300px;
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
        
        /* Filter and Sort Section */
        .filter-section {
            background-color: white;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .filter-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        
        .filter-group label {
            margin-right: 10px;
            font-weight: 600;
            color: #555;
        }
        
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .search-box {
            display: flex;
            align-items: center;
        }
        
        .search-box input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            width: 200px;
        }
        
        .search-box button {
            padding: 8px 12px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        /* Products Grid */
        .products-section {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            height: 200px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-info h3 {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            color: #333;
        }
        
        .product-info .team {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px 15px;
        }
        
        .price {
            font-weight: bold;
            color: #4a90e2;
            font-size: 1.2rem;
        }
        
        .add-to-cart {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .add-to-cart:hover {
            background-color: #3a80d2;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }
        
        .pagination a {
            display: inline-block;
            padding: 8px 14px;
            margin: 0 3px;
            border-radius: 4px;
            background-color: white;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .pagination a.active {
            background-color: #4a90e2;
            color: white;
        }
        
        .pagination a:hover:not(.active) {
            background-color: #f5f5f5;
        }
        
        /* Footer - Updated with gray color scheme */
        footer {
            background-color: #f1f1f1; /* Light gray background */
            color: #333333; /* Dark gray text */
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
            color: #4a90e2; /* Accent blue */
            font-size: 1.2rem;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .contact p {
            margin: 8px 0;
            color: #555555; /* Medium gray text */
        }
        
        .social a {
            display: block;
            color: #555555; /* Medium gray text */
            text-decoration: none;
            margin: 8px 0;
            transition: color 0.3s;
        }
        
        .social a:hover {
            color: #4a90e2; /* Accent blue */
        }
        
        .footer-bottom {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 0.9rem;
            color: #777777; /* Light gray text */
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
            
            .page-banner {
                margin-top: 120px; /* Adjusted for mobile navbar */
                height: 200px;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }
            
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                width: 100%;
                margin-top: 10px;
            }
            
            .search-box input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
            <a href="home.php">
                <img src="assets/css/images/logo.png" alt="JERSEY PRO Logo">
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
                <span class="cart-count">0</span>
            </a></li>
        </ul>
    </nav>
    

    <section class="page-banner"> 
        <h1>Shop Premium Jerseys</h1>
    </section>

    <section class="filter-section">
        <div class="filter-container">
            <div class="filter-group">
                <label for="category">Sport:</label>
                <select id="category" name="category">
                    <option value="all">All Sports</option>
                    <option value="basketball">Basketball</option>
                    <option value="football">Football</option>
                    <option value="soccer">Soccer</option>
                    <option value="baseball">Baseball</option>
                    <option value="hockey">Hockey</option>
                </select>
            </div>
            <!-- <div class="filter-group">
                <label for="team">Team:</label>
                <select id="team" name="team">
                    <option value="all">All Teams</option>
                    <option value="nba">NBA Teams</option>
                    <option value="nfl">NFL Teams</option>
                    <option value="mlb">MLB Teams</option>
                    <option value="nhl">NHL Teams</option>
                    <option value="premier">Premier League</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="sort">Sort By:</label>
                <select id="sort" name="sort">
                    <option value="popular">Most Popular</option>
                    <option value="latest">Latest Arrivals</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                </select>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search jerseys...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div> -->
        </div>
    </section>

    <section class="products-section">
        <div class="products-grid">
            <!-- Product 1 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey1.jpg" alt="Los Angeles Lakers Jersey">
                </div>
                <div class="product-info">
                    <h3>LeBron James #23</h3>
                    <p class="team">Los Angeles Lakers</p>
                </div>
                <div class="product-footer">
                    <span class="price">$99.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey2.jpg" alt="Golden State Warriors Jersey">
                </div>
                <div class="product-info">
                    <h3>Stephen Curry #30</h3>
                    <p class="team">Golden State Warriors</p>
                </div>
                <div class="product-footer">
                    <span class="price">$99.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey3.jpg" alt="Kansas City Chiefs Jersey">
                </div>
                <div class="product-info">
                    <h3>Patrick Mahomes #15</h3>
                    <p class="team">Kansas City Chiefs</p>
                </div>
                <div class="product-footer">
                    <span class="price">$129.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey4.jpg" alt="Liverpool FC Jersey">
                </div>
                <div class="product-info">
                    <h3>Mohamed Salah #11</h3>
                    <p class="team">Liverpool FC</p>
                </div>
                <div class="product-footer">
                    <span class="price">$89.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 5 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey5.jpg" alt="New York Yankees Jersey">
                </div>
                <div class="product-info">
                    <h3>Aaron Judge #99</h3>
                    <p class="team">New York Yankees</p>
                </div>
                <div class="product-footer">
                    <span class="price">$109.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 6 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey6.jpg" alt="Denver Nuggets Jersey">
                </div>
                <div class="product-info">
                    <h3>Nikola Jokić #15</h3>
                    <p class="team">Denver Nuggets</p>
                </div>
                <div class="product-footer">
                    <span class="price">$99.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 7 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey7.jpg" alt="Barcelona FC Jersey">
                </div>
                <div class="product-info">
                    <h3>Lamine Yamal #27</h3>
                    <p class="team">Barcelona FC</p>
                </div>
                <div class="product-footer">
                    <span class="price">$89.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
            
            <!-- Product 8 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/css/images/jersey8.jpg" alt="Toronto Maple Leafs Jersey">
                </div>
                <div class="product-info">
                    <h3>Auston Matthews #34</h3>
                    <p class="team">Toronto Maple Leafs</p>
                </div>
                <div class="product-footer">
                    <span class="price">$119.99</span>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
        
        <!-- <div class="pagination">
            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
            <a href="#">&raquo;</a>
        </div> -->
    </section>

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
    <script src="assets/js/main.js"></script>
</body>
</html>