<?php
// Path: jersey-pro/login_signup_process.php

require_once 'includes/config.php';
require_once 'includes/dbConnection.php';
require_once 'includes/functions.php';

// Initialize variables
$username = $email = '';
$signup_success = $signup_error = $login_error = '';
$validationErrors = [];

// Process signup form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    // Get form data
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    
    // Validate input
    if (empty($username)) {
        $validationErrors[] = "Username is required";
    }
    
    if (empty($email)) {
        $validationErrors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $validationErrors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $validationErrors[] = "Password must be at least 6 characters";
    }
    
    if ($password != $confirm_password) {
        $validationErrors[] = "Passwords do not match";
    }
    
    // Check if email already exists
    if (empty($validationErrors)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            
            if ($stmt->rowCount() > 0) {
                $signup_error = "Email already exists";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password
                ]);
                
                $signup_success = "Account created successfully! Please login.";
                
                // Clear form data
                $username = $email = '';
            }
        } catch(PDOException $e) {
            $signup_error = "Error: " . $e->getMessage();
        }
    }
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['username']) && isset($_POST['email'])) {
    // Get form data
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($email)) {
        $login_error = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_error = "Invalid email format";
    } elseif (empty($password)) {
        $login_error = "Password is required";
    } else {
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Password is correct, create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to user dashboard
                header("Location: user/index.php");
                exit;
            } else {
                $login_error = "Invalid email or password";
            }
        } catch(PDOException $e) {
            $login_error = "Error: " . $e->getMessage();
        }
    }
}
?>