<?php
/**
 * Helper Functions
 * Utility functions used throughout the application
 */

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number
 */
function isValidPhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) >= 10;
}

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect to URL
 */
function redirect($url, $statusCode = 302) {
    header("Location: $url", true, $statusCode);
}

/**
 * Get current URL
 */
function currentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Format currency
 */
function formatCurrency($cents) {
    return '$' . number_format($cents / 100, 2);
}

/**
 * Format date
 */
function formatDate($date, $format = 'M d, Y') {
    if (empty($date)) return '';
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date($format, $timestamp);
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = 'M d, Y g:i A') {
    if (empty($datetime)) return '';
    $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
    return date($format, $timestamp);
}

/**
 * Time ago format
 */
function timeAgo($datetime) {
    $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    if ($diff < 2592000) return floor($diff / 604800) . ' weeks ago';
    if ($diff < 31536000) return floor($diff / 2592000) . ' months ago';
    return floor($diff / 31536000) . ' years ago';
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Generate slug from text
 */
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Upload file
 */
function uploadFile($file, $allowedTypes = [], $maxSize = MAX_UPLOAD_SIZE, $destination = UPLOAD_PATH) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid file upload'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed with error code: ' . $file['error']];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File size exceeds maximum allowed size'];
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $extension = array_search($mimeType, [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'mov' => 'video/quicktime'
    ], true);
    
    if ($extension === false || !in_array($extension, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type'];
    }
    
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    $filepath = $destination . '/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
    
    return ['success' => true, 'filename' => $filename, 'path' => $filepath];
}

/**
 * Delete file
 */
function deleteFile($filename, $directory = UPLOAD_PATH) {
    $filepath = $directory . '/' . $filename;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Get system setting
 */
function getSetting($key, $default = null) {
    $setting = db()->fetchOne("SELECT setting_value FROM system_settings WHERE setting_key = ?", [$key]);
    return $setting ? $setting['setting_value'] : $default;
}

/**
 * Update system setting
 */
function updateSetting($key, $value, $description = null) {
    $existing = db()->fetchOne("SELECT id FROM system_settings WHERE setting_key = ?", [$key]);
    
    if ($existing) {
        $data = ['setting_value' => $value];
        if ($description !== null) {
            $data['description'] = $description;
        }
        return db()->update('system_settings', $data, 'setting_key = ?', [$key]);
    } else {
        return db()->insert('system_settings', [
            'setting_key' => $key,
            'setting_value' => $value,
            'description' => $description
        ]);
    }
}

/**
 * Check if page is visible
 */
function isPageVisible($page) {
    $key = "page_{$page}_visible";
    $value = getSetting($key, 'true');
    return $value === 'true' || $value === '1';
}

/**
 * Send JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get JSON input
 */
function getJsonInput() {
    $input = file_get_contents('php://input');
    return json_decode($input ?? '{}', true);
}

/**
 * Validate required fields
 */
function validateRequired($data, $fields) {
    $errors = [];
    foreach ($fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }
    return $errors;
}

/**
 * Generate UUID v4
 */
function generateUuid() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * Check if user is logged in as admin
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Get logged in admin
 */
function getLoggedInAdmin() {
    if (!isAdminLoggedIn()) {
        return null;
    }
    
    return db()->fetchOne("SELECT * FROM admin_users WHERE id = ?", [$_SESSION['admin_id']]);
}

/**
 * Require admin login
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        redirect('/admin/login.php');
    }
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Escape HTML
 */
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Get page visibility settings
 */
function getPageVisibility() {
    return [
        'adoptions' => isPageVisible('adoptions'),
        'residents' => isPageVisible('residents'),
        'blog' => isPageVisible('blog'),
        'shop' => isPageVisible('shop'),
        'book_visit' => isPageVisible('book_visit'),
        'volunteer' => isPageVisible('volunteer'),
        'volunteer_events' => isPageVisible('volunteer_events')
    ];
}

// --- Flash message helpers (add to bottom of functions.php) ---
if (!function_exists('flash')) {
    function flash($key, $val) {
        $_SESSION['flash'][$key] = $val;
    }
}

if (!function_exists('flash_out')) {
    function flash_out($key) {
        if (!empty($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return '';
    }
}

// --- Safe redirect helper (keeps session handling clean) ---
if (!function_exists('redirect')) {
    function redirect($path) {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close(); // prevent locked sessions
        }
        header('Location: ' . $path);
        exit;
    }
}

// --- CSRF token helpers (aliases for consistency) ---
if (!function_exists('csrf_token')) {
    function csrf_token() {
        return generateCsrfToken();
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify($token) {
        return verifyCsrfToken($token);
    }
}
