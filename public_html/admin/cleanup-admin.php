<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$secret = 'CleanupAdmin2026';
if (($_GET['key'] ?? '') !== $secret) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

try {
    db()->query("DELETE FROM admin_users WHERE email = ?", ['aviceen@gmail.com']);
    $remaining = db()->fetchAll("SELECT id, email, role, is_active FROM admin_users");
    echo "Deleted aviceen@gmail.com\n\nRemaining admins:\n";
    print_r($remaining);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
