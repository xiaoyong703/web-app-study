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
    // Get user's notes
    $stmt = $pdo->prepare("
        SELECT n.*,
               COUNT(na.id) as attachment_count
        FROM notes n
        LEFT JOIN note_attachments na ON n.id = na.note_id
        WHERE n.user_id = ?
        GROUP BY n.id
        ORDER BY n.updated_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $notes = $stmt->fetchAll();

    // Get user's files
    $stmt = $pdo->prepare("
        SELECT id, filename as name, original_name, mime_type, file_size as size, 
               subject, uploaded_at, file_path as url,
               CASE 
                   WHEN mime_type LIKE 'image/%' THEN CONCAT('uploads/thumbnails/', filename)
                   ELSE NULL 
               END as thumbnail_url
        FROM uploaded_files
        WHERE user_id = ?
        ORDER BY uploaded_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $files = $stmt->fetchAll();

    // Add type field to distinguish between notes and files
    foreach ($notes as &$note) {
        $note['type'] = 'note';
    }
    foreach ($files as &$file) {
        $file['type'] = 'file';
    }

    echo json_encode([
        'success' => true,
        'notes' => $notes,
        'files' => $files
    ]);

} catch (Exception $e) {
    error_log('Error getting notes: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>