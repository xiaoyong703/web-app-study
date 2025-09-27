<?php
header('Content-Type: application/json');
session_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['subject']) || !isset($input['duration'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? 'guest_' . uniqid();
$subject = sanitizeInput($input['subject']);
$duration = (int)$input['duration'];

if ($duration <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid duration']);
    exit;
}

try {
    $success = saveStudySession($user_id, $subject, $duration, $pdo);
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Session saved successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to save session']);
    }
} catch (Exception $e) {
    error_log("Error in save-session.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>