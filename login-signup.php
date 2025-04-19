<?php 
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
</head>

    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        /* Navigation Bar */
        .navbar {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            padding: 12px 24px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-sizing: border-box;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-bottom: 1px solid #eaeaea;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        
        .logo-container img {
            height: 50px;
            margin-right: 10px;
            border-radius: 6px;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .logo-text .jersey {
            color: #333333;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .logo-text .pro {
            color: #4a90e2;
            font-size: 16px;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
            margin-left: auto;
            align-items: center;
        }
        
        .nav-links li {
            margin-left: 20px;
        }
        
        .nav-links a {
            color: #555555;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }
        
        .nav-links a:hover {
            color: #4a90e2;
            background-color: #f5f5f5;
        }
        
        .icon-link {
            position: relative;
        }
        
        .icon-link i {
            font-size: 18px;
            margin-right: 5px;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #4a90e2;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        
        /* Page Content */
        .page-container {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }
        
        /* Auth Container */
        .auth-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .auth-tabs {
            display: flex;
            border-bottom: 1px solid #eaeaea;
        }
        
        .auth-tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .auth-tab.active {
            background-color: #ffffff;
            color: #4a90e2;
            border-bottom: 3px solid #4a90e2;
        }
        
        .auth-tab:not(.active) {
            background-color: #f8f9fa;
            color: #777;
        }
        
        .auth-tab:hover:not(.active) {
            background-color: #f1f1f1;
        }
        
        .form-container {
            padding: 30px;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            border-color: #4a90e2;
            outline: none;
        }
        
        .password-field {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
            color: #777;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .form-check-input {
            margin-right: 10px;
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .forgot-password a {
            color: #4a90e2;
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .btn-submit {
            width: 100%;
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #3a80d2;
        }
        
        /* .social-login {
            margin-top: 30px;
            text-align: center;
        }
        
        .social-login-title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .social-login-title:before, .social-login-title:after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .social-login-title span {
            padding: 0 10px;
            color: #777;
            font-size: 14px;
        }
        
        .social-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            color: #555;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            background-color: #f1f1f1;
            transform: translateY(-2px);
        }
        
        .social-btn.facebook:hover {
            color: #1877f2;
        }
        
        .social-btn.google:hover {
            color: #ea4335;
        }
        
        .social-btn.twitter:hover {
            color: #1da1f2;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        
        .form-footer a {
            color: #4a90e2;
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        } */
        
        /* Form validation styles */
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 5px;
            font-size: 12px;
            color: #dc3545;
        }
        
        /* Footer */
        footer {
            background-color: #f1f1f1;
            color: #333333;
            padding: 40px 20px 20px 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            gap: 30px;
        }
        
        .contact h4, .social h4 {
            color: #4a90e2;
            font-size: 1.2rem;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .contact p {
            margin: 8px 0;
            color: #555555;
        }
        
        .social a {
            display: block;
            color: #555555;
            text-decoration: none;
            margin: 8px 0;
            transition: color 0.3s;
        }
        
        .social a:hover {
            color: #4a90e2;
        }
        
        .footer-bottom {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 0.9rem;
            color: #777777;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 10px;
            }
            
            .logo-container {
                margin-bottom: 10px;
            }
            
            .nav-links {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .nav-links li {
                margin: 5px 10px;
            }
            
            .page-container {
                margin-top: 150px;
            }
            
            .auth-container {
                width: 100%;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .social-buttons {
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="page-container">
        <div class="auth-container">
            <div class="auth-tabs">
                <div class="auth-tab active" data-tab="login">Login</div>
                <div class="auth-tab" data-tab="signup">Sign Up</div>
            </div>
            
            <div class="form-container">
                <!-- Login Form -->
                <div class="form-section active" id="login-form">
                    <form action="login-process.php" method="post">
                        <div class="form-group">
                            <label for="login-email">Email Address</label>
                            <input type="email" class="form-control" id="login-email" name="email" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="login-password">Password</label>
                            <div class="password-field">
                                <input type="password" class="form-control" id="login-password" name="password" required>
                                <i class="toggle-password fas fa-eye-slash" data-target="login-password"></i>
                            </div>
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember-me" name="remember">
                            <label for="remember-me">Remember me</label>
                        </div>
                        
                        <div class="forgot-password">
                            <a href="forgot-password.php">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn-submit">Login</button>
                    </form>
                    
                    <!-- <div class="social-login">
                        <div class="social-login-title">
                            <span>Or login with</span>
                        </div>
                        
                        <div class="social-buttons">
                            <div class="social-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <div class="social-btn google">
                                <i class="fab fa-google"></i>
                            </div>
                            <div class="social-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="form-footer">
                        Don't have an account? <a href="#" class="switch-form" data-target="signup">Sign up now</a>
                    </div>
                </div>
                
                <!-- Sign Up Form -->
                <div class="form-section" id="signup-form">
                    <form action="signup-process.php" method="post">
                        <div class="form-group">
                            <label for="signup-name">Full Name</label>
                            <input type="text" class="form-control" id="signup-name" name="name" required>
                            <div class="invalid-feedback">Please enter your full name.</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="signup-email">Email Address</label>
                            <input type="email" class="form-control" id="signup-email" name="email" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="signup-password">Password</label>
                            <div class="password-field">
                                <input type="password" class="form-control" id="signup-password" name="password" required>
                                <i class="toggle-password fas fa-eye-slash" data-target="signup-password"></i>
                            </div>
                            <div class="invalid-feedback">Password must be at least 8 characters.</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="signup-confirm-password">Confirm Password</label>
                            <div class="password-field">
                                <input type="password" class="form-control" id="signup-confirm-password" name="confirm_password" required>
                                <i class="toggle-password fas fa-eye-slash" data-target="signup-confirm-password"></i>
                            </div>
                            <div class="invalid-feedback">Passwords do not match.</div>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label for="terms">I agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy Policy</a></label>
                        </div>
                        
                        <button type="submit" class="btn-submit">Create Account</button>
                    </form>
                    
                    <!-- <div class="social-login">
                        <div class="social-login-title">
                            <span>Or sign up with</span>
                        </div>
                        
                        <div class="social-buttons">
                            <div class="social-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <div class="social-btn google">
                                <i class="fab fa-google"></i>
                            </div>
                            <div class="social-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="form-footer">
                        Already have an account? <a href="#" class="switch-form" data-target="login">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabs = document.querySelectorAll('.auth-tab');
            const forms = document.querySelectorAll('.form-section');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show corresponding form
                    forms.forEach(form => {
                        form.classList.remove('active');
                        if (form.id === targetTab + '-form') {
                            form.classList.add('active');
                        }
                    });
                });
            });
            
            // Form switching links
            const switchLinks = document.querySelectorAll('.switch-form');
            switchLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetForm = this.getAttribute('data-target');
                    
                    // Click the appropriate tab
                    document.querySelector(`.auth-tab[data-tab="${targetForm}"]`).click();
                });
            });
            
            // Toggle password visibility
            const togglePasswordBtns = document.querySelectorAll('.toggle-password');
            togglePasswordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    
                    // Toggle password visibility
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });
            });
            
            // Basic form validation
            const loginForm = document.querySelector('#login-form form');
            const signupForm = document.querySelector('#signup-form form');
            
            loginForm.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Email validation
                const emailInput = document.getElementById('login-email');
                if (!validateEmail(emailInput.value)) {
                    showError(emailInput, 'Please enter a valid email address.');
                    isValid = false;
                } else {
                    hideError(emailInput);
                }
                
                // Password validation
                const passwordInput = document.getElementById('login-password');
                if (passwordInput.value.trim() === '') {
                    showError(passwordInput, 'Please enter your password.');
                    isValid = false;
                } else {
                    hideError(passwordInput);
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            signupForm.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Name validation
                const nameInput = document.getElementById('signup-name');
                if (nameInput.value.trim() === '') {
                    showError(nameInput, 'Please enter your full name.');
                    isValid = false;
                } else {
                    hideError(nameInput);
                }
                
                // Email validation
                const emailInput = document.getElementById('signup-email');
                if (!validateEmail(emailInput.value)) {
                    showError(emailInput, 'Please enter a valid email address.');
                    isValid = false;
                } else {
                    hideError(emailInput);
                }
                
                // Password validation
                const passwordInput = document.getElementById('signup-password');
                if (passwordInput.value.length < 8) {
                    showError(passwordInput, 'Password must be at least 8 characters.');
                    isValid = false;
                } else {
                    hideError(passwordInput);
                }
                
                // Confirm password validation
                const confirmPasswordInput = document.getElementById('signup-confirm-password');
                if (confirmPasswordInput.value !== passwordInput.value) {
                    showError(confirmPasswordInput, 'Passwords do not match.');
                    isValid = false;
                } else {
                    hideError(confirmPasswordInput);
                }
                
                // Terms checkbox validation
                const termsCheckbox = document.getElementById('terms');
                if (!termsCheckbox.checked) {
                    isValid = false;
                    // We don't show an error message for checkbox, just prevent form submission
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            // Helper functions
            function validateEmail(email) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }
            
            function showError(input, message) {
                input.classList.add('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'block';
                    feedback.textContent = message;
                }
            }
            
            function hideError(input) {
                input.classList.remove('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>