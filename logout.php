<?php
// Start session
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Set success message for the login page
session_start(); // Start a new session to store the message
$_SESSION['login_message'] = "You have been successfully logged out.";

// Redirect to login page
header("Location: login.php");
exit();
?>