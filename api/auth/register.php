<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$gradeLevel = $_POST['grade_level'] ?? null;
$terms = isset($_POST['terms']);
$newsletter = isset($_POST['newsletter']);

// Validation
if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
    header('Location: ../../pages/register.php?error=required');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../../pages/register.php?error=invalid_email');
    exit;
}

if ($password !== $confirmPassword) {
    header('Location: ../../pages/register.php?error=passwords');
    exit;
}

// Password strength validation
if (strlen($password) < 8 || 
    !preg_match('/[A-Z]/', $password) || 
    !preg_match('/[a-z]/', $password) || 
    !preg_match('/\d/', $password) || 
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    header('Location: ../../pages/register.php?error=weak_password');
    exit;
}

if (!$terms) {
    header('Location: ../../pages/register.php?error=terms');
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header('Location: ../../pages/register.php?error=email_exists');
        exit;
    }

    // Create user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (first_name, last_name, email, password, grade_level, newsletter_subscription, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())
    ");
    $stmt->execute([$firstName, $lastName, $email, $hashedPassword, $gradeLevel, $newsletter ? 1 : 0]);

    $userId = $pdo->lastInsertId();

    // Initialize user stats
    $stmt = $pdo->prepare("
        INSERT INTO user_stats (user_id, total_study_time, sessions_count, points, level, streak_days, created_at) 
        VALUES (?, 0, 0, 0, 1, 0, NOW())
    ");
    $stmt->execute([$userId]);

    // Send welcome email (if newsletter enabled)
    if ($newsletter) {
        // Add to newsletter queue or send welcome email
        error_log("Welcome email needed for: $email");
    }

    // Set session to log them in automatically
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $firstName . ' ' . $lastName;

    header('Location: ../../index.php');
    exit;

} catch (Exception $e) {
    error_log('Registration error: ' . $e->getMessage());
    header('Location: ../../pages/register.php?error=system');
    exit;
}
?>