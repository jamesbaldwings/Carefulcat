<?php
// Test password hashing and verification
// Upload this to /public_html/ and visit it in your browser

echo "<h1>Password Test</h1>";

// Test 1: Generate a new hash
$password = 'admin123';
$new_hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Test 1: Generate New Hash</h2>";
echo "Password: <strong>$password</strong><br>";
echo "New Hash: <code>$new_hash</code><br><br>";

// Test 2: Verify with the hash from database
$db_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "<h2>Test 2: Verify Database Hash</h2>";
echo "Database Hash: <code>$db_hash</code><br>";
echo "Testing password 'password': ";
if (password_verify('password', $db_hash)) {
    echo "<strong style='color: green;'>✓ MATCH</strong><br>";
} else {
    echo "<strong style='color: red;'>✗ NO MATCH</strong><br>";
}

echo "Testing password 'admin123': ";
if (password_verify('admin123', $db_hash)) {
    echo "<strong style='color: green;'>✓ MATCH</strong><br>";
} else {
    echo "<strong style='color: red;'>✗ NO MATCH</strong><br>";
}

// Test 3: Connect to database and check actual stored password
echo "<h2>Test 3: Check Database</h2>";

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

try {
    $admin = db()->fetchOne("SELECT email, password FROM admin_users WHERE email = ?", ['admin@carefulcatrescue.org']);
    
    if ($admin) {
        echo "Email found: <strong>{$admin['email']}</strong><br>";
        echo "Stored hash: <code>{$admin['password']}</code><br>";
        echo "Hash length: " . strlen($admin['password']) . " characters<br><br>";
        
        echo "Testing 'password': ";
        if (password_verify('password', $admin['password'])) {
            echo "<strong style='color: green;'>✓ MATCH</strong><br>";
        } else {
            echo "<strong style='color: red;'>✗ NO MATCH</strong><br>";
        }
        
        echo "Testing 'admin123': ";
        if (password_verify('admin123', $admin['password'])) {
            echo "<strong style='color: green;'>✓ MATCH</strong><br>";
        } else {
            echo "<strong style='color: red;'>✗ NO MATCH</strong><br>";
        }
    } else {
        echo "<strong style='color: red;'>Admin user not found!</strong>";
    }
} catch (Exception $e) {
    echo "<strong style='color: red;'>Error: " . $e->getMessage() . "</strong>";
}

echo "<h2>Recommendation</h2>";
echo "<p>Copy this SQL and run it in phpMyAdmin:</p>";
echo "<textarea style='width: 100%; height: 100px; font-family: monospace;'>";
echo "UPDATE admin_users SET password = '$new_hash' WHERE email = 'admin@carefulcatrescue.org';";
echo "</textarea>";
echo "<p>Then try logging in with password: <strong>admin123</strong></p>";
?>

