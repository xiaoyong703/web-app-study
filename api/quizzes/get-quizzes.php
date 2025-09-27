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
    // Get user's quizzes and public quizzes
    $stmt = $pdo->prepare("
        SELECT q.*,
               COUNT(qq.id) as question_count,
               AVG(qr.score) as avg_score,
               COUNT(qr.id) as attempt_count,
               MAX(qr.completed_at) as last_attempt
        FROM quizzes q
        LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id
        LEFT JOIN quiz_results qr ON q.id = qr.quiz_id AND qr.user_id = ?
        WHERE q.user_id = ? OR q.is_public = 1
        GROUP BY q.id
        ORDER BY q.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
    $quizzes = $stmt->fetchAll();

    // Get user stats
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(DISTINCT qr.quiz_id) as quizzes_taken,
            AVG(qr.score) as avg_score,
            MAX(qr.score) as best_score,
            COUNT(qr.id) as total_attempts
        FROM quiz_results qr
        WHERE qr.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $stats = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'quizzes' => $quizzes,
        'stats' => $stats ?: [
            'quizzes_taken' => 0,
            'avg_score' => 0,
            'best_score' => 0,
            'total_attempts' => 0
        ]
    ]);

} catch (Exception $e) {
    error_log('Error getting quizzes: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>