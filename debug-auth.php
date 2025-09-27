<?php
session_start();

echo "<h1>Debug Information</h1>";
echo "<h2>Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>GET Parameters:</h2>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

echo "<h2>Authentication Check:</h2>";
$isAuthenticated = false;
$user = null;
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    echo "Session user_id found: " . $_SESSION['user_id'] . "<br>";
    // Check if it's not a guest session
    $isAuthenticated = (strpos($_SESSION['user_id'], 'guest_') !== 0);
    echo "Is authenticated: " . ($isAuthenticated ? 'YES' : 'NO') . "<br>";
    
    if ($isAuthenticated) {
        echo "Attempting to load user data...<br>";
        try {
            require_once 'config/database.php';
            $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            echo "User data loaded: " . ($user ? 'YES' : 'NO') . "<br>";
            if ($user) {
                echo "User: " . $user['first_name'] . " " . $user['last_name'] . "<br>";
            }
        } catch(Exception $e) {
            echo "Database error: " . $e->getMessage() . "<br>";
        }
    }
} else {
    echo "No session user_id found<br>";
}

echo "<h2>Navigation Test:</h2>";
echo '<a href="pages/login.php" style="background: blue; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">Test Login Link</a><br><br>';

echo "<h2>File Existence Check:</h2>";
echo "pages/login.php exists: " . (file_exists('pages/login.php') ? 'YES' : 'NO') . "<br>";
echo "home.php exists: " . (file_exists('home.php') ? 'YES' : 'NO') . "<br>";

echo "<h2>Current Directory:</h2>";
echo "Current working directory: " . getcwd() . "<br>";

echo "<h2>Directory Listing:</h2>";
echo "<pre>";
print_r(scandir('.'));
echo "</pre>";
?>