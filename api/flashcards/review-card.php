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

$cardId = $input['card_id'] ?? null;
$difficulty = $input['difficulty'] ?? 'medium';

if (!$cardId || !in_array($difficulty, ['easy', 'medium', 'hard', 'again'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // Get existing review data
    $stmt = $pdo->prepare("
        SELECT * FROM user_reviews 
        WHERE user_id = ? AND card_id = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $cardId]);
    $review = $stmt->fetch();

    // Calculate spaced repetition values
    $easeFactor = $review['ease_factor'] ?? 2.5;
    $interval = $review['interval_days'] ?? 1;
    $repetitions = $review['repetitions'] ?? 0;

    // Adjust based on difficulty
    switch ($difficulty) {
        case 'again':
            $easeFactor = max(1.3, $easeFactor - 0.2);
            $interval = 1;
            $repetitions = 0;
            break;
        case 'hard':
            $easeFactor = max(1.3, $easeFactor - 0.15);
            $interval = max(1, floor($interval * 1.2));
            $repetitions++;
            break;
        case 'medium':
            $interval = $repetitions == 0 ? 1 : ($repetitions == 1 ? 6 : floor($interval * $easeFactor));
            $repetitions++;
            break;
        case 'easy':
            $easeFactor = min(2.5, $easeFactor + 0.1);
            $interval = $repetitions == 0 ? 4 : floor($interval * $easeFactor * 1.3);
            $repetitions++;
            break;
    }

    $nextReview = date('Y-m-d H:i:s', strtotime("+$interval days"));

    // Update or insert review record
    $stmt = $pdo->prepare("
        INSERT INTO user_reviews (user_id, card_id, ease_factor, interval_days, repetitions, next_review, last_reviewed)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
        ease_factor = VALUES(ease_factor),
        interval_days = VALUES(interval_days),
        repetitions = VALUES(repetitions),
        next_review = VALUES(next_review),
        last_reviewed = NOW()
    ");
    $stmt->execute([$_SESSION['user_id'], $cardId, $easeFactor, $interval, $repetitions, $nextReview]);

    // Log the review
    $stmt = $pdo->prepare("
        INSERT INTO flashcard_reviews (user_id, card_id, difficulty, review_time, created_at)
        VALUES (?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([$_SESSION['user_id'], $cardId, $difficulty]);

    // Award points based on difficulty
    $points = ['again' => 1, 'hard' => 2, 'medium' => 3, 'easy' => 5][$difficulty];
    require_once '../../includes/gamification.php';
    awardPoints($_SESSION['user_id'], $points, 'flashcard_review', 'Reviewed flashcard');

    echo json_encode([
        'success' => true,
        'next_review' => $nextReview,
        'interval_days' => $interval,
        'points_awarded' => $points
    ]);

} catch (Exception $e) {
    error_log('Error reviewing card: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>