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

$points = $input['points'] ?? 0;
$type = $input['type'] ?? '';
$description = $input['description'] ?? '';

if ($points <= 0 || !$type) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // Award points using the gamification helper
    require_once '../../includes/gamification.php';
    $success = awardPoints($_SESSION['user_id'], $points, $type, $description);
    
    if (!$success) {
        throw new Exception('Failed to award points');
    }

    // Check for level up
    $levelUpResult = checkLevelUp($_SESSION['user_id']);
    
    // Update streak
    $newStreak = updateStreak($_SESSION['user_id']);

    echo json_encode([
        'success' => true,
        'points_awarded' => $points,
        'level_up' => $levelUpResult,
        'current_streak' => $newStreak,
        'message' => 'Points awarded successfully'
    ]);

} catch (Exception $e) {
    error_log('Error awarding points: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>