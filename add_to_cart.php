<?php
session_start();
require_once 'includes/dbConnection.php';  // For database connection

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'cart_count' => 0
];

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get product ID and quantity from the POST data
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Validate product ID and quantity
    if ($product_id <= 0) {
        $response['message'] = 'Invalid product ID';
        echo json_encode($response);
        exit;
    }
    
    if ($quantity <= 0 || $quantity > 10) {
        $response['message'] = 'Quantity must be between 1 and 10';
        echo json_encode($response);
        exit;
    }
    
    // Check if product exists in database
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id");
        $stmt->execute([':product_id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            $response['message'] = 'Product not found';
            echo json_encode($response);
            exit;
        }
        
        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Check if product is already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Update quantity
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            
            // Make sure quantity doesn't exceed 10
            if ($_SESSION['cart'][$product_id]['quantity'] > 10) {
                $_SESSION['cart'][$product_id]['quantity'] = 10;
            }
        } else {
            // Add new product to cart
            $_SESSION['cart'][$product_id] = [
                'quantity' => $quantity
            ];
        }
        
        // Count total items in cart for response
        $cart_count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cart_count += $item['quantity'];
        }
        
        $response['success'] = true;
        $response['message'] = 'Product added to cart';
        $response['cart_count'] = $cart_count;
        
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>