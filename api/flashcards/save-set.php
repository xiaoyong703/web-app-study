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

$title = trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$subject = $input['subject'] ?? '';
$isPublic = $input['is_public'] ?? false;
$cards = $input['cards'] ?? [];

if (!$title || empty($cards)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Title and cards required']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Create flashcard set
    $stmt = $pdo->prepare("
        INSERT INTO flashcard_sets (user_id, title, description, subject, is_public, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$_SESSION['user_id'], $title, $description, $subject, $isPublic ? 1 : 0]);

    $setId = $pdo->lastInsertId();

    // Add flashcards
    $stmt = $pdo->prepare("
        INSERT INTO flashcards (set_id, question, answer, hint, difficulty, position, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    foreach ($cards as $index => $card) {
        $question = trim($card['question'] ?? '');
        $answer = trim($card['answer'] ?? '');
        $hint = trim($card['hint'] ?? '');
        $difficulty = $card['difficulty'] ?? 'medium';

        if ($question && $answer) {
            $stmt->execute([$setId, $question, $answer, $hint, $difficulty, $index + 1]);
        }
    }

    $pdo->commit();

    // Award points for creating flashcard set
    require_once '../../includes/gamification.php';
    awardPoints($_SESSION['user_id'], count($cards) * 5, 'flashcard_create', 'Created flashcard set');

    echo json_encode([
        'success' => true,
        'set_id' => $setId,
        'message' => 'Flashcard set created successfully'
    ]);

} catch (Exception $e) {
    $pdo->rollback();
    error_log('Error creating flashcard set: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>