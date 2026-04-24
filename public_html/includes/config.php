<?php
/**
 * Configuration File - FIXED VERSION
 * Load environment variables and define constants
 * FIXES: HTTPS detection, secure session cookies, consistent paths
 */

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Load .env file
loadEnv(__DIR__ . '/../.env');

// Database Configuration - UPDATED FOR HOSTINGER
define('DB_HOST', 'localhost');
define('DB_NAME', 'u774526707_carefulcat_db');
define('DB_USER', 'u774526707_carefulcat_db');
define('DB_PASS', 'Vum3317!!!!');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_CHARSET', 'utf8mb4');

// Stripe Configuration
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '');
define('STRIPE_PUBLIC_KEY', getenv('STRIPE_PUBLIC_KEY') ?: '');
define('STRIPE_WEBHOOK_SECRET', getenv('STRIPE_WEBHOOK_SECRET') ?: '');

// Detect HTTPS - FIXED
$isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
    (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
);

// Site Configuration - UPDATED FOR PRODUCTION
$protocol = $isHttps ? 'https' : 'http';
define('SITE_URL', $protocol . '://carefulcat.org');
define('SITE_NAME', getenv('SITE_NAME') ?: 'Careful Cat Rescue');
define('SITE_EMAIL', getenv('SITE_EMAIL') ?: 'info@carefulcatrescue.org');

// Path Configuration - FIXED FOR ROOT DEPLOYMENT (no /public/ subdirectory)
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH);
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// URL Configuration - FIXED FOR ROOT DEPLOYMENT
define('BASE_URL', rtrim(SITE_URL, '/'));
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

// Upload Configuration
define('MAX_UPLOAD_SIZE', getenv('MAX_UPLOAD_SIZE') ?: 10485760); // 10MB
define('ALLOWED_IMAGE_TYPES', explode(',', getenv('ALLOWED_IMAGE_TYPES') ?: 'jpg,jpeg,png,gif,webp'));
define('ALLOWED_VIDEO_TYPES', explode(',', getenv('ALLOWED_VIDEO_TYPES') ?: 'mp4,webm,mov'));

// Session Configuration
define('SESSION_LIFETIME', getenv('SESSION_LIFETIME') ?: 7200); // 2 hours
define('SESSION_SECURE', $isHttps); // Secure cookies only on HTTPS

// Environment Configuration
define('ENVIRONMENT', 'production');
define('DEBUG_MODE', false); // Disable debug in production

// Error Reporting
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('America/Chicago');

// Session Settings - FIXED: Only set secure flag when on HTTPS
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', SESSION_SECURE ? 1 : 0);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.cookie_lifetime', SESSION_LIFETIME);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

