<?php
/**
 * Supabase Database Configuration
 */

class Database {
    private $supabase_url;
    private $supabase_key;
    private $supabase_service_key;
    
    public function __construct() {
        // Load environment variables
        $this->loadEnv();
        
        $this->supabase_url = $_ENV['VITE_SUPABASE_URL'] ?? '';
        $this->supabase_key = $_ENV['VITE_SUPABASE_ANON_KEY'] ?? '';
        $this->supabase_service_key = $_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? '';
    }
    
    private function loadEnv() {
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
    }
    
    public function makeRequest($endpoint, $method = 'GET', $data = null, $useServiceKey = false) {
        $url = rtrim($this->supabase_url, '/') . '/rest/v1/' . ltrim($endpoint, '/');
        
        $headers = [
            'Content-Type: application/json',
            'Prefer: return=representation',
            'apikey: ' . ($useServiceKey ? $this->supabase_service_key : $this->supabase_key),
            'Authorization: Bearer ' . ($useServiceKey ? $this->supabase_service_key : $this->supabase_key)
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        if ($data && in_array($method, ['POST', 'PATCH', 'PUT'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 400) {
            throw new Exception("API Error: HTTP $httpCode - $response");
        }
        
        return json_decode($response, true);
    }
    
    // Products CRUD operations
    public function getProducts($featured_only = false) {
        $endpoint = 'products?select=*&order=display_order.asc';
        if ($featured_only) {
            $endpoint .= '&is_featured=eq.true';
        }
        return $this->makeRequest($endpoint);
    }
    
    public function getProduct($id) {
        return $this->makeRequest("products?id=eq.$id&select=*");
    }
    
    public function createProduct($data) {
        $data['updated_at'] = date('c');
        return $this->makeRequest('products', 'POST', $data, true);
    }
    
    public function updateProduct($id, $data) {
        $data['updated_at'] = date('c');
        return $this->makeRequest("products?id=eq.$id", 'PATCH', $data, true);
    }
    
    public function deleteProduct($id) {
        return $this->makeRequest("products?id=eq.$id", 'DELETE', null, true);
    }
}