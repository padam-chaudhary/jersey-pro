<?php
session_start(); // Start the session
include_once 'includes/dbConnection.php'; // Include database connection
include_once 'includes/functions.php'; // Include functions
// If cart_count isn't defined yet, initialize it
if (!isset($cart_count)) {
    $cart_count = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cart_count += $item['quantity'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JERSEY PRO - Your Game, Your Gear</title>
    <link rel="icon" type="assets/image/png" href="assets/images/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/jerseys.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
           <a href="home.php"><img src="assets/images/logo.png" alt="Jersey Pro Logo"></a> 
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
            <li><a href="login.php" class="icon-link"><i class="fas fa-user"></i></a></li>
            <li><a href="cart.php" class="icon-link">
                <i class="fas fa-shopping-cart"></i> 
                <span id="navCartCount" class="cart-count"><?php echo $cart_count; ?></span>
            </a></li>
        </ul>
    </nav>
