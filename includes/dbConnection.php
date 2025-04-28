<?php
// This should be in includes/dbConnection.php
// Make sure this file exists and is correct

// Database configuration
$host = 'localhost:3306';     // Your database host
$dbname = 'jersey_pro';  // Your database name
$username = 'root';      // Your database username
$password = '11111111';          // Your database password

// Create PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Use prepared statements by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Optional: Disable emulated prepared statements
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch (PDOException $e) {
    // Log the error
    error_log("Database connection failed: " . $e->getMessage());
    
    // Display an error message (remove in production)
    die("Database connection failed. Please try again later.");
}