<?php
session_start();

echo "<h1>YPT Study App - Login Debug</h1>";

// Test database connection
echo "<h2>1. Database Connection Test</h2>";
try {
    require_once '../../config/database.php';
    echo "✅ Database connection successful<br>";
    
    // Test if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists<br>";
        
        // Count users
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch()['count'];
        echo "✅ Users table has {$count} records<br>";
    } else {
        echo "❌ Users table does not exist<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test session
echo "<h2>2. Session Test</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data: <pre>" . print_r($_SESSION, true) . "</pre>";

// Test POST data if this is a POST request
echo "<h2>3. Request Test</h2>";
echo "Request method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST data: <pre>" . print_r($_POST, true) . "</pre>";
}

// Test error log
echo "<h2>4. Error Log Test</h2>";
if (function_exists('error_log')) {
    echo "✅ Error logging is available<br>";
    error_log("YPT Study Debug Test - " . date('Y-m-d H:i:s'));
} else {
    echo "❌ Error logging not available<br>";
}

// Test file permissions
echo "<h2>5. File Permissions Test</h2>";
$loginFile = '../api/auth/login.php';
if (file_exists($loginFile)) {
    echo "✅ Login API file exists<br>";
    if (is_readable($loginFile)) {
        echo "✅ Login API file is readable<br>";
    } else {
        echo "❌ Login API file is not readable<br>";
    }
} else {
    echo "❌ Login API file does not exist<br>";
}

echo "<hr>";
echo "<a href='login.php'>← Back to Login</a>";
?>