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

$quizId = $input['quiz_id'] ?? null;
$answers = $input['answers'] ?? [];
$timeSpent = $input['time_spent'] ?? 0;

if (!$quizId || empty($answers)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Quiz ID and answers required']);
    exit;
}

try {
    // Get quiz questions with correct answers
    $stmt = $pdo->prepare("
        SELECT qq.id, qq.points, qo.id as option_id, qo.is_correct
        FROM quiz_questions qq
        JOIN quiz_options qo ON qq.id = qo.question_id
        WHERE qq.quiz_id = ?
        ORDER BY qq.id, qo.id
    ");
    $stmt->execute([$quizId]);
    $questionData = $stmt->fetchAll();

    // Process answers and calculate score
    $correctAnswers = [];
    $questionPoints = [];
    
    foreach ($questionData as $data) {
        $questionId = $data['id'];
        if (!isset($questionPoints[$questionId])) {
            $questionPoints[$questionId] = $data['points'];
        }
        if ($data['is_correct']) {
            if (!isset($correctAnswers[$questionId])) {
                $correctAnswers[$questionId] = [];
            }
            $correctAnswers[$questionId][] = $data['option_id'];
        }
    }

    $totalScore = 0;
    $maxScore = array_sum($questionPoints);
    $correctCount = 0;
    $results = [];

    foreach ($answers as $questionId => $selectedOptions) {
        $questionId = (int)$questionId;
        $selectedOptions = is_array($selectedOptions) ? $selectedOptions : [$selectedOptions];
        $selectedOptions = array_map('intval', $selectedOptions);
        
        $correctForQuestion = $correctAnswers[$questionId] ?? [];
        $isCorrect = (sort($selectedOptions) == sort($correctForQuestion));
        
        if ($isCorrect) {
            $totalScore += $questionPoints[$questionId];
            $correctCount++;
        }
        
        $results[$questionId] = [
            'selected' => $selectedOptions,
            'correct' => $correctForQuestion,
            'is_correct' => $isCorrect,
            'points_earned' => $isCorrect ? $questionPoints[$questionId] : 0
        ];
    }

    $percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 1) : 0;

    // Save quiz result
    $stmt = $pdo->prepare("
        INSERT INTO quiz_results (user_id, quiz_id, score, max_score, percentage, time_spent, answers, completed_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $_SESSION['user_id'], 
        $quizId, 
        $totalScore, 
        $maxScore, 
        $percentage, 
        $timeSpent,
        json_encode($results)
    ]);

    // Award points based on performance
    require_once '../../includes/gamification.php';
    $bonusPoints = 0;
    if ($percentage >= 90) $bonusPoints = 50;
    elseif ($percentage >= 80) $bonusPoints = 30;
    elseif ($percentage >= 70) $bonusPoints = 20;
    elseif ($percentage >= 60) $bonusPoints = 10;

    if ($bonusPoints > 0) {
        awardPoints($_SESSION['user_id'], $bonusPoints, 'quiz_complete', "Completed quiz with {$percentage}%");
    }

    echo json_encode([
        'success' => true,
        'score' => $totalScore,
        'max_score' => $maxScore,
        'percentage' => $percentage,
        'correct_count' => $correctCount,
        'total_questions' => count($questionPoints),
        'results' => $results,
        'bonus_points' => $bonusPoints
    ]);

} catch (Exception $e) {
    error_log('Error submitting quiz: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>