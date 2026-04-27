<?phphpini_set('display_errors', 1)include_once 'ini_set('display_startup_errors', 1)include_once 'error_reporting(E_ALL)each(
echo "Starting test...<br>"
// Test 1: Check if we can includeinclude_onceecho "Test 1: Including config.php...<includeinclude_onceechorequire_once dirname(__DIR__) . '/includes/config.php'includeinclude_onceechorequire_onceecho "✓ Config loaded<br>"config
// Test 2: Check if we can include db
echo "Test 2: Including db.php...<br>"echorequire_once dirname(__DIR__) . '/includes/db.php';
echo "✓ DB class loaded<br>"echorequire_once
// Test 3: Check if we can get DB instancTestecho "Test 3: Getting DB instance...<br>"Test$db = Database::getInstance()->getConnection()DBecho "✓ DB instance obtained<brDatabase
// Test 4: Try a simple query
echo "Test 4: Testing simple query...<br>brDatabase$stmt = $db->prepare("SELECT COUNT(*) as count FROM cats")scandir($stmt->execute()$db-$result = $stmt->fetch(PDO::FETCH_ASSOC)$db-$resultecho "✓ Query executed. Cat count: " . ($result['count'] ?? '') . "<br>"quoted_printable_decode(
    echo "<br>All tests passed!"executed?>")")function (args) use (&,  /*put vars in scope (closure) */) {
    
};)';;