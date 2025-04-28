<?php 
include 'includes/header.php'; // Include the header file

?>

    <style>
            /* Page Banner */
        .page-banner {
            background-color: #4a90e2;
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
        
    </style>
</head>
<body>
    <section class="page-banner">
        <h1>About Jersey Pro</h1>
    </section>

    <section class="about-section">
        <div class="about-content">
            <h2>Our Story</h2>
            <p>Founded in 2020, Jersey Pro emerged from a simple passion for sports and quality athletic wear. What began as a small online store run by sports enthusiasts has grown into a premier destination for authentic sports jerseys across multiple sports and leagues.</p>
            <p>Our journey started when our founders, avid sports fans themselves, recognized the need for high-quality, authentic jerseys that wouldn't break the bank. They embarked on a mission to create a platform where fans and athletes alike could find premium jerseys representing their favorite teams and players.</p>
            <p>Today, Jersey Pro is proud to offer one of the most comprehensive collections of authentic sports jerseys in Nepal, serving thousands of satisfied customers nationwide. We've built our reputation on quality, authenticity, and exceptional customer service.</p>
            
        

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
<?php 
include 'includes/footer.php'; // Include the footer file
?>
    <script src="assets/js/main.js"></script>
</body>
</html>