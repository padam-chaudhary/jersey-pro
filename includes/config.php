<?php
// Site configuration file
// Contains important constants and settings for the website

// Prevent direct access
if (basename($_SERVER['PHP_SELF']) == 'config.php') {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access to this file is forbidden.');
}

// Define a constant to indicate this file has been included
define('INCLUDED_FROM_CONFIG', true);

// Site information
define('SITE_NAME', 'JerseyPro');
define('SITE_URL', 'https://yoursite.com'); // Change to your domain
define('ADMIN_EMAIL', 'admin@jerseypro.com');

// Database configuration
$dbHost = 'localhost:3306';
$dbName = 'jersey_pro';
$dbUser = 'root';
$dbPass = '11111111';

// Environment settings
define('DEVELOPMENT_MODE', true); // Set to false in production

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (DEVELOPMENT_MODE === false) {
    ini_set('session.cookie_secure', 1);
}
session_start();

// Error reporting settings
if (DEVELOPMENT_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Currency settings
define('CURRENCY_SYMBOL', 'Rs');
define('CURRENCY_CODE', 'NPR'); // Nepalese Rupee

// Pagination settings
define('ITEMS_PER_PAGE', 12);

// File upload settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Path settings
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/uploads/');
define('JERSEYS_IMG_PATH', 'assets/images/jerseys/');
?>