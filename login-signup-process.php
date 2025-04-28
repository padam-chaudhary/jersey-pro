<?php
// Add this at the top of your login-signup-process.php file
// Display all errors for debugging - remove in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Make sure these files exist and paths are correct
include_once 'includes/dbConnection.php';
include_once 'includes/functions.php';

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email format
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number (simple check for numbers only)
function validate_phone($phone) {
    return preg_match('/^(98|97|96)[0-9]{8}$/', $phone);
}

// Validate name (only letters allowed)
function validate_name($name) {
    return preg_match('/^[a-zA-Z\s]+$/', $name);
}

// Validate password strength (at least one number and one special character)
function validate_password_strength($password) {
    // Check for at least one number
    $has_number = preg_match('/[0-9]/', $password);
    
    // Check for at least one special character
    $has_special = preg_match('/[^a-zA-Z0-9]/', $password);
    
    return $has_number && $has_special;
}

// LOGIN PROCESS
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Get form data
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password']; // Do not sanitize password before verification
    
    // Store email for form persistence in case of errors
    $_SESSION['login_data']['email'] = $email;
    
    // Validate inputs
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If no validation errors, proceed with login
    if (empty($errors)) {
        try {
            // Prepare SQL statement to prevent SQL injection using PDO
            // Fixed: Added all needed columns to the SELECT statement
            $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, create session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    // Clear login data from session
                    unset($_SESSION['login_data']);
                    
                    // Redirect based on role
                    if ($user['role'] == 'admin') {
                        // Redirect admin to admin dashboard
                        header("Location: admin-dashboard.php");
                        exit();
                    } else {
                        // Redirect regular users to user dashboard or homepage
                        header("Location:jerseys.php");
                        exit();
                    }
                } else {
                    $errors[] = "Invalid email or password";
                }
            } else {
                $errors[] = "Invalid email or password";
            }
        } catch (PDOException $e) {
            $errors[] = "Login failed. Please try again later. Error: " . $e->getMessage();
            // Log the error (in a production environment)
            error_log("Login error: " . $e->getMessage());
        }
    }
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        header("Location: login.php");
        exit();
    }
}

// SIGNUP PROCESS
elseif (isset($_POST['action']) && $_POST['action'] == 'signup') {
    // Get form data
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password']; // Do not sanitize password before hashing
    $confirm_password = $_POST['confirm_password'];
    
    // Store form data for persistence in case of errors
    $_SESSION['signup_data'] = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone
    ];
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Full name is required";
    } elseif (!validate_name($name)) {
        $errors[] = "Name can only contain letters";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!validate_phone($phone)) {
        $errors[] = "Invalid phone number format (must start with 96, 97, or 98 and be 10 digits)";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    } elseif (!validate_password_strength($password)) {
        $errors[] = "Password must contain at least one number and one special character";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if email already exists in the database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email address is already registered";
            }
        } catch (PDOException $e) {
            $errors[] = "Registration check failed. Error: " . $e->getMessage();
            error_log("Registration error checking email: " . $e->getMessage());
        }
    }
    
    // If no validation errors, proceed with registration
    if (empty($errors)) {
        try {
            // Hash the password and confirm_password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $hashed_confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT);
            
            // Set default role as 'user'
            $role = 'user';
            
            // Insert new user into the database using PDO
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, confirm_password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            
            if ($stmt->execute([$name, $email, $phone, $hashed_password, $hashed_confirm_password, $role])) {
                // Get the new user ID
                $user_id = $pdo->lastInsertId();
                
                // Create session for the new user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $role;
                
                // Clear signup data from session
                unset($_SESSION['signup_data']);
                
                // Set success message
                $_SESSION['signup_success'] = "Registration successful! Welcome " . $name . "!";
                
                // Redirect to user dashboard
                header("Location: user-dashboard.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        } catch (PDOException $e) {
            $errors[] = "Registration failed. Error: " . $e->getMessage();
            error_log("Registration error: " . $e->getMessage());
        }
    }
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['signup_errors'] = $errors;
        header("Location: signup.php");
        exit();
    }
}

// If no action specified or direct access, redirect to login page
else {
    header("Location: login.php");
    exit();
}
?>