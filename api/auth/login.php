<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validation
if (empty($email) || empty($password)) {
    header('Location: ../../pages/login.php?error=required');
    exit;
}

try {
    // Check user credentials
    $stmt = $pdo->prepare("SELECT id, email, password, first_name, last_name, status FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        header('Location: ../../pages/login.php?error=invalid');
        exit;
    }

    if ($user['status'] !== 'active') {
        header('Location: ../../pages/login.php?error=inactive');
        exit;
    }

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