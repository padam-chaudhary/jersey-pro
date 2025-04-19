<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - JERSEY PRO</title>
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
        
        /* Page Banner */
        .page-banner {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/css/images/about-banner.jpg');
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
        
        /* About Us Content */
        .about-section {
            padding: 60px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .about-content {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }
        
        .about-content h2 {
            color: #4a90e2;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .about-content p {
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555;
        }
        
        .founder-section {
            display: flex;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .founder {
            flex: 1;
            min-width: 250px;
            margin: 15px;
            text-align: center;
        }
        
        .founder img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #4a90e2;
        }
        
        .founder h3 {
            margin: 10px 0 5px 0;
            color: #333;
        }
        
        .founder p {
            color: #777;
            font-size: 0.9rem;
        }
        
        .mission-vision {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
        }
        
        .mission, .vision {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .mission h2, .vision h2 {
            color: #4a90e2;
            margin-top: 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .mission h2 i, .vision h2 i {
            margin-right: 10px;
            font-size: 1.8rem;
        }
        
        /* Timeline */
        .timeline-section {
            padding: 60px 20px;
            background-color: #f1f1f1;
        }
        
        .timeline-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .timeline-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
            font-size: 2rem;
        }
        
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: #4a90e2;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            right: -12px;
            background-color: white;
            border: 4px solid #4a90e2;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }
        
        .timeline-item.left {
            left: 0;
        }
        
        .timeline-item.right {
            left: 50%;
        }
        
        .timeline-item.right::after {
            left: -12px;
        }
        
        .timeline-content {
            padding: 20px;
            background-color: white;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .timeline-content h3 {
            margin-top: 0;
            color: #4a90e2;
        }
        
        .timeline-content .date {
            color: #777;
            font-style: italic;
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
            
            .page-banner {
                margin-top: 120px; /* Adjusted for mobile navbar */
                height: 200px;
            }
            
            .mission-vision {
                grid-template-columns: 1fr;
            }
            
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item.right {
                left: 0%;
            }
            
            .timeline-item.left::after, .timeline-item.right::after {
                left: 18px;
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
        <h1>About Jersey Pro</h1>
    </section>

    <section class="about-section">
        <div class="about-content">
            <h2>Our Story</h2>
            <p>Founded in 2020, Jersey Pro emerged from a simple passion for sports and quality athletic wear. What began as a small online store run by sports enthusiasts has grown into a premier destination for authentic sports jerseys across multiple sports and leagues.</p>
            <p>Our journey started when our founders, avid sports fans themselves, recognized the need for high-quality, authentic jerseys that wouldn't break the bank. They embarked on a mission to create a platform where fans and athletes alike could find premium jerseys representing their favorite teams and players.</p>
            <p>Today, Jersey Pro is proud to offer one of the most comprehensive collections of authentic sports jerseys in Nepal, serving thousands of satisfied customers nationwide. We've built our reputation on quality, authenticity, and exceptional customer service.</p>
            
            <h2>Meet Our Team</h2>
            <div class="founder-section">
                <div class="founder">
                    <img src="assets/css/images/founder1.jpg" alt="Raj Sharma">
                    <h3>Raj Sharma</h3>
                    <p>Co-Founder & CEO</p>
                </div>
                <div class="founder">
                    <img src="assets/css/images/founder2.jpg" alt="Anup Bhandari">
                    <h3>Anup Bhandari</h3>
                    <p>Co-Founder & COO</p>
                </div>
                <div class="founder">
                    <img src="assets/css/images/founder3.jpg" alt="Sabina Tamang">
                    <h3>Sabina Tamang</h3>
                    <p>Marketing Director</p>
                </div>
            </div>
        </div>
        
        <div class="mission-vision">
            <div class="mission">
                <h2><i class="fas fa-bullseye"></i> Our Mission</h2>
                <p>To provide sports enthusiasts with authentic, high-quality jerseys that celebrate their passion for the game, delivered with exceptional customer service and at fair prices.</p>
            </div>
            <div class="vision">
                <h2><i class="fas fa-eye"></i> Our Vision</h2>
                <p>To be the leading provider of authentic sports jerseys in Nepal, connecting fans with their favorite teams and players through premium quality products that inspire pride and passion.</p>
            </div>
        </div>
    </section>

    <section class="timeline-section">
        <div class="timeline-container">
            <h2>Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>The Beginning</h3>
                        <p class="date">April 2020</p>
                        <p>Jersey Pro was founded as an online store with a small collection of basketball jerseys.</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h3>Expansion</h3>
                        <p class="date">December 2020</p>
                        <p>Added football and soccer jerseys to our collection, expanding our product range.</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>First Physical Store</h3>
                        <p class="date">June 2021</p>
                        <p>Opened our first physical retail location in Lalitpur.</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h3>Partnership with Teams</h3>
                        <p class="date">October 2022</p>
                        <p>Established official partnerships with local sports teams.</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>Today</h3>
                        <p class="date">2025</p>
                        <p>Recognized as Nepal's leading sports jersey retailer with a loyal customer base.</p>
                    </div>
                </div>
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