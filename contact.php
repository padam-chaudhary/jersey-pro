<?php 
include_once 'includes/header.php'; // Include the header file
?>

    <style>
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
        
       
    </style>
</head>
<body>
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
        <iframe  class="map-iframe"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d28273.910348289897!2d85.28411827365363!3d27.64808274351311!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb178514f97739%3A0xeb1f6e5c822e62ab!2sBhaisepati%2C%20Karyabinayak!5e0!3m2!1sen!2snp!4v1745376655628!5m2!1sen!2snp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>