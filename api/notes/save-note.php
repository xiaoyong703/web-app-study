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

// Get form data
$title = trim($_POST['title'] ?? '');
$subject = $_POST['subject'] ?? '';
$tags = trim($_POST['tags'] ?? '');
$content = $_POST['content'] ?? '';
$noteId = $_POST['id'] ?? null;

if (!$title) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Title is required']);
    exit;
}

try {
    $pdo->beginTransaction();

    if ($noteId) {
        // Update existing note
        $stmt = $pdo->prepare("
            UPDATE notes 
            SET title = ?, subject = ?, tags = ?, content = ?, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$title, $subject, $tags, $content, $noteId, $_SESSION['user_id']]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception('Note not found or access denied');
        }
    } else {
        // Create new note
        $stmt = $pdo->prepare("
            INSERT INTO notes (user_id, title, subject, tags, content, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$_SESSION['user_id'], $title, $subject, $tags, $content]);
        $noteId = $pdo->lastInsertId();
    }

    // Handle attachments if any
    if (isset($_FILES['attachments'])) {
        $attachments = $_FILES['attachments'];
        $uploadDir = '../../uploads/notes/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Handle multiple files
        for ($i = 0; $i < count($attachments['name']); $i++) {
            if ($attachments['error'][$i] === UPLOAD_ERR_OK) {
                $originalName = $attachments['name'][$i];
                $tmpName = $attachments['tmp_name'][$i];
                $size = $attachments['size'][$i];
                $type = $attachments['type'][$i];
                
                // Generate unique filename
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $filepath = $uploadDir . $filename;
                
                if (move_uploaded_file($tmpName, $filepath)) {
                    // Save attachment to database
                    $stmt = $pdo->prepare("
                        INSERT INTO note_attachments (note_id, filename, original_name, mime_type, file_size, uploaded_at)
                        VALUES (?, ?, ?, ?, ?, NOW())
                    ");
                    $stmt->execute([$noteId, $filename, $originalName, $type, $size]);
                }
            }
        }
    }

    $pdo->commit();

    // Award points
    require_once '../../includes/gamification.php';
    $points = $noteId && $_POST['id'] ? 5 : 15; // Less points for updates
    awardPoints($_SESSION['user_id'], $points, 'note_save', $noteId && $_POST['id'] ? 'Updated note' : 'Created note');

    echo json_encode([
        'success' => true,
        'note_id' => $noteId,
        'message' => 'Note saved successfully',
        'points_awarded' => $points
    ]);

} catch (Exception $e) {
    $pdo->rollback();
    error_log('Error saving note: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>