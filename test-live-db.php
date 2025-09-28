<?php
// Simple diagnostic page for your live cPanel server
// Upload this to your server root and visit it to test database connection

echo "<h1>YPT Study App - Live Server Diagnostics</h1>";

// Test 1: PHP Version and Extensions
echo "<h2>1. PHP Environment</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Available PDO Drivers: " . implode(', ', PDO::getAvailableDrivers()) . "<br>";
echo "Session support: " . (function_exists('session_start') ? '✓' : '✗') . "<br>";
echo "Password hashing: " . (function_exists('password_hash') ? '✓' : '✗') . "<br>";

// Test 2: Database Connection
echo "<h2>2. Database Connection Test</h2>";
try {
    require_once 'config/database.php';
    echo "✅ Database connection successful!<br>";
    
    // Test users table
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists<br>";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch()['count'];
        echo "✅ Users table has {$count} users<br>";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE users");
        echo "<br>Users table structure:<br>";
        while ($row = $stmt->fetch()) {
            echo "- {$row['Field']} ({$row['Type']})<br>";
        }
    } else {
        echo "❌ Users table missing<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}

// Test 3: File Permissions
echo "<h2>3. File Permissions</h2>";
$files = [
    'api/auth/login.php',
    'api/auth/register.php', 
    'pages/login.php',
    'config/database.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ {$file} exists and is " . (is_readable($file) ? 'readable' : 'not readable') . "<br>";
    } else {
        echo "❌ {$file} missing<br>";
    }
}

// Test 4: Session Test
echo "<h2>4. Session Test</h2>";
session_start();
$_SESSION['test'] = 'working';
echo "Session ID: " . session_id() . "<br>";
echo "Session test: " . ($_SESSION['test'] ?? 'failed') . "<br>";

// Test 5: Error Log Test
echo "<h2>5. Error Logging Test</h2>";
$testMessage = "YPT Study diagnostic test - " . date('Y-m-d H:i:s');
if (error_log($testMessage)) {
    echo "✅ Error logging working<br>";
} else {
    echo "❌ Error logging failed<br>";
}

echo "<hr>";
echo "<p><strong>Instructions:</strong></p>";
echo "<ol>";
echo "<li>Upload this file to your cPanel website root</li>";
echo "<li>Visit yourdomain.com/test-live-db.php</li>"; 
echo "<li>Share the results to help debug the login issue</li>";
echo "<li>Delete this file after testing for security</li>";
echo "</ol>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 40px; }
h1 { color: #2196F3; }
h2 { color: #666; border-bottom: 1px solid #eee; padding-bottom: 5px; }
.success { color: green; }
.error { color: red; }
</style>