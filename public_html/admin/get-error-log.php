<?php
// Script to find and display error logs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Error Log Finder</h2>";

// Get error log location from php.ini
$error_log_path = ini_get('error_log');
echo "<p><strong>PHP error_log setting:</strong> " . ($error_log_path ? $error_log_path : "Not set (using default)") . "</p>";

// Common error log locations
$possible_logs = [
    __DIR__ . '/error_log',
    __DIR__ . '/../error_log',
    '/home/u774526707/domains/carefulcat.org/public_html/error_log',
    '/home/u774526707/domains/carefulcat.org/public_html/admin/error_log',
    '/var/log/php_errors.log',
    '/tmp/php_errors.log'
];

echo "<h3>Checking common error log locations:</h3>";
foreach ($possible_logs as $log) {
    if (file_exists($log)) {
        echo "<p style='color: green;'><strong>FOUND:</strong> $log</p>";
        echo "<p>File size: " . filesize($log) . " bytes</p>";
        echo "<p>Last modified: " . date("Y-m-d H:i:s", filemtime($log)) . "</p>";
        
        // Display last 50 lines of the log
        echo "<h4>Last 50 lines of $log:</h4>";
        echo "<pre style='background: #f0f0f0; padding: 10px; overflow: auto; max-height: 400px;'>";
        $lines = file($log);
        $last_lines = array_slice($lines, -50);
        echo htmlspecialchars(implode('', $last_lines));
        echo "</pre>";
    } else {
        echo "<p style='color: gray;'>Not found: $log</p>";
    }
}

// Try to trigger an error and see where it goes
echo "<h3>Testing error logging:</h3>";
error_log("TEST ERROR from get-error-log.php at " . date('Y-m-d H:i:s'));
echo "<p>Test error logged. Check the logs above.</p>";

// Display current directory
echo "<h3>Current Directory:</h3>";
echo "<p>" . __DIR__ . "</p>";

// List files in current directory
echo "<h3>Files in current directory:</h3>";
echo "<pre>";
$files = scandir(__DIR__);
foreach ($files as $file) {
    if (strpos($file, 'error') !== false || strpos($file, 'log') !== false) {
        echo "<strong>$file</strong>\n";
    } else {
        echo "$file\n";
    }
}
echo "</pre>";
?>
