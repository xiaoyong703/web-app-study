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

if (!isset($input['task']) || empty(trim($input['task']))) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Task is required']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? 'guest_' . uniqid();
$task = sanitizeInput($input['task']);

try {
    $success = addTodoItem($user_id, $task, $pdo);
    
    if ($success) {
        // Get the newly created todo item
        $stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? AND task = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$user_id, $task]);
        $todo = $stmt->fetch();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Todo added successfully',
            'todo' => $todo
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to add todo']);
    }
} catch (Exception $e) {
    error_log("Error in add-todo.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>