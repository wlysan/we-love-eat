<?php
/**
 * Application deployment configuration
 * This file is included by config.php and overrides default settings
 */

// The below settings should be modified for each deployment environment
// For development, keep the default values

// Base URL (with trailing slash)
define('APP_URL', '/');  // Change to your domain in production, e.g., 'https://nutrimenu.example.com/'

// Security settings
define('ENABLE_DEBUG', true);  // Set to false in production
define('SECURE_COOKIES', false);  // Set to true in production if using HTTPS
define('SESSION_LIFETIME', 86400);  // 24 hours in seconds

// File upload settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);  // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Email settings (for future use)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM', 'no-reply@example.com');
define('SMTP_FROM_NAME', 'NutriMenu');

// Error handling
if (ENABLE_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
}