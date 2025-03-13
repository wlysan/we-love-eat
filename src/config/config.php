<?php
/**
 * Application configuration settings
 */
define('APP_NAME', 'NutriMenu');
define('APP_VERSION', '1.0.0');

// Load environment-specific configuration if it exists
$applicationConfig = __DIR__ . '/application.php';
if (file_exists($applicationConfig)) {
    require_once $applicationConfig;
}

// Default values (if not defined in application.php)
if (!defined('APP_URL')) {
    define('APP_URL', '/');
}

// Session settings
define('SESSION_NAME', 'nutrimenu_session');
if (!defined('SESSION_LIFETIME')) {
    define('SESSION_LIFETIME', 86400); // 24 hours in seconds
}

// Time and locale settings
define('DEFAULT_TIMEZONE', 'America/Sao_Paulo');
date_default_timezone_set(DEFAULT_TIMEZONE);
setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR', 'pt-BR');

// Security settings
define('HASH_COST', 12); // for password hashing
if (!defined('SECURE_COOKIES')) {
    define('SECURE_COOKIES', false);
}

// Upload settings
if (!defined('MAX_UPLOAD_SIZE')) {
    define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
}
define('UPLOAD_DIR', __DIR__ . '/../../public/uploads');

// Diet settings
define('MEALS_PER_DAY', 5); // default number of meals per day