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
    // Get user's focus settings
    $stmt = $pdo->prepare("
        SELECT * FROM focus_settings 
        WHERE user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $settings = $stmt->fetch();

    // Get blocked sites
    $stmt = $pdo->prepare("
        SELECT url FROM blocked_sites 
        WHERE user_id = ? AND is_active = 1
        ORDER BY url
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $blockedSites = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Default settings if none exist
    if (!$settings) {
        $settings = [
            'pomodoro_work_time' => 25,
            'pomodoro_break_time' => 5,
            'pomodoro_long_break' => 15,
            'sessions_until_long_break' => 4,
            'enable_website_blocking' => true,
            'enable_notifications' => true,
            'enable_sounds' => true,
            'ambient_sound' => 'none',
            'break_reminders' => true,
            'strict_mode' => false
        ];
    }

    echo json_encode([
        'success' => true,
        'settings' => $settings,
        'blocked_sites' => $blockedSites
    ]);

} catch (Exception $e) {
    error_log('Error getting focus settings: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>