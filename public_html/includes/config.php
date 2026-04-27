<?php
/**
 * Configuration File - Railway-compatible version
 * Reads from environment variables (set in Railway dashboard)
 * Falls back to .env file for local development
 */

// Load environment variables from .env file (for local dev only)
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
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

// Load .env file (local dev)
loadEnv(__DIR__ . '/../.env');

// Database Configuration - reads from environment variables (Railway injects these)
// Railway MySQL service provides: MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD
// Also supports standard DB_* variables
define('DB_HOST', getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: 'carefulcat_db');
define('DB_USER', getenv('MYSQLUSER') ?: getenv('DB_USER') ?: 'carefulcat_user');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: getenv('DB_PASS') ?: '');
define('DB_PORT', getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: '3306');
define('DB_CHARSET', 'utf8mb4');

// Stripe Configuration
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '');
define('STRIPE_PUBLIC_KEY', getenv('STRIPE_PUBLIC_KEY') ?: '');
define('STRIPE_WEBHOOK_SECRET', getenv('STRIPE_WEBHOOK_SECRET') ?: '');

// Detect HTTPS - handles Railway's proxy
$isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
    (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
);

// Site Configuration
$siteUrl = getenv('SITE_URL') ?: ($isHttps ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'carefulcat.org');
$protocol = $isHttps ? 'https' : 'http';
define('SITE_URL', rtrim($siteUrl, '/'));
define('SITE_NAME', getenv('SITE_NAME') ?: 'Careful Cat Rescue');
define('SITE_EMAIL', getenv('SITE_EMAIL') ?: 'carefulcatrescue@gmail.com');

// Path Configuration
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH);
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// URL Configuration
define('BASE_URL', SITE_URL);
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

// Upload Configuration
define('MAX_UPLOAD_SIZE', getenv('MAX_UPLOAD_SIZE') ?: 10485760); // 10MB
define('ALLOWED_IMAGE_TYPES', explode(',', getenv('ALLOWED_IMAGE_TYPES') ?: 'jpg,jpeg,png,gif,webp'));
define('ALLOWED_VIDEO_TYPES', explode(',', getenv('ALLOWED_VIDEO_TYPES') ?: 'mp4,webm,mov'));

// Session Configuration
define('SESSION_LIFETIME', getenv('SESSION_LIFETIME') ?: 7200); // 2 hours
define('SESSION_SECURE', $isHttps);

// Environment Configuration
$env = getenv('ENVIRONMENT') ?: 'production';
$debug = getenv('DEBUG_MODE') === 'true' ? true : false;
define('ENVIRONMENT', $env);
define('DEBUG_MODE', $debug);

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

// Session Settings
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
