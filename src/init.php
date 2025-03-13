<?php
/**
 * Application initialization
 */

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Load utility classes
require_once __DIR__ . '/utils/Auth.php';
require_once __DIR__ . '/utils/Formatter.php';

// Start session
session_name(SESSION_NAME);
session_start();

// Initialize database if needed
$db = Database::getInstance();
$dbPath = __DIR__ . '/../database/nutrition.db';

if (!file_exists($dbPath)) {
    $db->initSchema();
    
    // Create default admin user if database is new
    require_once __DIR__ . '/models/User.php';
    $userModel = new User();
    
    $adminExists = $userModel->getByEmail('admin@nutrimenu.com.br');
    
    if (!$adminExists) {
        $userModel->create(
            'Administrador', 
            'admin@nutrimenu.com.br', 
            'admin123', 
            'admin'
        );
    }
}

/**
 * Helper function to redirect
 */
function redirect($path) {
    header("Location: " . APP_URL . ltrim($path, '/'));
    exit;
}

/**
 * Helper function to render view
 */
function view($template, $data = []) {
    // Extract data to make variables available in template
    extract($data);
    
    // Get current user
    $auth = Auth::getInstance();
    $currentUser = $auth->getUser();
    
    // Buffer output
    ob_start();
    include __DIR__ . "/views/$template.php";
    $content = ob_get_clean();
    
    // Check if layout is needed
    if (isset($data['layout']) && $data['layout'] === false) {
        echo $content;
    } else {
        // Wrap content in layout
        include __DIR__ . "/views/layouts/main.php";
    }
}

/**
 * Helper function to check if string starts with a substring
 */
function startsWith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

/**
 * Function to get current route
 */
function getCurrentRoute() {
    $uri = $_SERVER['REQUEST_URI'];
    $basePath = parse_url(APP_URL, PHP_URL_PATH) ?: '';
    
    // Remove base path from URI
    if ($basePath && startsWith($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath));
    }
    
    // Remove query string
    $uri = parse_url($uri, PHP_URL_PATH);
    
    return '/' . trim($uri, '/');
}