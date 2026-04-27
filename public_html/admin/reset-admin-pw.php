<?php
/**
 * One-time admin password reset script
 * DELETE THIS FILE AFTER USE
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$secret = 'CarefulCatReset2026';

if (($_GET['key'] ?? '') !== $secret) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$newEmail = 'carefulcatrescue@gmail.com';
$newPassword = 'CarefulCat2026!';
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    // Check existing admin users
    $admins = db()->fetchAll("SELECT id, email, first_name, last_name, role, is_active FROM admin_users");
    echo "<h3>Current admin users:</h3><pre>";
    print_r($admins);
    echo "</pre>";

    if (empty($admins)) {
        // No admin users - insert one
        db()->query(
            "INSERT INTO admin_users (email, password, first_name, last_name, role, is_active) VALUES (?, ?, 'Admin', 'User', 'super_admin', 1)",
            [$newEmail, $hash]
        );
        echo "<p>Created new admin: $newEmail / $newPassword</p>";
    } else {
        // Update the first admin user
        $adminId = $admins[0]['id'];
        db()->query(
            "UPDATE admin_users SET email = ?, password = ?, is_active = 1 WHERE id = ?",
            [$newEmail, $hash, $adminId]
        );
        echo "<p>Updated admin to: $newEmail / $newPassword</p>";
    }
    echo "<p><strong>DELETE THIS FILE NOW!</strong></p>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
