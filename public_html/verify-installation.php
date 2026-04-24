<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carful Cat Rescue - Installation Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #2d6a4f 0%, #52b788 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: #2d6a4f;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 30px;
        }
        .check-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .check-item.pass {
            background: #d4edda;
            border-left-color: #28a745;
        }
        .check-item.fail {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        .check-item.warning {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        .icon {
            font-size: 24px;
            margin-right: 15px;
            min-width: 30px;
        }
        .check-content {
            flex: 1;
        }
        .check-content h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }
        .check-content p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }
        .check-content code {
            background: rgba(0,0,0,0.05);
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 20px;
            color: #2d6a4f;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        .summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .summary-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 6px;
        }
        .summary-item .number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .summary-item .label {
            font-size: 14px;
            color: #666;
        }
        .pass .number { color: #28a745; }
        .fail .number { color: #dc3545; }
        .warning .number { color: #ffc107; }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .next-steps {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .next-steps h3 {
            color: #1976D2;
            margin-bottom: 10px;
        }
        .next-steps ol {
            margin-left: 20px;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐱 Installation Verification</h1>
            <p>Carful Cat Rescue Website - Deployment Status Check</p>
        </div>
        
        <div class="content">
            <?php
            $checks = [
                'pass' => 0,
                'fail' => 0,
                'warning' => 0
            ];
            
            function checkItem($title, $description, $status) {
                global $checks;
                $checks[$status]++;
                $icons = [
                    'pass' => '✅',
                    'fail' => '❌',
                    'warning' => '⚠️'
                ];
                echo "<div class='check-item {$status}'>";
                echo "<div class='icon'>{$icons[$status]}</div>";
                echo "<div class='check-content'>";
                echo "<h3>{$title}</h3>";
                echo "<p>{$description}</p>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            
            <div class="summary">
                <h2 style="margin: 0 0 15px 0; border: none;">Installation Summary</h2>
                <div class="summary-grid">
                    <?php
                    // Run all checks first to get counts
                    ob_start();
                    
                    echo "<div class='section'><h2>📁 File Structure</h2>";
                    
                    // Check critical directories
                    $dirs = [
                        'public' => 'Main website files',
                        'admin' => 'Admin panel',
                        'includes' => 'PHP core files',
                        'api' => 'API endpoints',
                        'database' => 'Database files'
                    ];
                    
                    foreach ($dirs as $dir => $desc) {
                        if (is_dir(__DIR__ . '/' . $dir)) {
                            checkItem("{$dir}/ folder exists", "Contains {$desc}", 'pass');
                        } else {
                            checkItem("{$dir}/ folder missing", "Required for {$desc}", 'fail');
                        }
                    }
                    
                    // Check critical files
                    $files = [
                        'includes/config.php' => 'Configuration file',
                        'includes/db.php' => 'Database connection',
                        'includes/functions.php' => 'Helper functions',
                        'public/index.php' => 'Homepage',
                        'admin/login.php' => 'Admin login',
                        'database/schema.sql' => 'Database schema'
                    ];
                    
                    foreach ($files as $file => $desc) {
                        if (file_exists(__DIR__ . '/' . $file)) {
                            checkItem(basename($file) . " exists", "Located at: <code>{$file}</code>", 'pass');
                        } else {
                            checkItem(basename($file) . " missing", "Should be at: <code>{$file}</code>", 'fail');
                        }
                    }
                    
                    echo "</div>";
                    
                    // PHP Environment
                    echo "<div class='section'><h2>🔧 PHP Environment</h2>";
                    
                    $phpVersion = phpversion();
                    if (version_compare($phpVersion, '7.4.0', '>=')) {
                        checkItem("PHP Version: {$phpVersion}", "Meets minimum requirement (7.4+)", 'pass');
                    } else {
                        checkItem("PHP Version: {$phpVersion}", "Upgrade to PHP 7.4 or higher required", 'fail');
                    }
                    
                    $extensions = ['mysqli', 'json', 'mbstring', 'curl'];
                    foreach ($extensions as $ext) {
                        if (extension_loaded($ext)) {
                            checkItem("{$ext} extension loaded", "Required extension is available", 'pass');
                        } else {
                            checkItem("{$ext} extension missing", "Install this PHP extension", 'fail');
                        }
                    }
                    
                    echo "</div>";
                    
                    // Configuration
                    echo "<div class='section'><h2>⚙️ Configuration</h2>";
                    
                    if (file_exists(__DIR__ . '/includes/config.php')) {
                        require_once __DIR__ . '/includes/config.php';
                        
                        if (defined('DB_NAME') && DB_NAME !== 'your_database_name') {
                            checkItem("Database name configured", "DB_NAME is set to: <code>" . DB_NAME . "</code>", 'pass');
                        } else {
                            checkItem("Database name not configured", "Edit <code>includes/config.php</code> and set DB_NAME", 'fail');
                        }
                        
                        if (defined('DB_USER') && DB_USER !== 'your_database_username') {
                            checkItem("Database user configured", "DB_USER is set", 'pass');
                        } else {
                            checkItem("Database user not configured", "Edit <code>includes/config.php</code> and set DB_USER", 'fail');
                        }
                        
                        if (defined('DB_PASS') && DB_PASS !== 'your_database_password') {
                            checkItem("Database password configured", "DB_PASS is set", 'pass');
                        } else {
                            checkItem("Database password not configured", "Edit <code>includes/config.php</code> and set DB_PASS", 'fail');
                        }
                        
                        if (defined('SITE_URL') && SITE_URL !== 'http://localhost') {
                            checkItem("Site URL configured", "SITE_URL is set to: <code>" . SITE_URL . "</code>", 'pass');
                        } else {
                            checkItem("Site URL not updated", "Change SITE_URL to <code>https://carfulcat.org</code>", 'warning');
                        }
                    }
                    
                    echo "</div>";
                    
                    // Database Connection
                    echo "<div class='section'><h2>🗄️ Database Connection</h2>";
                    
                    if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
                        $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                        
                        if ($conn->connect_error) {
                            checkItem("Database connection failed", "Error: " . $conn->connect_error . "<br>Check your credentials in config.php", 'fail');
                        } else {
                            checkItem("Database connection successful", "Connected to <code>" . DB_NAME . "</code>", 'pass');
                            
                            // Check if tables exist
                            $tables = ['cats', 'donations', 'adoptions', 'volunteers', 'blog_posts', 'sponsors', 'users', 'settings'];
                            $missingTables = [];
                            
                            foreach ($tables as $table) {
                                $result = $conn->query("SHOW TABLES LIKE '{$table}'");
                                if ($result->num_rows == 0) {
                                    $missingTables[] = $table;
                                }
                            }
                            
                            if (empty($missingTables)) {
                                checkItem("Database tables exist", "All " . count($tables) . " core tables found", 'pass');
                            } else {
                                checkItem("Database tables missing", "Missing tables: <code>" . implode(', ', $missingTables) . "</code><br>Import <code>database/schema.sql</code> via phpMyAdmin", 'fail');
                            }
                            
                            $conn->close();
                        }
                    } else {
                        checkItem("Database not configured", "Complete configuration in <code>includes/config.php</code> first", 'warning');
                    }
                    
                    echo "</div>";
                    
                    // File Permissions
                    echo "<div class='section'><h2>🔐 File Permissions</h2>";
                    
                    $uploadDir = __DIR__ . '/public/uploads';
                    if (is_dir($uploadDir)) {
                        if (is_writable($uploadDir)) {
                            checkItem("Uploads folder writable", "Cat photos can be uploaded", 'pass');
                        } else {
                            checkItem("Uploads folder not writable", "Set permissions to 777 for <code>public/uploads/</code>", 'fail');
                        }
                    } else {
                        checkItem("Uploads folder missing", "Create <code>public/uploads/</code> folder with 777 permissions", 'fail');
                    }
                    
                    echo "</div>";
                    
                    $output = ob_get_clean();
                    ?>
                    
                    <div class="summary-item pass">
                        <div class="number"><?php echo $checks['pass']; ?></div>
                        <div class="label">Passed</div>
                    </div>
                    <div class="summary-item fail">
                        <div class="number"><?php echo $checks['fail']; ?></div>
                        <div class="label">Failed</div>
                    </div>
                    <div class="summary-item warning">
                        <div class="number"><?php echo $checks['warning']; ?></div>
                        <div class="label">Warnings</div>
                    </div>
                </div>
            </div>
            
            <?php echo $output; ?>
            
            <?php if ($checks['fail'] > 0): ?>
            <div class="next-steps">
                <h3>🔧 Next Steps to Fix Issues</h3>
                <ol>
                    <li>Review the failed checks above</li>
                    <li>If database is not configured, edit <code>includes/config.php</code></li>
                    <li>If tables are missing, import <code>database/schema.sql</code> via phpMyAdmin</li>
                    <li>If files are missing, re-upload from <code>HOSTINGER-UPLOAD-ONLY.tar.gz</code></li>
                    <li>Refresh this page to re-check</li>
                </ol>
            </div>
            <?php elseif ($checks['warning'] > 0): ?>
            <div class="next-steps">
                <h3>⚠️ Almost There!</h3>
                <ol>
                    <li>Address the warnings above (they're not critical but recommended)</li>
                    <li>Visit <a href="public/">your website</a> to see it live</li>
                    <li>Visit <a href="admin/">admin panel</a> to log in</li>
                    <li>Default login: admin@carefulcatrescue.org / admin123</li>
                    <li><strong>Change the admin password immediately!</strong></li>
                </ol>
            </div>
            <?php else: ?>
            <div class="next-steps">
                <h3>🎉 Installation Complete!</h3>
                <ol>
                    <li>Visit <a href="public/" target="_blank">your website</a> to see it live</li>
                    <li>Visit <a href="admin/" target="_blank">admin panel</a> to manage content</li>
                    <li>Default login: <code>admin@carefulcatrescue.org</code> / <code>admin123</code></li>
                    <li><strong>Change the admin password in Settings!</strong></li>
                    <li>Add your Stripe API keys in Admin → Settings</li>
                    <li>Start adding cats, blog posts, and sponsors!</li>
                </ol>
            </div>
            <?php endif; ?>
            
        </div>
        
        <div class="footer">
            <p>Carful Cat Rescue Website v1.0 | Deployed on Hostinger</p>
            <p style="margin-top: 5px; font-size: 12px;">Delete this file (verify-installation.php) after successful deployment</p>
        </div>
    </div>
</body>
</html>

