<?php
// Common functions used across the site

// Sanitize user input
function sanitize($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitize($value);
        }
        return $input;
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Generate a random string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Format price
function formatPrice($price) {
    return CURRENCY_SYMBOL . number_format($price, 2);
}

// Get current page URL
function getCurrentUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// Redirect to URL
function redirect($url) {
    header("Location: $url");
    exit();
}

// Get jersey discount percentage
function getDiscountPercentage($originalPrice, $salePrice) {
    if (!$salePrice || $salePrice >= $originalPrice) {
        return 0;
    }
    return round(100 - (($salePrice / $originalPrice) * 100));
}

// Check if product is in stock
function isInStock($stock) {
    return $stock > 0;
}

// Get user cart count
function getCartCount() {
    if (!isLoggedIn()) {
        return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    }
    
    global $db;
    try {
        $stmt = $db->prepare("SELECT COUNT(*) FROM cart_items WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error getting cart count: " . $e->getMessage());
        return 0;
    }
}

// Log site activity
function logActivity($userId, $action, $details = '') {
    global $db;
    try {
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $details, $_SERVER['REMOTE_ADDR']]);
    } catch (PDOException $e) {
        error_log("Error logging activity: " . $e->getMessage());
    }
}

// Generate pagination links
function generatePaginationLinks($currentPage, $totalPages, $urlParams) {
    $links = '';
    
    // Previous page link
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $links .= "<a href=\"?page=$prevPage$urlParams\" class=\"page-nav prev\">&laquo; Previous</a>";
    }
    
    // Page number links
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $links .= "<span class=\"page-number current\">$i</span>";
        } else {
            $links .= "<a href=\"?page=$i$urlParams\" class=\"page-number\">$i</a>";
        }
    }
    
    // Next page link
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $links .= "<a href=\"?page=$nextPage$urlParams\" class=\"page-nav next\">Next &raquo;</a>";
    }
    
    return $links;
}

// Get related jerseys
function getRelatedJerseys($db, $jerseyId, $teamId, $limit = 4) {
    try {
        // First try to get jerseys from the same team
        $stmt = $db->prepare("
            SELECT j.id, j.name, j.price, j.sale_price, j.image_path, t.team_name 
            FROM jerseys j
            LEFT JOIN teams t ON j.team_id = t.id
            WHERE j.team_id = ? AND j.id != ? AND j.stock > 0
            ORDER BY j.featured DESC, RAND()
            LIMIT ?
        ");
        $stmt->execute([$teamId, $jerseyId, $limit]);
        $jerseys = $stmt->fetchAll();
        
        // If not enough jerseys from the same team, get popular jerseys
        if (count($jerseys) < $limit) {
            $remainingCount = $limit - count($jerseys);
            $existingIds = array_column($jerseys, 'id');
            $existingIds[] = $jerseyId;
            
            $placeholders = implode(',', array_fill(0, count($existingIds), '?'));
            
            $stmt = $db->prepare("
                SELECT j.id, j.name, j.price, j.sale_price, j.image_path, t.team_name 
                FROM jerseys j
                LEFT JOIN teams t ON j.team_id = t.id
                WHERE j.id NOT IN ($placeholders) AND j.stock > 0
                ORDER BY j.sales_count DESC, j.featured DESC
                LIMIT ?
            ");
            
            $params = $existingIds;
            $params[] = $remainingCount;
            $stmt->execute($params);
            
            $additionalJerseys = $stmt->fetchAll();
            $jerseys = array_merge($jerseys, $additionalJerseys);
        }
        
        return $jerseys;
    } catch (PDOException $e) {
        error_log("Error getting related jerseys: " . $e->getMessage());
        return [];
    }
}