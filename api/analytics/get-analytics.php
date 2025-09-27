<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$period = $_GET['period'] ?? 'week';
$subject = $_GET['subject'] ?? '';

try {
    $dateCondition = '';
    $dateParams = [$_SESSION['user_id']];
    
    switch ($period) {
        case 'day':
            $dateCondition = 'DATE(created_at) = CURDATE()';
            break;
        case 'week':
            $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
            break;
        case 'month':
            $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
            break;
        case 'year':
            $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 365 DAY)';
            break;
        default:
            $dateCondition = 'created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
    }

    if ($subject) {
        $dateCondition .= ' AND subject = ?';
        $dateParams[] = $subject;
    }

    // Study time by day
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) as date, 
               SUM(duration) as total_time,
               COUNT(*) as session_count
        FROM study_sessions 
        WHERE user_id = ? AND $dateCondition
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $stmt->execute($dateParams);
    $dailyStats = $stmt->fetchAll();

    // Study time by subject
    $stmt = $pdo->prepare("
        SELECT subject, 
               SUM(duration) as total_time,
               COUNT(*) as session_count,
               AVG(duration) as avg_session
        FROM study_sessions 
        WHERE user_id = ? AND $dateCondition
        GROUP BY subject
        ORDER BY total_time DESC
    ");
    $stmt->execute($dateParams);
    $subjectStats = $stmt->fetchAll();

    // Focus sessions
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) as date,
               COUNT(*) as focus_sessions,
               SUM(duration) as focus_time,
               AVG(distractions_count) as avg_distractions
        FROM focus_sessions 
        WHERE user_id = ? AND $dateCondition
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $stmt->execute($dateParams);
    $focusStats = $stmt->fetchAll();

    // Quiz performance
    $stmt = $pdo->prepare("
        SELECT DATE(completed_at) as date,
               COUNT(*) as quiz_count,
               AVG(score) as avg_score,
               MAX(score) as best_score
        FROM quiz_results 
        WHERE user_id = ? AND completed_at IS NOT NULL AND $dateCondition
        GROUP BY DATE(completed_at)
        ORDER BY date ASC
    ");
    $dateCondition = str_replace('created_at', 'completed_at', $dateCondition);
    $stmt->execute($dateParams);
    $quizStats = $stmt->fetchAll();

    // Overall summary
    $stmt = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) THEN duration ELSE 0 END) as today_time,
            SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN duration ELSE 0 END) as week_time,
            SUM(duration) as total_time,
            COUNT(*) as total_sessions,
            AVG(duration) as avg_session_length
        FROM study_sessions 
        WHERE user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $summary = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'period' => $period,
        'daily_stats' => $dailyStats,
        'subject_stats' => $subjectStats,
        'focus_stats' => $focusStats,
        'quiz_stats' => $quizStats,
        'summary' => $summary
    ]);

} catch (Exception $e) {
    error_log('Error getting analytics: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>