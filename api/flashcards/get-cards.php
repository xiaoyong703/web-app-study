<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$setId = $_GET['set_id'] ?? null;

if (!$setId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Set ID required']);
    exit;
}

try {
    // Get flashcards for the set
    $stmt = $pdo->prepare("
        SELECT f.*, 
               COALESCE(ur.ease_factor, 2.5) as ease_factor,
               COALESCE(ur.interval_days, 1) as interval_days,
               COALESCE(ur.repetitions, 0) as repetitions,
               COALESCE(ur.next_review, NOW()) as next_review
        FROM flashcards f
        LEFT JOIN user_reviews ur ON f.id = ur.card_id AND ur.user_id = ?
        WHERE f.set_id = ? AND f.status = 'active'
        ORDER BY f.position ASC
    ");
    $stmt->execute([$_SESSION['user_id'], $setId]);
    $cards = $stmt->fetchAll();

    // Get set information
    $stmt = $pdo->prepare("
        SELECT fs.*, 
               COUNT(f.id) as total_cards,
               AVG(CASE WHEN ur.ease_factor IS NOT NULL THEN ur.ease_factor ELSE 2.5 END) as avg_ease
        FROM flashcard_sets fs
        LEFT JOIN flashcards f ON fs.id = f.set_id
        LEFT JOIN user_reviews ur ON f.id = ur.card_id AND ur.user_id = ?
        WHERE fs.id = ? AND (fs.user_id = ? OR fs.is_public = 1)
        GROUP BY fs.id
    ");
    $stmt->execute([$_SESSION['user_id'], $setId, $_SESSION['user_id']]);
    $set = $stmt->fetch();

    if (!$set) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Set not found']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'cards' => $cards,
        'set' => $set
    ]);

} catch (Exception $e) {
    error_log('Error getting flashcards: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>