<?php
// Script to read the actual error log
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Reading Error Log</h2>";

$error_log_path = '/home/u774526707/logs/error_log_carefulcat_org';

echo "<p><strong>Attempting to read:</strong> $error_log_path</p>";

if (file_exists($error_log_path)) {
    echo "<p style='color: green;'><strong>File exists!</strong></p>";
    echo "<p>File size: " . filesize($error_log_path) . " bytes</p>";
    echo "<p>Last modified: " . date("Y-m-d H:i:s", filemtime($error_log_path)) . "</p>";
    echo "<p>Readable: " . (is_readable($error_log_path) ? "Yes" : "No") . "</p>";
    
    if (is_readable($error_log_path)) {
        // Display last 100 lines of the log
        echo "<h3>Last 100 lines of error log:</h3>";
        echo "<pre style='background: #f0f0f0; padding: 10px; overflow: auto; max-height: 600px; font-size: 12px;'>";
        $lines = file($error_log_path);
        if ($lines) {
            $last_lines = array_slice($lines, -100);
            echo htmlspecialchars(implode('', $last_lines));
        } else {
            echo "Could not read file contents.";
        }
        echo "</pre>";
        
        // Also show lines containing "admin" or "500" or "Fatal" or "Error"
        echo "<h3>Lines containing 'admin', '500', 'Fatal', or 'Error' (last 50 matches):</h3>";
        echo "<pre style='background: #ffe0e0; padding: 10px; overflow: auto; max-height: 400px; font-size: 12px;'>";
        $matches = [];
        foreach ($lines as $line) {
            if (stripos($line, 'admin') !== false || 
                stripos($line, '500') !== false || 
                stripos($line, 'Fatal') !== false || 
                stripos($line, 'Error') !== false) {
                $matches[] = $line;
            }
        }
        $last_matches = array_slice($matches, -50);
        echo htmlspecialchars(implode('', $last_matches));
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>File is not readable.</p>";
    }
} else {
    echo "<p style='color: red;'><strong>File does not exist!</strong></p>";
    
    // Try to find it
    echo "<h3>Searching for error log files...</h3>";
    $search_dirs = [
        '/home/u774526707/logs/',
        '/home/u774526707/',
        '/home/u774526707/domains/carefulcat.org/'
    ];
    
    foreach ($search_dirs as $dir) {
        if (is_dir($dir) && is_readable($dir)) {
            echo "<p>Checking $dir:</p>";
            $files = @scandir($dir);
            if ($files) {
                echo "<ul>";
                foreach ($files as $file) {
                    if (strpos($file, 'error') !== false || strpos($file, 'log') !== false) {
                        echo "<li><strong>$file</strong></li>";
                    }
                }
                echo "</ul>";
            }
        }
    }
}
?>
