<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$quizId = $_GET['id'] ?? null;

if (!$quizId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Quiz ID required']);
    exit;
}

try {
    // Get quiz with questions
    $stmt = $pdo->prepare("
        SELECT q.*, COUNT(qq.id) as question_count
        FROM quizzes q
        LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id
        WHERE q.id = ? AND (q.user_id = ? OR q.is_public = 1)
        GROUP BY q.id
    ");
    $stmt->execute([$quizId, $_SESSION['user_id']]);
    $quiz = $stmt->fetch();

    if (!$quiz) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Quiz not found']);
        exit;
    }

    // Get questions with options
    $stmt = $pdo->prepare("
        SELECT qq.*, qo.option_text, qo.is_correct, qo.id as option_id
        FROM quiz_questions qq
        LEFT JOIN quiz_options qo ON qq.id = qo.question_id
        WHERE qq.quiz_id = ?
        ORDER BY qq.order_number, qo.order_number
    ");
    $stmt->execute([$quizId]);
    $results = $stmt->fetchAll();

    // Group questions with their options
    $questions = [];
    foreach ($results as $row) {
        $questionId = $row['id'];
        if (!isset($questions[$questionId])) {
            $questions[$questionId] = [
                'id' => $row['id'],
                'question_text' => $row['question_text'],
                'question_type' => $row['question_type'],
                'points' => $row['points'],
                'order_number' => $row['order_number'],
                'explanation' => $row['explanation'],
                'options' => []
            ];
        }
        
        if ($row['option_id']) {
            $questions[$questionId]['options'][] = [
                'id' => $row['option_id'],
                'option_text' => $row['option_text'],
                'is_correct' => $row['is_correct']
            ];
        }
    }

    // Convert to indexed array and sort by order
    $questions = array_values($questions);
    usort($questions, function($a, $b) {
        return $a['order_number'] - $b['order_number'];
    });

    echo json_encode([
        'success' => true,
        'quiz' => $quiz,
        'questions' => $questions
    ]);

} catch (Exception $e) {
    error_log('Error getting quiz: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>