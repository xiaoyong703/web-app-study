<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$groupId = $input['group_id'] ?? null;
$message = trim($input['message'] ?? '');

if (!$groupId || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Group ID and message required']);
    exit;
}

try {
    // Check if user is member of the group
    $stmt = $pdo->prepare("
        SELECT id FROM study_group_members 
        WHERE group_id = ? AND user_id = ? AND status = 'active'
    ");
    $stmt->execute([$groupId, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Not a member of this group']);
        exit;
    }

    // Insert message
    $stmt = $pdo->prepare("
        INSERT INTO study_group_messages (group_id, user_id, message, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$groupId, $_SESSION['user_id'], $message]);

    $messageId = $pdo->lastInsertId();

    // Get the inserted message with user info
    $stmt = $pdo->prepare("
        SELECT sgm.*, u.first_name, u.last_name
        FROM study_group_messages sgm
        JOIN users u ON sgm.user_id = u.id
        WHERE sgm.id = ?
    ");
    $stmt->execute([$messageId]);
    $newMessage = $stmt->fetch();

    // Award points for group participation
    require_once '../../includes/gamification.php';
    awardPoints($_SESSION['user_id'], 2, 'group_message', 'Sent group message');

    echo json_encode([
        'success' => true,
        'message' => $newMessage
    ]);

} catch (Exception $e) {
    error_log('Error sending message: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>