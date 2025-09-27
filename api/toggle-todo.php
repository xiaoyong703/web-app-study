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

if (!isset($input['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todo ID is required']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? 'guest_' . uniqid();
$todo_id = (int)$input['id'];

try {
    $success = toggleTodoItem($todo_id, $user_id, $pdo);
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Todo updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update todo']);
    }
} catch (Exception $e) {
    error_log("Error in toggle-todo.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>