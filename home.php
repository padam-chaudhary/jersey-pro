<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JERSEY PRO - Your Game, Your Gear</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        /* Hero Section with Background Image */
        .hero {
            background-image: url('assets/css/images/jerseys-image.jpg'); /* Update this path to your actual image */
            background-size: cover;
            background-position: center;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 100px 0;
            margin-top: 74px; /* To account for the fixed navbar */
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
        
        .hero .cta {
            display: inline-block;
            background-color: #4a90e2; /* Accent blue */
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .hero .cta:hover {
            background-color: #3a80d2;
            transform: translateY(-2px);
        }
        
        /* About Section */
        .about {
            padding: 60px 20px;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about h2 {
            color: #333333; /* Dark gray text */
            font-size: 2rem;
            margin-bottom: 20px;
        }
        
        .about p {
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .feature {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .feature:hover {
            transform: translateY(-5px);
        }
        
        .feature h3 {
            color: #333333; /* Dark gray text */
            margin-bottom: 15px;
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
            
            .hero {
                margin-top: 120px; /* Adjusted for mobile navbar */
                height: 400px;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .about-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                flex-direction: column;
                align-items: center;
            }
            
            .contact, .social {
                text-align: center;
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

    <section class="hero">
        <div class="hero-content">
            <h1>Gear Up For Greatness</h1>
            <p>Premium quality jerseys for athletes and fans. Authentic designs, superior comfort, unmatched style.</p>
            <a href="jerseys.php" class="cta">Shop Now</a>
        </div>
    </section>

    <section class="about">
        <h2>Why Choose Jersey Pro?</h2>
        <p>The ultimate destination for sports enthusiasts and athletes looking for high-quality, authentic jerseys.</p>
        <div class="about-grid">
            <div class="feature">
                <h3>🏆 Premium Quality</h3>
                <p>Authentic materials and professional craftsmanship for maximum comfort.</p>
            </div>
            <div class="feature">
                <h3>👕 Wide Selection</h3>
                <p>From basketball to soccer, find jerseys for every sport and team.</p>
            </div>
            <div class="feature">
                <h3>🚚 Fast Shipping</h3>
                <p>Quick delivery to get you game-ready in no time.</p>
            </div>
        </div>
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