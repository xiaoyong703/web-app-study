<?php
session_start();

// Debug logging
error_log("Login API called - Method: " . $_SERVER['REQUEST_METHOD'] . " - Time: " . date('Y-m-d H:i:s'));
error_log("POST data: " . json_encode($_POST));

// Don't set JSON header since we're doing redirects
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Login error: Invalid request method");
    header('Location: ../../pages/login.php?error=method');
    exit;
}

// Include database config with error handling
try {
    require_once '../../config/database.php';
} catch (Exception $e) {
    error_log('Database connection error: ' . $e->getMessage());
    header('Location: ../../pages/login.php?error=database');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

error_log("Login attempt for email: " . $email);

// Validation
if (empty($email) || empty($password)) {
    error_log("Login error: Empty email or password");
    header('Location: ../../pages/login.php?error=required');
    exit;
}

try {
    // Check user credentials
    error_log("Querying database for user: " . $email);
    $stmt = $pdo->prepare("SELECT id, email, password, first_name, last_name, status FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        error_log("Login error: User not found for email: " . $email);
        header('Location: ../../pages/login.php?error=invalid');
        exit;
    }

    error_log("User found, verifying password for user ID: " . $user['id']);
    if (!password_verify($password, $user['password'])) {
        error_log("Login error: Invalid password for user: " . $email);
        header('Location: ../../pages/login.php?error=invalid');
        exit;
    }

    if ($user['status'] !== 'active') {
        error_log("Login error: Inactive user: " . $email . " (status: " . $user['status'] . ")");
        header('Location: ../../pages/login.php?error=inactive');
        exit;
    }

    error_log("Login successful for user: " . $email);

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

    // Set remember me cookie
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 days
        
        // Store token in database
        $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->execute([$token, $user['id']]);
    }

    // Update last login
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->execute([$user['id']]);

    header('Location: ../../index.php');
    exit;

} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    header('Location: ../../pages/login.php?error=system');
    exit;
}
?>