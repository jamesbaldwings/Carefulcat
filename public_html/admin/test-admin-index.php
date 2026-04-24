<?php
// Test script to capture errors from admin/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<h2>Testing Admin Index</h2>";
echo "<p>This script will attempt to include admin/index.php and display any errors.</p>";
echo "<hr>";

// Capture output and errors
ob_start();

try {
    // Change to admin directory context
    chdir(__DIR__);
    
    echo "<h3>Including index.php...</h3>";
    
    // Include the index.php file
    include(__DIR__ . '/index.php');
    
} catch (Exception $e) {
    echo "<div style='background: #ffcccc; padding: 10px; margin: 10px 0;'>";
    echo "<h3>Exception Caught:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
} catch (Error $e) {
    echo "<div style='background: #ffcccc; padding: 10px; margin: 10px 0;'>";
    echo "<h3>Error Caught:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

$output = ob_get_clean();

echo "<h3>Output:</h3>";
echo "<div style='background: #f0f0f0; padding: 10px;'>";
echo $output;
echo "</div>";

// Check for last error
$last_error = error_get_last();
if ($last_error) {
    echo "<div style='background: #ffeeee; padding: 10px; margin: 10px 0;'>";
    echo "<h3>Last PHP Error:</h3>";
    echo "<pre>" . htmlspecialchars(print_r($last_error, true)) . "</pre>";
    echo "</div>";
}
?>
