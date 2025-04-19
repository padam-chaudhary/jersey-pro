<?php
session_start();
session_destroy();
header("Location: login-signup.php"); // Redirect to login page
exit;
?>      
