<?php
// admin/settings.php - Site configuration

// Start session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once '../includes/dbConnection.php';

// Initialize variables
$message = '';
$error = '';

// Get current settings
try {
    $stmt = $pdo->query("SELECT * FROM settings");
    $settings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    $error = "Error loading settings: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Site settings
        if (isset($_POST['update_site_settings'])) {
            $site_settings = [
                'site_name' => $_POST['site_name'],
                'site_description' => $_POST['site_description'],
                'contact_email' => $_POST['contact_email'],
                'contact_phone' => $_POST['contact_phone'],
                'address' => $_POST['address'],
                'currency' => $_POST['currency'],
                'timezone' => $_POST['timezone']
            ];
            
            foreach ($site_settings as $key => $value) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
            
            $message = "Site settings updated successfully.";
        }
        
        // Payment settings
        if (isset($_POST['update_payment_settings'])) {
            $payment_settings = [
                'payment_methods' => isset($_POST['payment_methods']) ? implode(',', $_POST['payment_methods']) : '',
                'paypal_email' => $_POST['paypal_email'],
                'stripe_public_key' => $_POST['stripe_public_key'],
                'stripe_secret_key' => $_POST['stripe_secret_key'],
                'bank_details' => $_POST['bank_details']
            ];
            
            foreach ($payment_settings as $key => $value) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
            
            $message = "Payment settings updated successfully.";
        }
        
        // Shipping settings
        if (isset($_POST['update_shipping_settings'])) {
            $shipping_settings = [
                'shipping_methods' => isset($_POST['shipping_methods']) ? implode(',', $_POST['shipping_methods']) : '',
                'free_shipping_threshold' => $_POST['free_shipping_threshold'],
                'standard_shipping_rate' => $_POST['standard_shipping_rate'],
                'express_shipping_rate' => $_POST['express_shipping_rate']
            ];
            
            foreach ($shipping_settings as $key => $value) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
            
            $message = "Shipping settings updated successfully.";
        }
        
        // Email settings
        if (isset($_POST['update_email_settings'])) {
            $email_settings = [
                'smtp_host' => $_POST['smtp_host'],
                'smtp_port' => $_POST['smtp_port'],
                'smtp_username' => $_POST['smtp_username'],
                'smtp_password' => $_POST['smtp_password'],
                'from_email' => $_POST['from_email'],
                'from_name' => $_POST['from_name']
            ];
            
            foreach ($email_settings as $key => $value) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
            
            $message = "Email settings updated successfully.";
        }
        
        // Tax settings
        if (isset($_POST['update_tax_settings'])) {
            $tax_settings = [
                'tax_enabled' => isset($_POST['tax_enabled']) ? '1' : '0',
                'tax_rate' => $_POST['tax_rate'],
                'tax_included' => isset($_POST['tax_included']) ? '1' : '0'
            ];
            
            foreach ($tax_settings as $key => $value) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
            
            $message = "Tax settings updated successfully.";
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Reload settings after update
        $stmt = $pdo->query("SELECT * FROM settings");
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (PDOException $e) {
        // Rollback transaction
        $pdo->rollBack();
        $error = "Error updating settings: " . $e->getMessage();
    }
}

// Get admin info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'admin'");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Site Settings</h1>
                <div class="admin-user">
                    <span>Welcome, <?= htmlspecialchars($admin['username']) ?></span>
                    <a href="logout.php" class="btn btn-sm">Logout</a>
                </div>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <div class="settings-tabs">
                <ul class="tab-nav">
                    <li><a href="#site-settings" class="active">General</a></li>
                    <li><a href="#payment-settings">Payment</a></li>
                    <li><a href="#shipping-settings">Shipping</a></li>
                    <li><a href="#email-settings">Email</a></li>
                    <li><a href="#tax-settings">Tax</a></li>
                </ul>
                
                <div class="tab-content">
                    <!-- General Site Settings -->
                    <div id="site-settings" class="tab-pane active">
                        <h2>General Site Settings</h2>
                        <form method="POST" class="settings-form">
                            <div class="form-group">
                                <label for="site_name">Site Name:</label>
                                <input type="text" name="site_name" id="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_description">Site Description:</label>
                                <textarea name="site_description" id="site_description" rows="4"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_email">Contact Email:</label>
                                <input type="email" name="contact_email" id="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_phone">Contact Phone:</label>
                                <input type="text" name="contact_phone" id="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Business Address:</label>
                                <textarea name="address" id="address" rows="3"><?= htmlspecialchars($settings['address'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="currency">Currency:</label>
                                <select name="currency" id="currency">
                                    <option value="USD" <?= ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                                    <option value="EUR" <?= ($settings['currency'] ?? '') === 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                                    <option value="GBP" <?= ($settings['currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP (£)</option>
                                    <option value="CAD" <?= ($settings['currency'] ?? '') === 'CAD' ? 'selected' : '' ?>>CAD ($)</option>
                                    <option value="AUD" <?= ($settings['currency'] ?? '') === 'AUD' ? 'selected' : '' ?>>AUD ($)</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="timezone">Timezone:</label>
                                <select name="timezone" id="timezone">
                                    <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                    <option value="America/New_York" <?= ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>Eastern Time (ET)</option>
                                    <option value="America/Chicago" <?= ($settings['timezone'] ?? '') === 'America/Chicago' ? 'selected' : '' ?>>Central Time (CT)</option>
                                    <option value="America/Denver" <?= ($settings['timezone'] ?? '') === 'America/Denver' ? 'selected' : '' ?>>Mountain Time (MT)</option>
                                    <option value="America/Los_Angeles" <?= ($settings['timezone'] ?? '') === 'America/Los_Angeles' ? 'selected' : '' ?>>Pacific Time (PT)</option>
                                    <option value="Europe/London" <?= ($settings['timezone'] ?? '') === 'Europe/London' ? 'selected' : '' ?>>London (GMT)</option>
                                    <option value="Europe/Paris" <?= ($settings['timezone'] ?? '') === 'Europe/Paris' ? 'selected' : '' ?>>Paris (CET)</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_site_settings" class="btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Payment Settings -->
                    <div id="payment-settings" class="tab-pane">
                        <h2>Payment Settings</h2>
                        <form method="POST" class="settings-form">
                            <div class="form-group">
                                <label>Payment Methods:</label>
                                <div class="checkbox-list">
                                    <?php 
                                    $payment_methods = isset($settings['payment_methods']) ? explode(',', $settings['payment_methods']) : [];
                                    ?>
                                    <label>
                                        <input type="checkbox" name="payment_methods[]" value="credit_card" <?= in_array('credit_card', $payment_methods) ? 'checked' : '' ?>>
                                        Credit Card
                                    </label>
                                    <label>
                                        <input type="checkbox" name="payment_methods[]" value="paypal" <?= in_array('paypal', $payment_methods) ? 'checked' : '' ?>>
                                        PayPal
                                    </label>
                                    <label>
                                        <input type="checkbox" name="payment_methods[]" value="bank_transfer" <?= in_array('bank_transfer', $payment_methods) ? 'checked' : '' ?>>
                                        Bank Transfer
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="paypal_email">PayPal Email:</label>
                                <input type="email" name="paypal_email" id="paypal_email" value="<?= htmlspecialchars($settings['paypal_email'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="stripe_public_key">Stripe Public Key:</label>
                                <input type="text" name="stripe_public_key" id="stripe_public_key" value="<?= htmlspecialchars($settings['stripe_public_key'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="stripe_secret_key">Stripe Secret Key:</label>
                                <input type="password" name="stripe_secret_key" id="stripe_secret_key" value="<?= htmlspecialchars($settings['stripe_secret_key'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="bank_details">Bank Transfer Details:</label>
                                <textarea name="bank_details" id="bank_details" rows="4"><?= htmlspecialchars($settings['bank_details'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_payment_settings" class="btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Shipping Settings -->
                    <div id="shipping-settings" class="tab-pane">
                        <h2>Shipping Settings</h2>
                        <form method="POST" class="settings-form">
                            <div class="form-group">
                                <label>Shipping Methods:</label>
                                <div class="checkbox-list">
                                    <?php 
                                    $shipping_methods = isset($settings['shipping_methods']) ? explode(',', $settings['shipping_methods']) : [];
                                    ?>
                                    <label>
                                        <input type="checkbox" name="shipping_methods[]" value="standard" <?= in_array('standard', $shipping_methods) ? 'checked' : '' ?>>
                                        Standard Shipping
                                    </label>
                                    <label>
                                        <input type="checkbox" name="shipping_methods[]" value="express" <?= in_array('express', $shipping_methods) ? 'checked' : '' ?>>
                                        Express Shipping
                                    </label>
                                    <label>
                                        <input type="checkbox" name="shipping_methods[]" value="local_pickup" <?= in_array('local_pickup', $shipping_methods) ? 'checked' : '' ?>>
                                        Local Pickup
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="free_shipping_threshold">Free Shipping Threshold:</label>
                                <div class="input-group">
                                    <span class="input-group-prefix">$</span>
                                    <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" step="0.01" min="0" value="<?= htmlspecialchars($settings['free_shipping_threshold'] ?? '0') ?>">
                                </div>
                                <small>Set to 0 to disable free shipping</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="standard_shipping_rate">Standard Shipping Rate:</label>
                                <div class="input-group">
                                    <span class="input-group-prefix">$</span>
                                    <input type="number" name="standard_shipping_rate" id="standard_shipping_rate" step="0.01" min="0" value="<?= htmlspecialchars($settings['standard_shipping_rate'] ?? '0') ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="express_shipping_rate">Express Shipping Rate:</label>
                                <div class="input-group">
                                    <span class="input-group-prefix">$</span>
                                    <input type="number" name="express_shipping_rate" id="express_shipping_rate" step="0.01" min="0" value="<?= htmlspecialchars($settings['express_shipping_rate'] ?? '0') ?>">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_shipping_settings" class="btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Email Settings -->
                    <div id="email-settings" class="tab-pane">
                        <h2>Email Settings</h2>
                        <form method="POST" class="settings-form">
                            <div class="form-group">
                                <label for="smtp_host">SMTP Host:</label>
                                <input type="text" name="smtp_host" id="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_port">SMTP Port:</label>
                                <input type="number" name="smtp_port" id="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '587') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_username">SMTP Username:</label>
                                <input type="text" name="smtp_username" id="smtp_username" value="<?= htmlspecialchars($settings['smtp_username'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_password">SMTP Password:</label>
                                <input type="password" name="smtp_password" id="smtp_password" value="<?= htmlspecialchars($settings['smtp_password'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="from_email">From Email:</label>
                                <input type="email" name="from_email" id="from_email" value="<?= htmlspecialchars($settings['from_email'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="from_name">From Name:</label>
                                <input type="text" name="from_name" id="from_name" value="<?= htmlspecialchars($settings['from_name'] ?? '') ?>">
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_email_settings" class="btn">Save Changes</button>
                                <a href="#" class="btn btn-secondary" id="test-email">Send Test Email</a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tax Settings -->
                    <div id="tax-settings" class="tab-pane">
                        <h2>Tax Settings</h2>
                        <form method="POST" class="settings-form">
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="tax_enabled" <?= ($settings['tax_enabled'] ?? '') === '1' ? 'checked' : '' ?>>
                                    Enable Tax Calculations
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="tax_rate">Default Tax Rate (%):</label>
                                <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="<?= htmlspecialchars($settings['tax_rate'] ?? '0') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="tax_included" <?= ($settings['tax_included'] ?? '') === '1' ? 'checked' : '' ?>>
                                    Prices include tax
                                </label>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="update_tax_settings" class="btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Simple tab navigation
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-nav a');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabLinks.forEach(l => l.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    const target = this.getAttribute('href');
                    document.querySelector(target).classList.add('active');
                });
            });
            
            // Test email functionality
            const testEmailBtn = document.getElementById('test-email');
            if (testEmailBtn) {
                testEmailBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Test email functionality would be implemented here.');
                });
            }
        });
    </script>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>