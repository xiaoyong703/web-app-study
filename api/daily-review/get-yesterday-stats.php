<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$date = $_GET['date'] ?? date('Y-m-d', strtotime('-1 day'));

try {
    // Get yesterday's study sessions
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as session_count,
            SUM(duration) as total_time,
            subject,
            SUM(duration) as subject_time
        FROM study_sessions 
        WHERE user_id = ? AND DATE(created_at) = ?
        GROUP BY subject
        ORDER BY subject_time DESC
    ");
    $stmt->execute([$_SESSION['user_id'], $date]);
    $sessions = $stmt->fetchAll();

    // Get completed todos
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as completed_todos
        FROM todos 
        WHERE user_id = ? AND DATE(completed_at) = ? AND completed = 1
    ");
    $stmt->execute([$_SESSION['user_id'], $date]);
    $todos = $stmt->fetch();

    // Get focus sessions
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as focus_sessions,
            SUM(duration) as focus_time,
            AVG(distractions_count) as avg_distractions
        FROM focus_sessions 
        WHERE user_id = ? AND DATE(created_at) = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $date]);
    $focus = $stmt->fetch();

    // Get achievements earned
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as achievements_earned
        FROM user_achievements 
        WHERE user_id = ? AND DATE(unlocked_at) = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $date]);
    $achievements = $stmt->fetch();

    $totalTime = array_sum(array_column($sessions, 'total_time'));

    echo json_encode([
        'success' => true,
        'date' => $date,
        'summary' => [
            'total_study_time' => $totalTime,
            'session_count' => array_sum(array_column($sessions, 'session_count')),
            'completed_todos' => $todos['completed_todos'] ?? 0,
            'focus_sessions' => $focus['focus_sessions'] ?? 0,
            'focus_time' => $focus['focus_time'] ?? 0,
            'avg_distractions' => round($focus['avg_distractions'] ?? 0, 1),
            'achievements_earned' => $achievements['achievements_earned'] ?? 0
        ],
        'sessions_by_subject' => $sessions
    ]);

} catch (Exception $e) {
    error_log('Error getting yesterday stats: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>