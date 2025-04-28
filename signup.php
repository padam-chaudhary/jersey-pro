<?php require_once 'includes/header.php'; ?>
<style>
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
        
        .form-container {
            padding: 30px;
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
        
        .form-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
        
        .form-footer a {
            color: #4a90e2;
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        /* Alert messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .alert-dismissible {
            position: relative;
        }
        
        .alert-dismissible .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 15px;
            color: inherit;
            cursor: pointer;
            background: transparent;
            border: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
    </style>
</head>
<body>


    <div class="page-container">
        <div class="auth-container">
            <div class="form-container">
                <h2 class="page-title">Create an Account</h2>
                
                <?php if (isset($_SESSION['signup_success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <?php echo $_SESSION['signup_success']; ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php unset($_SESSION['signup_success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['signup_errors'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <?php 
                        if (is_array($_SESSION['signup_errors'])) {
                            foreach ($_SESSION['signup_errors'] as $error) {
                                echo $error . "<br>";
                            }
                        } else {
                            echo $_SESSION['signup_errors'];
                        }
                        ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php unset($_SESSION['signup_errors']); ?>
                <?php endif; ?>
                
                <form action="login-signup-process.php" method="post" id="signup-form">
                    <input type="hidden" name="action" value="signup">
                    
                    <div class="form-group">
                        <label for="signup-name">Full Name</label>
                        <input type="text" class="form-control" id="signup-name" name="name" value="<?php echo isset($_SESSION['signup_data']['name']) ? htmlspecialchars($_SESSION['signup_data']['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-email">Email Address</label>
                        <input type="email" class="form-control" id="signup-email" name="email" value="<?php echo isset($_SESSION['signup_data']['email']) ? htmlspecialchars($_SESSION['signup_data']['email']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-phone">Phone</label>
                        <input type="tel" class="form-control" id="signup-phone" name="phone" value="<?php echo isset($_SESSION['signup_data']['phone']) ? htmlspecialchars($_SESSION['signup_data']['phone']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="signup-password" name="password" required>
                            <i class="toggle-password fas fa-eye-slash" data-target="signup-password"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-confirm-password">Confirm Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="signup-confirm-password" name="confirm_password" required>
                            <i class="toggle-password fas fa-eye-slash" data-target="signup-confirm-password"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">Create Account</button>
                </form>
                
                <div class="form-footer">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Close alert messages
        const closeButtons = document.querySelectorAll('.alert .close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
    });
    </script>
</body>
</html>