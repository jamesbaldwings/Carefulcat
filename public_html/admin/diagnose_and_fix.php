<?php
/**
 * CarefulCat.org Admin Dashboard Diagnostic and Fix Script
 * 
 * This script diagnoses and attempts to fix the HTTP 500 error
 * on the admin dashboard.
 * 
 * USAGE: Upload this file to /public_html/admin/ and access via browser
 */

// Security: Only allow access from localhost or specific IPs
$allowed_ips = ['127.0.0.1', '::1']; // Add your IP here if needed
// Uncomment the next line to enable IP restriction
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) die('Access denied');

?>
<!DOCTYPE html>
<html>
<head>
    <title>CarefulCat Admin Diagnostic Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 12px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 12px; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .btn { background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin: 5px; }
        .btn:hover { background: #45a049; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 CarefulCat Admin Diagnostic Tool</h1>
        
        <?php
        
        $diagnostics = [];
        $fixes_applied = [];
        $errors = [];
        
        // Check if fix is requested
        $apply_fix = isset($_GET['fix']) && $_GET['fix'] === 'htaccess';
        
        // 1. Check current directory
        $current_dir = __DIR__;
        $diagnostics[] = "Current directory: <code>$current_dir</code>";
        
        // 2. Check for .htaccess files
        $htaccess_locations = [
            $current_dir . '/.htaccess',
            dirname($current_dir) . '/.htaccess',
        ];
        
        $htaccess_found = [];
        foreach ($htaccess_locations as $location) {
            if (file_exists($location)) {
                $htaccess_found[] = $location;
            }
        }
        
        if (empty($htaccess_found)) {
            $diagnostics[] = "<div class='warning'>⚠️ No .htaccess file found in admin or parent directory</div>";
        } else {
            $diagnostics[] = "<div class='info'>📄 Found .htaccess file(s): " . implode(', ', array_map(function($f) { return "<code>$f</code>"; }, $htaccess_found)) . "</div>";
        }
        
        // 3. Analyze .htaccess content
        foreach ($htaccess_found as $htaccess_file) {
            $content = file_get_contents($htaccess_file);
            $diagnostics[] = "<h2>Analyzing: <code>" . basename(dirname($htaccess_file)) . "/.htaccess</code></h2>";
            
            // Check for common issues
            $issues = [];
            
            // Check for concatenated directives (missing line breaks)
            if (preg_match('/RewriteCond.*RewriteEngine/i', $content)) {
                $issues[] = "❌ Found concatenated directives: RewriteCond and RewriteEngine on same line";
            }
            if (preg_match('/RewriteRule.*RewriteRule/i', $content)) {
                $issues[] = "❌ Found concatenated RewriteRule directives";
            }
            if (preg_match('/RewriteCond.*RewriteRule.*RewriteRule/i', $content)) {
                $issues[] = "❌ Found multiple directives concatenated without line breaks";
            }
            if (preg_match('/#[^\\n]*Rewrite/i', $content)) {
                $issues[] = "❌ Found comment concatenated with directive";
            }
            
            // Check for malformed conditions
            if (preg_match('/RewriteCond[^\\n]*off[^\\n]*Rewrite/i', $content)) {
                $issues[] = "❌ CRITICAL: Malformed HTTPS redirect rule detected";
            }
            
            if (!empty($issues)) {
                $diagnostics[] = "<div class='error'><strong>Issues found in .htaccess:</strong><ul><li>" . implode('</li><li>', $issues) . "</li></ul></div>";
                
                $diagnostics[] = "<h3>Current .htaccess content:</h3>";
                $diagnostics[] = "<pre>" . htmlspecialchars($content) . "</pre>";
                
                // Offer to fix
                if (!$apply_fix) {
                    $diagnostics[] = "<div class='warning'>";
                    $diagnostics[] = "<p><strong>⚠️ This .htaccess file is causing the HTTP 500 error!</strong></p>";
                    $diagnostics[] = "<p>Click the button below to automatically fix it:</p>";
                    $diagnostics[] = "<form method='get'>";
                    $diagnostics[] = "<input type='hidden' name='fix' value='htaccess'>";
                    $diagnostics[] = "<input type='hidden' name='file' value='" . htmlspecialchars($htaccess_file) . "'>";
                    $diagnostics[] = "<button type='submit' class='btn'>🔧 Fix .htaccess File</button>";
                    $diagnostics[] = "</form>";
                    $diagnostics[] = "</div>";
                } else {
                    // Apply the fix
                    if (isset($_GET['file']) && $_GET['file'] === $htaccess_file) {
                        $fixed_content = "RewriteEngine On\nRewriteBase /\n\n# Force HTTPS\nRewriteCond %{HTTPS} off\nRewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n\n# Remove .php extension\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteCond %{REQUEST_FILENAME}.php -f\nRewriteRule ^(.*)$ $1.php [L]\n";
                        
                        // Backup original file
                        $backup_file = $htaccess_file . '.backup.' . date('Y-m-d_H-i-s');
                        if (copy($htaccess_file, $backup_file)) {
                            $fixes_applied[] = "✅ Created backup: <code>$backup_file</code>";
                        } else {
                            $errors[] = "❌ Failed to create backup file";
                        }
                        
                        // Write fixed content
                        if (file_put_contents($htaccess_file, $fixed_content)) {
                            $fixes_applied[] = "✅ Successfully fixed .htaccess file!";
                            $fixes_applied[] = "<h3>New .htaccess content:</h3>";
                            $fixes_applied[] = "<pre>" . htmlspecialchars($fixed_content) . "</pre>";
                        } else {
                            $errors[] = "❌ Failed to write fixed .htaccess file. Check file permissions.";
                        }
                    }
                }
            } else {
                $diagnostics[] = "<div class='success'>✅ No obvious issues found in this .htaccess file</div>";
                $diagnostics[] = "<h3>Current .htaccess content:</h3>";
                $diagnostics[] = "<pre>" . htmlspecialchars($content) . "</pre>";
            }
        }
        
        // 4. Check login.php
        $login_file = $current_dir . '/login.php';
        if (file_exists($login_file)) {
            $diagnostics[] = "<h2>Analyzing: <code>login.php</code></h2>";
            $login_content = file_get_contents($login_file);
            
            // Check for session_write_close before redirect
            if (preg_match('/redirect\\([^)]+\\);/i', $login_content)) {
                if (!preg_match('/session_write_close\\(\\);[\\s\\n]*redirect\\(/i', $login_content)) {
                    $diagnostics[] = "<div class='warning'>⚠️ <strong>Potential issue:</strong> login.php calls redirect() without session_write_close() first. This can cause redirect loops.</div>";
                    $diagnostics[] = "<p><strong>Recommended fix:</strong> Add <code>session_write_close();</code> before the <code>redirect()</code> call in login.php</p>";
                } else {
                    $diagnostics[] = "<div class='success'>✅ login.php correctly calls session_write_close() before redirect</div>";
                }
            }
        } else {
            $diagnostics[] = "<div class='info'>ℹ️ login.php not found in current directory</div>";
        }
        
        // 5. Check index.php
        $index_file = $current_dir . '/index.php';
        if (file_exists($index_file)) {
            $diagnostics[] = "<h2>Analyzing: <code>index.php</code></h2>";
            $diagnostics[] = "<div class='success'>✅ index.php exists</div>";
            
            // Check for syntax errors
            $output = [];
            $return_var = 0;
            exec("php -l " . escapeshellarg($index_file) . " 2>&1", $output, $return_var);
            if ($return_var === 0) {
                $diagnostics[] = "<div class='success'>✅ No PHP syntax errors in index.php</div>";
            } else {
                $diagnostics[] = "<div class='error'>❌ PHP syntax errors found in index.php:<pre>" . htmlspecialchars(implode("\n", $output)) . "</pre></div>";
            }
        } else {
            $diagnostics[] = "<div class='error'>❌ index.php not found!</div>";
        }
        
        // 6. Check PHP version and modules
        $diagnostics[] = "<h2>Server Information</h2>";
        $diagnostics[] = "<div class='info'>";
        $diagnostics[] = "PHP Version: <code>" . PHP_VERSION . "</code><br>";
        $diagnostics[] = "Server Software: <code>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</code><br>";
        $diagnostics[] = "Document Root: <code>" . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</code>";
        $diagnostics[] = "</div>";
        
        // Display results
        if (!empty($fixes_applied)) {
            echo "<div class='success'><h2>✅ Fixes Applied</h2>";
            foreach ($fixes_applied as $fix) {
                echo $fix;
            }
            echo "<p><strong>Next steps:</strong></p>";
            echo "<ol>";
            echo "<li>Test the admin dashboard: <a href='/admin/index.php' target='_blank'>Open Admin Dashboard</a></li>";
            echo "<li>If it works, delete this diagnostic script for security</li>";
            echo "<li>If you still see errors, check the diagnostics below</li>";
            echo "</ol>";
            echo "</div>";
        }
        
        if (!empty($errors)) {
            echo "<div class='error'><h2>❌ Errors</h2>";
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo "</div>";
        }
        
        echo "<h2>📋 Diagnostic Results</h2>";
        foreach ($diagnostics as $diag) {
            echo $diag;
        }
        
        ?>
        
        <h2>🔒 Security Notice</h2>
        <div class="warning">
            <p><strong>Important:</strong> Delete this diagnostic script after fixing the issues!</p>
            <p>This script can expose sensitive information about your server configuration.</p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return confirm('Are you sure you want to delete this script?');">
                <input type="hidden" name="delete_self" value="1">
                <button type="submit" class="btn btn-danger">🗑️ Delete This Script</button>
            </form>
        </div>
        
        <?php
        // Self-delete functionality
        if (isset($_POST['delete_self']) && $_POST['delete_self'] === '1') {
            if (unlink(__FILE__)) {
                echo "<div class='success'>✅ Diagnostic script deleted successfully!</div>";
                echo "<script>setTimeout(function(){ window.location.href = '/admin/'; }, 2000);</script>";
            } else {
                echo "<div class='error'>❌ Failed to delete script. Please delete manually: <code>" . __FILE__ . "</code></div>";
            }
        }
        ?>
        
    </div>
</body>
</html>
