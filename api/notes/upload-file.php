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

// Handle file upload
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['file'];
$subject = $_POST['subject'] ?? '';

// Validate file
$allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'text/plain',
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif'
];

$maxSize = 10 * 1024 * 1024; // 10MB

if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File type not allowed']);
    exit;
}

if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 10MB']);
    exit;
}

try {
    // Create upload directory if it doesn't exist
    $uploadDir = '../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Create thumbnail for images
    $thumbnailPath = null;
    if (strpos($file['type'], 'image/') === 0) {
        $thumbnailDir = '../../uploads/thumbnails/';
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }
        
        $thumbnailPath = $thumbnailDir . $filename;
        // Simple thumbnail creation (you might want to use a proper image library)
        copy($filepath, $thumbnailPath);
    }

    // Save file info to database
    $stmt = $pdo->prepare("
        INSERT INTO uploaded_files (user_id, filename, original_name, mime_type, file_size, file_path, thumbnail_path, subject, uploaded_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $_SESSION['user_id'],
        $filename,
        $file['name'],
        $file['type'],
        $file['size'],
        'uploads/' . $filename,
        $thumbnailPath ? 'uploads/thumbnails/' . $filename : null,
        $subject
    ]);

    $fileId = $pdo->lastInsertId();

    // Award points for file upload
    require_once '../../includes/gamification.php';
    awardPoints($_SESSION['user_id'], 10, 'file_upload', 'Uploaded study material');

    echo json_encode([
        'success' => true,
        'file_id' => $fileId,
        'filename' => $filename,
        'original_name' => $file['name'],
        'url' => 'uploads/' . $filename,
        'message' => 'File uploaded successfully'
    ]);

} catch (Exception $e) {
    error_log('Error uploading file: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>