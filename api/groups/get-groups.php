<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    // Get user's groups
    $stmt = $pdo->prepare("
        SELECT sg.*,
               COUNT(DISTINCT sgm.user_id) as member_count,
               COUNT(DISTINCT sgme.id) as message_count,
               MAX(sgme.created_at) as last_activity
        FROM study_groups sg
        JOIN study_group_members sgm ON sg.id = sgm.group_id
        LEFT JOIN study_group_messages sgme ON sg.id = sgme.group_id
        WHERE sgm.user_id = ? AND sgm.status = 'active'
        GROUP BY sg.id
        ORDER BY last_activity DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $userGroups = $stmt->fetchAll();

    // Get available public groups (not joined)
    $stmt = $pdo->prepare("
        SELECT sg.*,
               COUNT(DISTINCT sgm.user_id) as member_count,
               u.first_name as creator_name
        FROM study_groups sg
        LEFT JOIN study_group_members sgm ON sg.id = sgm.group_id AND sgm.status = 'active'
        LEFT JOIN users u ON sg.created_by = u.id
        WHERE sg.is_public = 1 
        AND sg.id NOT IN (
            SELECT group_id FROM study_group_members 
            WHERE user_id = ? AND status = 'active'
        )
        GROUP BY sg.id
        ORDER BY member_count DESC, sg.created_at DESC
        LIMIT 20
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $availableGroups = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'user_groups' => $userGroups,
        'available_groups' => $availableGroups
    ]);

} catch (Exception $e) {
    error_log('Error getting groups: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>