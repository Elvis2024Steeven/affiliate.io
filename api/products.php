<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../admin/config/database.php';

try {
    $db = new Database();
    
    // Get only featured products for the frontend
    $products = $db->getProducts(true);
    
    // Format products for frontend consumption
    $formattedProducts = array_map(function($product) {
        return [
            'id' => $product['id'],
            'title' => $product['title'],
            'description' => $product['description'],
            'image_url' => $product['image_url'],
            'amazon_link' => $product['amazon_link'],
            'price' => $product['price'],
            'display_order' => $product['display_order']
        ];
    }, $products);
    
    // Sort by display order
    usort($formattedProducts, function($a, $b) {
        return $a['display_order'] <=> $b['display_order'];
    });
    
    echo json_encode([
        'success' => true,
        'products' => $formattedProducts
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>