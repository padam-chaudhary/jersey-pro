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
                <h2 class="page-title">Login to Your Account</h2>
                <?php if (isset($_SESSION['login_errors'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <?php 
                        if (is_array($_SESSION['login_errors'])) {
                            foreach ($_SESSION['login_errors'] as $error) {
                                echo $error . "<br>";
                            }
                        } else {
                            echo $_SESSION['login_errors'];
                        }
                        ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php unset($_SESSION['login_errors']); ?>
                <?php endif; ?>
                
                <form action="login-signup-process.php" method="post" id="login-form">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group">
                        <label for="login-email">Email Address</label>
                        <input type="email" class="form-control" id="login-email" name="email" value="<?php echo isset($_SESSION['login_data']['email']) ? htmlspecialchars($_SESSION['login_data']['email']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" id="login-password" name="password" required>
                            <i class="toggle-password fas fa-eye-slash" data-target="login-password"></i>
                        </div>
                    </div>
                    
                    <div class="forgot-password">
                        <a href="forgot-password.php">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="btn-submit">Login</button>
                </form>
                
                <div class="form-footer">
                    Don't have an account? <a href="signup.php">Sign up now</a>
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