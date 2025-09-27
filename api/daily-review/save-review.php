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

$yesterdayReflection = $input['yesterday_reflection'] ?? '';
$mood = $input['mood'] ?? '';
$moodNotes = $input['mood_notes'] ?? '';
$tomorrowGoals = $input['tomorrow_goals'] ?? [];
$insights = $input['insights'] ?? '';
$timeSpent = $input['time_spent'] ?? 0;

try {
    // Save daily review
    $stmt = $pdo->prepare("
        INSERT INTO daily_reviews (user_id, review_date, yesterday_reflection, mood, mood_notes, tomorrow_goals, insights, time_spent, created_at)
        VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
        yesterday_reflection = VALUES(yesterday_reflection),
        mood = VALUES(mood),
        mood_notes = VALUES(mood_notes),
        tomorrow_goals = VALUES(tomorrow_goals),
        insights = VALUES(insights),
        time_spent = VALUES(time_spent),
        updated_at = NOW()
    ");
    
    $goalsJson = json_encode($tomorrowGoals);
    $stmt->execute([
        $_SESSION['user_id'],
        $yesterdayReflection,
        $mood,
        $moodNotes,
        $goalsJson,
        $insights,
        $timeSpent
    ]);

    // Award points for completing daily review
    require_once '../../includes/gamification.php';
    awardPoints($_SESSION['user_id'], 25, 'daily_review', 'Completed daily review');

    // Update streak
    $stmt = $pdo->prepare("
        UPDATE user_stats 
        SET streak_days = CASE 
            WHEN last_review_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN streak_days + 1
            WHEN last_review_date = CURDATE() THEN streak_days
            ELSE 1
        END,
        last_review_date = CURDATE()
        WHERE user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);

    echo json_encode([
        'success' => true,
        'message' => 'Daily review saved successfully',
        'points_awarded' => 25
    ]);

} catch (Exception $e) {
    error_log('Error saving daily review: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>