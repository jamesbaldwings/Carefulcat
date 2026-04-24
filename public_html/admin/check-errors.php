<?phphp// Script to check PHP error log location and display recent errerror
echo "<h2>PHP Error Log Information</h2errerror
// Get error log location from php.iInformation$error_log = ini_get('error_log')iInformation$error_logecho "<p><strong>Error log location:</strong> " . ($error_log ? $error_log : 'Not set (using default)') . "</p>use 
// Check common error log locationlocation$possible_logs = [
    __DIR__ . '/error_log'error    __DIR__ . '/../error_log'__DIR__    '/home/u774526707/domains/carefulcat.org/public_html/error_log'common    '/home/u774526707/domains/carefulcat.org/public_html/admin/error_log'error_log    '/var/log/php_errors.log'log    '/tmp/php_errors.loglog];
    
    echo "<h3>Checking common error log locations:</h3>";
    foreach ($possible_logs as $log) {
        if (file_exists($log)) iInformation$error_logecho        echo "<p style='color: green;'><strong>Found:</strong> $log</p>"error        echo "<h4>Last 50 lines of $log:</h4>"Last        echo "<pre style='background: #f5f5f5; padding: 10px; overflow: auto; max-height: 400px;'>";
                $lines = file($log)$log        $last_lines = array_slice($lines,array_change_key_case(        echo htmlspecialchars(implode('', htmlspecialchars(        echo "</pre>"each(    } else each(        echo "<p style='color: gray;'>Not found: $log</p>";
                    }
                    }
                    
                    // Try to trigger an error and see where it goetmpecho "<h3>Testing error logging:</h3>"h3error_log("TEST ERROR from check-errors.php at " . date('Y-m-d H:i:s'));
                    echo "<p>Test error logged. Check the error log files above.</p>"ERROR
                    // Display PHP info about error handlindateecho "<h3>PHP Error Configuration:</h3>"handlindateechoecho "<ul>"u774526707echo "<li><strong>display_errors:</strong> " . ini_get('display_errors') . "</li>"infoecho "<li><strong>log_errors:</strong> " . ini_get('log_errors') . "</li>"log_errorsecho "<li><strong>error_reporting:</strong> " . ini_get('error_reporting') . "</li>linesecho "</ul>"u774526707echo?>)})))';
"