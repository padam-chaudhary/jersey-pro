<?php
// Start session at the top
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page if not admin
    $_SESSION['login_error'] = "You must be logged in as an administrator to access the settings.";
    header("Location: login.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_general_settings'])) {
        // Process general settings update
        $site_name = htmlspecialchars($_POST['site_name']);
        $site_email = htmlspecialchars($_POST['site_email']);
        $contact_phone = htmlspecialchars($_POST['contact_phone']);
        $contact_address = htmlspecialchars($_POST['contact_address']);
        
        // In a real application, you would update these values in the database
        // Here we'll just show a success message
        $_SESSION['dashboard_success'] = "General settings updated successfully.";
        header("Location: admin-settings.php");
        exit();
    } 
    elseif (isset($_POST['update_email_settings'])) {
        // Process email settings update
        $smtp_host = htmlspecialchars($_POST['smtp_host']);
        $smtp_port = htmlspecialchars($_POST['smtp_port']);
        $smtp_username = htmlspecialchars($_POST['smtp_username']);
        $smtp_password = $_POST['smtp_password']; // In production, encrypt this
        
        // In a real application, you would update these values in the database
        $_SESSION['dashboard_success'] = "Email settings updated successfully.";
        header("Location: admin-settings.php");
        exit();
    } 
    elseif (isset($_POST['update_payment_settings'])) {
        // Process payment settings update
        $currency = htmlspecialchars($_POST['currency']);
        $payment_methods = isset($_POST['payment_methods']) ? $_POST['payment_methods'] : [];
        
        // In a real application, you would update these values in the database
        $_SESSION['dashboard_success'] = "Payment settings updated successfully.";
        header("Location: admin-settings.php");
        exit();
    }
    elseif (isset($_POST['update_admin_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Check if new passwords match
        if ($new_password !== $confirm_password) {
            $_SESSION['dashboard_error'] = "New passwords do not match.";
            header("Location: admin-settings.php");
            exit();
        }
        
        // In a real application, you would verify the current password and update it in the database
        // For demo purposes, we'll just show a success message
        $_SESSION['dashboard_success'] = "Admin password updated successfully.";
        header("Location: admin-settings.php");
        exit();
    }
}

// For demo purposes, we'll set default values
$settings = [
    'site_name' => 'JERSEY PRO',
    'site_email' => 'info@jerseypro.com',
    'contact_phone' => '+1 (555) 123-4567',
    'contact_address' => '123 Sports Avenue, New York, NY 10001',
    'smtp_host' => 'smtp.jerseypro.com',
    'smtp_port' => '587',
    'smtp_username' => 'notifications@jerseypro.com',
    'smtp_password' => '•••••••••••',
    'currency' => 'USD',
    'payment_methods' => ['credit_card', 'paypal']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - JERSEY PRO</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Page Content */
        .page-container {
            max-width: 1200px;
            margin: 120px auto 60px;
            padding: 0 20px;
        }
        
        /* Dashboard Container */
        .dashboard-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .dashboard-header {
            background-color: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            margin: 0;
            color: #333;
        }
        
        .dashboard-content {
            display: flex;
            min-height: 600px;
        }
        
        /* Sidebar Navigation */
        .dashboard-sidebar {
            width: 250px;
            background-color: #f8f9fa;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
        }
        
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #555;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .nav-link:hover {
            background-color: #e9ecef;
            color: #4a90e2;
        }
        
        .nav-link.active {
            background-color: #4a90e2;
            color: white;
            font-weight: 600;
        }
        
        .nav-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Area */
        .dashboard-main {
            flex: 1;
            padding: 30px;
        }
        
        /* Settings Sections */
        .settings-section {
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .settings-title {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #4a90e2;
            outline: none;
        }
        
        .checkbox-group {
            margin-top: 10px;
        }
        
        .checkbox-label {
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
        }
        
        .checkbox-label input {
            margin-right: 10px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a80d2;
        }
        
        /* Form Columns */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 250px;
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
        
        /* Nav Tabs for Settings */
        .settings-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 25px;
        }
        
        .settings-tab {
            padding: 12px 20px;
            margin-right: 5px;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            font-weight: 500;
        }
        
        .settings-tab.active {
            border-bottom-color: #4a90e2;
            color: #4a90e2;
        }
        
        .settings-content {
            display: none;
        }
        
        .settings-content.active {
            display: block;
        }
    </style>
</head>
<body>
<?php require_once 'includes/header.php'; ?>

    <div class="page-container">
        <?php if (isset($_SESSION['dashboard_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?php echo $_SESSION['dashboard_success']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['dashboard_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['dashboard_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?php echo $_SESSION['dashboard_error']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['dashboard_error']); ?>
        <?php endif; ?>
        
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2 class="page-title">Admin Settings</h2>
            </div>
            
            <div class="dashboard-content">
                <!-- Sidebar Navigation -->
                <div class="dashboard-sidebar">
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="admin-dashboard.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-orders.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-products.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-tshirt"></i></span>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-customers.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-users"></i></span>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-categories.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-tags"></i></span>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin-settings.php" class="nav-link active">
                                <span class="nav-icon"><i class="fas fa-cog"></i></span>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Main Content -->
                <div class="dashboard-main">
                    <div class="settings-section">
                        <div class="settings-tabs">
                            <div class="settings-tab active" data-tab="general">General Settings</div>
                            <div class="settings-tab" data-tab="email">Email Settings</div>
                            <div class="settings-tab" data-tab="payment">Payment Settings</div>
                            <div class="settings-tab" data-tab="security">Security</div>
                        </div>
                        
                        <!-- General Settings -->
                        <div class="settings-content active" id="general-settings">
                            <form action="admin-settings.php" method="POST">
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="site_name">Site Name</label>
                                            <input type="text" id="site_name" name="site_name" class="form-control" value="<?php echo $settings['site_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="site_email">Site Email</label>
                                            <input type="email" id="site_email" name="site_email" class="form-control" value="<?php echo $settings['site_email']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="contact_phone">Contact Phone</label>
                                            <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?php echo $settings['contact_phone']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="contact_address">Contact Address</label>
                                            <input type="text" id="contact_address" name="contact_address" class="form-control" value="<?php echo $settings['contact_address']; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="update_general_settings" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Email Settings -->
                        <div class="settings-content" id="email-settings">
                            <form action="admin-settings.php" method="POST">
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="smtp_host">SMTP Host</label>
                                            <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="<?php echo $settings['smtp_host']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="smtp_port">SMTP Port</label>
                                            <input type="text" id="smtp_port" name="smtp_port" class="form-control" value="<?php echo $settings['smtp_port']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="smtp_username">SMTP Username</label>
                                            <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="<?php echo $settings['smtp_username']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="smtp_password">SMTP Password</label>
                                            <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="<?php echo $settings['smtp_password']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="update_email_settings" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Payment Settings -->
                        <div class="settings-content" id="payment-settings">
                            <form action="admin-settings.php" method="POST">
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="currency">Currency</label>
                                            <select id="currency" name="currency" class="form-control">
                                                <option value="USD" <?php echo ($settings['currency'] === 'USD') ? 'selected' : ''; ?>>USD - US Dollar</option>
                                                <option value="EUR" <?php echo ($settings['currency'] === 'EUR') ? 'selected' : ''; ?>>EUR - Euro</option>
                                                <option value="GBP" <?php echo ($settings['currency'] === 'GBP') ? 'selected' : ''; ?>>GBP - British Pound</option>
                                                <option value="CAD" <?php echo ($settings['currency'] === 'CAD') ? 'selected' : ''; ?>>CAD - Canadian Dollar</option>
                                                <option value="AUD" <?php echo ($settings['currency'] === 'AUD') ? 'selected' : ''; ?>>AUD - Australian Dollar</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Payment Methods</label>
                                    <div class="checkbox-group">
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="payment_methods[]" value="credit_card" <?php echo in_array('credit_card', $settings['payment_methods']) ? 'checked' : ''; ?>> Credit Card
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="payment_methods[]" value="paypal" <?php echo in_array('paypal', $settings['payment_methods']) ? 'checked' : ''; ?>> PayPal
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="payment_methods[]" value="stripe" <?php echo in_array('stripe', $settings['payment_methods']) ? 'checked' : ''; ?>> Stripe
                                        </label>
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="payment_methods[]" value="bank_transfer" <?php echo in_array('bank_transfer', $settings['payment_methods']) ? 'checked' : ''; ?>> Bank Transfer
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="update_payment_settings" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Security Settings -->
                        <div class="settings-content" id="security-settings">
                            <form action="admin-settings.php" method="POST">
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm New Password</label>
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="update_admin_password" class="btn btn-primary">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabs = document.querySelectorAll('.settings-tab');
        const tabContents = document.querySelectorAll('.settings-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all tab contents
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Show the selected tab content
                const tabId = this.getAttribute('data-tab') + '-settings';
                document.getElementById(tabId).classList.add('active');
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