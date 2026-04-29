<?php
/**
 * Admin Login Page - Hardened & Consistent
 * - Uses require_* includes
 * - Redirects early if already logged in
 * - Verifies with password_verify() via verifyPassword()
 * - Regenerates session ID on success
 * - Calls session_write_close() before redirect
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// If already logged in, bounce to dashboard
if (isAdminLoggedIn()) {
    session_write_close();                 // flush session before leaving
    redirect('/admin/index.php');
    exit;
}

$error   = '';
$success = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password';
    } else {
        try {
            // Lookup active admin by email
            $admin = db()->fetchOne(
                "SELECT * FROM admin_users WHERE email = ? AND is_active = 1",
                [$email]
            );

            if ($admin && verifyPassword($password, $admin['password'])) {
                // Prevent session fixation
                session_regenerate_id(true);

                // Store only the data you actually need
                $_SESSION['admin_id']    = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name']  = trim(($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? ''));
                $_SESSION['admin_role']  = $admin['role'] ?? 'admin';

                // Update last_login (best effort)
                db()->query("UPDATE admin_users SET last_login = NOW() WHERE id = ?", [$admin['id']]);

                // Close session before redirect to avoid race/lock issues
                session_write_close();

                // Go to dashboard
                redirect('/admin/index.php');
                exit;
            } else {
                $error = 'Invalid email or password';
            }
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again.';
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                $error .= ' (' . $e->getMessage() . ')';
            }
        }
    }
}

$pageTitle = 'Admin Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? ''); ?> - <?php echo htmlspecialchars(SITE_NAME ?? ''); ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/admin.css">
    <style>
        .login-container {
            max-width: 420px;
            margin: 96px auto;
            padding: 40px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }
        .login-logo { text-align: center; margin-bottom: 28px; }
        .login-logo h1 { color: #2e7d32; margin: 0; font-size: 24px; }
        .login-logo p { color: #666; margin: 6px 0 0; font-size: 14px; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display:block; margin-bottom:6px; color:#333; font-weight:500; }
        .form-group input {
            width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:14px; box-sizing:border-box;
        }
        .form-group input:focus { outline:none; border-color:#2e7d32; }

        .btn-login {
            width:100%; padding:12px; background:#2e7d32; color:#fff; border:none; border-radius:4px;
            font-size:16px; font-weight:600; cursor:pointer; transition:background .2s;
        }
        .btn-login:hover { background:#1b5e20; }

        .alert { padding:12px; border-radius:4px; margin-bottom:18px; line-height:1.35; }
        .alert-error { background:#ffebee; color:#b71c1c; border:1px solid #ef9a9a; }
        .alert-success { background:#e8f5e9; color:#1b5e20; border:1px solid #a5d6a7; }

        .back-link { text-align:center; margin-top:18px; }
        .back-link a { color:#2e7d32; text-decoration:none; }
        .back-link a:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <h1><?php echo htmlspecialchars(SITE_NAME ?? ''); ?></h1>
            <p>Admin Panel Login</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error ?? ''); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success ?? ''); ?></div>
        <?php endif; ?>

        <form method="POST" action="" autocomplete="on" novalidate>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'] ?? '') : ''; ?>"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="back-link">
            <a href="<?php echo BASE_URL; ?>">← Back to Website</a>
        </div>
    </div>
</body>
</html>
