<?php
header('Content-Type: application/json');
session_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

$user_id = $_SESSION['user_id'] ?? 'guest_' . uniqid();
$period = $_GET['period'] ?? 'week';

try {
    $whereClause = '';
    switch ($period) {
        case 'today':
            $whereClause = 'DATE(created_at) = CURDATE()';
            break;
        case 'week':
            $whereClause = 'WEEK(created_at) = WEEK(NOW())';
            break;
        case 'month':
            $whereClause = 'MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())';
            break;
        default:
            $whereClause = 'WEEK(created_at) = WEEK(NOW())';
    }
    
    // Get detailed statistics
    $stmt = $pdo->prepare("SELECT 
        SUM(duration) as total_time,
        COUNT(*) as total_sessions,
        AVG(duration) as avg_session,
        subject,
        COUNT(subject) as subject_sessions
        FROM study_sessions 
        WHERE user_id = ? AND {$whereClause}
        GROUP BY subject
        ORDER BY subject_sessions DESC");
    $stmt->execute([$user_id]);
    $subjectStats = $stmt->fetchAll();
    
    // Get totals
    $stmt = $pdo->prepare("SELECT 
        SUM(duration) as total_time,
        COUNT(*) as total_sessions,
        AVG(duration) as avg_session
        FROM study_sessions 
        WHERE user_id = ? AND {$whereClause}");
    $stmt->execute([$user_id]);
    $totals = $stmt->fetch();
    
    $topSubject = $subjectStats[0]['subject'] ?? null;
    
    echo json_encode([
        'success' => true,
        'data' => [
            'total_time' => $totals['total_time'] ?? 0,
            'total_sessions' => $totals['total_sessions'] ?? 0,
            'avg_session' => $totals['avg_session'] ?? 0,
            'top_subject' => $topSubject,
            'subjects' => $subjectStats
        ]
    ]);
} catch (Exception $e) {
    error_log("Error in get-detailed-stats.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>