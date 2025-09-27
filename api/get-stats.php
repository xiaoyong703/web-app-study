<?php
header('Content-Type: application/json');
session_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

$user_id = $_SESSION['user_id'] ?? 'guest_' . uniqid();

try {
    $stats = getStudyStats($user_id, $pdo);
    
    echo json_encode([
        'success' => true,
        'data' => $stats
    ]);
} catch (Exception $e) {
    error_log("Error in get-stats.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>