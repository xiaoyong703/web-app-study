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

$name = trim($input['name'] ?? '');
$description = trim($input['description'] ?? '');
$subject = $input['subject'] ?? '';
$isPublic = $input['is_public'] ?? false;
$maxMembers = $input['max_members'] ?? 50;

if (!$name) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Group name required']);
    exit;
}

try {
    // Generate invite code
    $inviteCode = strtoupper(substr(uniqid(), -8));

    // Create group
    $stmt = $pdo->prepare("
        INSERT INTO study_groups (name, description, subject, is_public, max_members, invite_code, created_by, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$name, $description, $subject, $isPublic ? 1 : 0, $maxMembers, $inviteCode, $_SESSION['user_id']]);

    $groupId = $pdo->lastInsertId();

    // Add creator as admin member
    $stmt = $pdo->prepare("
        INSERT INTO study_group_members (group_id, user_id, role, status, joined_at)
        VALUES (?, ?, 'admin', 'active', NOW())
    ");
    $stmt->execute([$groupId, $_SESSION['user_id']]);

    // Award points for creating group
    require_once '../../includes/gamification.php';
    awardPoints($_SESSION['user_id'], 50, 'group_create', 'Created study group');

    echo json_encode([
        'success' => true,
        'group_id' => $groupId,
        'invite_code' => $inviteCode,
        'message' => 'Study group created successfully'
    ]);

} catch (Exception $e) {
    error_log('Error creating group: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>