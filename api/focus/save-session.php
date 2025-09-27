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

$duration = $input['duration'] ?? 0;
$sessionType = $input['session_type'] ?? 'focus';
$subject = $input['subject'] ?? '';
$distractions = $input['distractions'] ?? 0;
$completed = $input['completed'] ?? false;

try {
    // Save focus session
    $stmt = $pdo->prepare("
        INSERT INTO focus_sessions (user_id, session_type, duration, subject, distractions_count, completed, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$_SESSION['user_id'], $sessionType, $duration, $subject, $distractions, $completed ? 1 : 0]);

    // Award points based on session
    require_once '../../includes/gamification.php';
    $points = 0;
    if ($sessionType === 'focus') {
        $points = min(floor($duration / 60) * 2, 30); // 2 points per minute, max 30
        if ($completed) $points += 10; // Bonus for completion
    }
    
    if ($points > 0) {
        awardPoints($_SESSION['user_id'], $points, 'focus_session', "Completed $sessionType session");
    }

    // Update user stats
    $stmt = $pdo->prepare("
        UPDATE user_stats 
        SET total_focus_time = total_focus_time + ?,
            focus_sessions = focus_sessions + 1
        WHERE user_id = ?
    ");
    $stmt->execute([$duration, $_SESSION['user_id']]);

    echo json_encode([
        'success' => true,
        'points_awarded' => $points,
        'message' => 'Focus session saved successfully'
    ]);

} catch (Exception $e) {
    error_log('Error saving focus session: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>