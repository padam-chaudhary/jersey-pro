<?php 
include_once 'includes/header.php';
?>

    <style>
        /* Hero Section with Background Image */
        .hero {
            background-image: url('assets/images/jerseys-image.jpg'); /* Update this path to your actual image */
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
    </style>
</head>
<body>
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

   <?php 
   include_once 'includes/footer.php';
   
   ?>
    <script src="assets/js/main.js"></script>
</body>
</html>