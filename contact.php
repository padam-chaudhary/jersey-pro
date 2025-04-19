<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - JERSEY PRO</title>
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
        
        /* Contact Page Specific Styles */
        .page-header {
            background-color: #4a90e2;
            color: white;
            padding: 100px 0 50px;
            text-align: center;
            margin-top: 74px; /* To account for the fixed navbar */
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .contact-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        
        .contact-info {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .contact-info h2 {
            color: #333333;
            margin-bottom: 25px;
            font-size: 1.8rem;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .contact-item {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
        }
        
        .contact-item i {
            color: #4a90e2;
            font-size: 20px;
            margin-right: 15px;
            width: 25px;
            text-align: center;
        }
        
        .contact-details {
            flex: 1;
        }
        
        .contact-details h3 {
            margin: 0 0 5px;
            font-size: 1.1rem;
            color: #555;
        }
        
        .contact-details p {
            margin: 0;
            color: #666;
            line-height: 1.5;
        }
        
        .contact-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .contact-form h2 {
            color: #333333;
            margin-bottom: 25px;
            font-size: 1.8rem;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #4a90e2;
            outline: none;
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn-submit {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        
        .btn-submit:hover {
            background-color: #3a80d2;
            transform: translateY(-2px);
        }
        
        .map-container {
            margin-top: 40px;
            padding: 0 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .map-container h2 {
            color: #333333;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.8rem;
        }
        
        .map-iframe {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        /* Footer - Updated with gray color scheme */
        footer {
            background-color: #f1f1f1; /* Light gray background */
            color: #333333; /* Dark gray text */
            padding: 40px 20px 20px 20px;
            border-top: 1px solid #e0e0e0;
            margin-top: 60px;
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
            
            .page-header {
                margin-top: 120px; /* Adjusted for mobile navbar */
                padding: 60px 0 30px;
            }
            
            .contact-container {
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

    <header class="page-header">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Get in touch with our team.</p>
    </header>

    <div class="contact-container">
        <div class="contact-info">
            <h2>Our Information</h2>
            
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div class="contact-details">
                    <h3>Address</h3>
                    <p>Lalitpur Metropolitan City-25, Bhainsepati, Lalitpur</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-phone-alt"></i>
                <div class="contact-details">
                    <h3>Phone</h3>
                    <p>01-5570204</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div class="contact-details">
                    <h3>Email</h3>
                    <p>support@jerseypro.com</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-clock"></i>
                <div class="contact-details">
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                    <p>Saturday: 10:00 AM - 4:00 PM</p>
                    <p>Sunday: Closed</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-comments"></i>
                <div class="contact-details">
                    <h3>Social Media</h3>
                    <p>
                        <a href="#" style="color: #4a90e2; margin-right: 15px;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: #4a90e2; margin-right: 15px;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: #4a90e2; margin-right: 15px;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: #4a90e2;"><i class="fab fa-youtube"></i></a>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="contact-form">
            <h2>Send a Message</h2>
            <form action="process_contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                
                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </div>
    </div>

    <div class="map-container">
        <h2>Find Us</h2>
        <!-- Replace with actual Google Maps embed code -->
        <iframe 
            class="map-iframe"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3533.0135662414198!2d85.31274361504438!3d27.6783031828071!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19d3cf395dcf%3A0x67c241fd35fe7a9!2sBhainsepati%2C%20Lalitpur%2044700!5e0!3m2!1sen!2snp!4v1650018599752!5m2!1sen!2snp" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>

    <!-- <footer>
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
        </div> -->
        <p class="footer-bottom">&copy; 2025 JERSEY PRO. All Rights Reserved.</p>
    </footer>
    <script src="assets/js/main.js"></script>
</body>
</html>