<?php
// Debug Admin Login - Shows detailed information about login process
// Upload to /public_html/admin/ and visit it

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

echo "<h1>Admin Login Debug</h1>";
echo "<style>body { font-family: Arial; padding: 20px; } .success { color: green; } .error { color: red; } code { background: #f4f4f4; padding: 2px 5px; }</style>";

// Check if already logged in
echo "<h2>1. Session Check</h2>";
echo "Session ID: <code>" . session_id() . "</code><br>";
echo "Session Status: <code>" . session_status() . "</code> (1=disabled, 2=active)<br>";
echo "Admin ID in session: <code>" . ($_SESSION['admin_id'] ?? 'NOT SET') . "</code><br>";
echo "Is Admin Logged In: <strong class='" . (isAdminLoggedIn() ? 'success' : 'error') . "'>" . (isAdminLoggedIn() ? 'YES' : 'NO') . "</strong><br>";

// Process login
$error = '';
$debug_info = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>2. Login Attempt</h2>";
    
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    echo "Email entered: <code>$email</code><br>";
    echo "Password entered: <code>" . str_repeat('*', strlen($password)) . "</code> (" . strlen($password) . " characters)<br><br>";
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
        echo "<p class='error'>❌ $error</p>";
    } else {
        // Find admin user
        echo "<h3>Database Query</h3>";
        try {
            $admin = db()->fetchOne("SELECT * FROM admin_users WHERE email = ? AND is_active = 1", [$email]);
            
            if ($admin) {
                echo "<p class='success'>✓ User found in database</p>";
                echo "User ID: <code>{$admin['id']}</code><br>";
                echo "Email: <code>{$admin['email']}</code><br>";
                echo "Name: <code>{$admin['first_name']} {$admin['last_name']}</code><br>";
                echo "Role: <code>{$admin['role']}</code><br>";
                echo "Is Active: <code>" . (($admin['is_active'] ?? null) ? 'YES' : 'NO') . "</code><br>";
                echo "Password Hash: <code>" . substr($admin['password'] ?? '',  0,  20) . "...</code> (" . strlen($admin['password'] ?? '') . " chars)<br><br>";
                
                echo "<h3>Password Verification</h3>";
                $password_match = verifyPassword($password, $admin['password']);
                
                if ($password_match) {
                    echo "<p class='success'>✓ Password MATCHES!</p>";
                    
                    // Set session
                    echo "<h3>Setting Session</h3>";
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_name'] = ($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? '');
                    $_SESSION['admin_role'] = $admin['role'];
                    
                    echo "Session admin_id: <code>{$_SESSION['admin_id']}</code><br>";
                    echo "Session admin_email: <code>{$_SESSION['admin_email']}</code><br>";
                    echo "Session admin_name: <code>{$_SESSION['admin_name']}</code><br>";
                    echo "Session admin_role: <code>{$_SESSION['admin_role']}</code><br>";
                    
                    // Update last login
                    try {
                        db()->query("UPDATE admin_users SET last_login = NOW() WHERE id = ?", [$admin['id']]);
                        echo "<p class='success'>✓ Last login updated</p>";
                    } catch (Exception $e) {
                        echo "<p class='error'>⚠ Could not update last login: " . $e->getMessage() . "</p>";
                    }
                    
                    echo "<h3>✅ LOGIN SUCCESSFUL!</h3>";
                    echo "<p>You should now be able to access: <a href='/admin/index.php'>/admin/index.php</a></p>";
                    echo "<p><a href='/admin/index.php' class='btn'>Go to Admin Dashboard</a></p>";
                    
                } else {
                    echo "<p class='error'>❌ Password does NOT match</p>";
                    echo "<p>Testing password verification:</p>";
                    echo "password_verify('$password', hash): " . (password_verify($password, $admin['password']) ? 'TRUE' : 'FALSE') . "<br>";
                    $error = 'Invalid email or password';
                }
            } else {
                echo "<p class='error'>❌ No user found with email: $email (or user is not active)</p>";
                $error = 'Invalid email or password';
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
            $error = 'Database error occurred';
        }
    }
}

// Show login form
?>
<hr>
<h2>Login Form</h2>
<?php if ($error): ?>
    <div style="background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0;">
        <?php echo htmlspecialchars($error ?? ''); ?>
    </div>
<?php endif; ?>

<form method="POST" action="" style="max-width: 400px;">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Email</label>
        <input type="email" name="email" value="admin@carefulcatrescue.org" style="width: 100%; padding: 8px;" required>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Password</label>
        <input type="password" name="password" value="password" style="width: 100%; padding: 8px;" required>
    </div>
    
    <button type="submit" style="background: #2e7d32; color: white; padding: 10px 20px; border: none; cursor: pointer;">
        Test Login
    </button>
</form>

<hr>
<h2>Quick Tests</h2>
<p><a href="?test=session">Test Session</a> | <a href="?test=db">Test Database</a> | <a href="?test=functions">Test Functions</a></p>

<?php
if (isset($_GET['test'])) {
    switch ($_GET['test']) {
        case 'session':
            echo "<h3>Session Test</h3>";
            $_SESSION['test'] = 'Hello World';
            echo "Set session test value: " . ($_SESSION['test'] ?? '') . "<br>";
            echo "Session works: <strong class='success'>YES</strong>";
            break;
            
        case 'db':
            echo "<h3>Database Test</h3>";
            try {
                $count = db()->fetchOne("SELECT COUNT(*) as count FROM admin_users")['count'];
                echo "Admin users in database: <strong>$count</strong><br>";
                echo "Database connection: <strong class='success'>OK</strong>";
            } catch (Exception $e) {
                echo "Database error: <strong class='error'>" . $e->getMessage() . "</strong>";
            }
            break;
            
        case 'functions':
            echo "<h3>Functions Test</h3>";
            echo "isAdminLoggedIn() exists: " . (function_exists('isAdminLoggedIn') ? 'YES' : 'NO') . "<br>";
            echo "verifyPassword() exists: " . (function_exists('verifyPassword') ? 'YES' : 'NO') . "<br>";
            echo "redirect() exists: " . (function_exists('redirect') ? 'YES' : 'NO') . "<br>";
            echo "sanitize() exists: " . (function_exists('sanitize') ? 'YES' : 'NO') . "<br>";
            break;
    }
}
?>

<hr>
<p><a href="/admin/login.php">← Back to Normal Login</a></p>

