<?php
// Script to check PHP syntax of files
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>PHP Syntax Checker</h2>";

$files_to_check = [
    'index.php',
    'login.php',
    'logout.php',
    '../includes/config.php',
    '../includes/db.php',
    '../includes/functions.php',
    '../includes/header.php',
    '../includes/footer.php'
];

echo "<h3>Checking files for syntax errors:</h3>";

foreach ($files_to_check as $file) {
    $full_path = __DIR__ . '/' . $file;
    echo "<div style='margin: 10px 0; padding: 10px; background: #f0f0f0;'>";
    echo "<strong>File: $file</strong><br>";
    
    if (!file_exists($full_path)) {
        echo "<span style='color: orange;'>File not found: $full_path</span>";
    } else {
        // Check syntax using php -l
        $output = [];
        $return_var = 0;
        exec("php -l " . escapeshellarg($full_path) . " 2>&1", $output, $return_var);
        
        if ($return_var === 0) {
            echo "<span style='color: green;'>✓ Syntax OK</span>";
        } else {
            echo "<span style='color: red;'>✗ SYNTAX ERROR:</span><br>";
            echo "<pre style='background: #ffeeee; padding: 5px; margin: 5px 0;'>";
            echo htmlspecialchars(implode("\n", $output));
            echo "</pre>";
        }
    }
    echo "</div>";
}

// Also check for common issues in index.php
echo "<h3>Analyzing index.php content:</h3>";
$index_file = __DIR__ . '/index.php';
if (file_exists($index_file)) {
    $content = file_get_contents($index_file);
    echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
    echo "<p>File size: " . strlen($content) . " bytes</p>";
    echo "<p>Lines: " . substr_count($content, "\n") . "</p>";
    
    // Count parentheses
    $open_paren = substr_count($content, '(');
    $close_paren = substr_count($content, ')');
    echo "<p>Opening parentheses: $open_paren</p>";
    echo "<p>Closing parentheses: $close_paren</p>";
    if ($open_paren != $close_paren) {
        echo "<p style='color: red; font-weight: bold;'>⚠ Parentheses mismatch!</p>";
    }
    
    // Count braces
    $open_brace = substr_count($content, '{');
    $close_brace = substr_count($content, '}');
    echo "<p>Opening braces: $open_brace</p>";
    echo "<p>Closing braces: $close_brace</p>";
    if ($open_brace != $close_brace) {
        echo "<p style='color: red; font-weight: bold;'>⚠ Braces mismatch!</p>";
    }
    
    // Show last 30 lines
    echo "<h4>Last 30 lines of index.php:</h4>";
    echo "<pre style='background: #fff; padding: 10px; overflow: auto; max-height: 300px; font-size: 11px;'>";
    $lines = explode("\n", $content);
    $last_lines = array_slice($lines, -30);
    $start_line = count($lines) - 30;
    foreach ($last_lines as $i => $line) {
        $line_num = $start_line + $i + 1;
        echo sprintf("%3d: %s\n", $line_num, htmlspecialchars($line ?? ''));
    }
    echo "</pre>";
    echo "</div>";
}
?>
