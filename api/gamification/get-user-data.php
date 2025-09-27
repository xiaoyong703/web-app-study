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
    // Get user's current gamification data
    $stmt = $pdo->prepare("
        SELECT us.*, u.first_name, u.last_name, u.email
        FROM user_stats us
        JOIN users u ON us.user_id = u.id
        WHERE us.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $userStats = $stmt->fetch();

    if (!$userStats) {
        // Create initial user stats if they don't exist
        $stmt = $pdo->prepare("
            INSERT INTO user_stats (user_id, points, level, streak_days, total_study_time, sessions_count, created_at)
            VALUES (?, 0, 1, 0, 0, 0, NOW())
        ");
        $stmt->execute([$_SESSION['user_id']]);
        
        // Fetch the newly created stats
        $stmt = $pdo->prepare("
            SELECT us.*, u.first_name, u.last_name, u.email
            FROM user_stats us
            JOIN users u ON us.user_id = u.id
            WHERE us.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $userStats = $stmt->fetch();
    }

    // Calculate points needed for next level
    $currentLevel = $userStats['level'];
    $currentPoints = $userStats['points'];
    $pointsForNextLevel = pow(($currentLevel * 10), 2);
    $pointsNeeded = max(0, $pointsForNextLevel - $currentPoints);

    // Get recent point history
    $stmt = $pdo->prepare("
        SELECT * FROM point_history 
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $recentPoints = $stmt->fetchAll();

    // Get user's rank
    $stmt = $pdo->prepare("
        SELECT COUNT(*) + 1 as user_rank
        FROM user_stats 
        WHERE points > ?
    ");
    $stmt->execute([$currentPoints]);
    $rankData = $stmt->fetch();

    // Get achievements count
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as unlocked_achievements
        FROM user_achievements 
        WHERE user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $achievementData = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'user_data' => [
            'id' => $userStats['user_id'],
            'name' => $userStats['first_name'] . ' ' . $userStats['last_name'],
            'email' => $userStats['email'],
            'points' => (int)$userStats['points'],
            'level' => (int)$userStats['level'],
            'streak_days' => (int)$userStats['streak_days'],
            'total_study_time' => (int)$userStats['total_study_time'],
            'sessions_count' => (int)$userStats['sessions_count'],
            'points_for_next_level' => $pointsForNextLevel,
            'points_needed' => $pointsNeeded,
            'rank' => (int)$rankData['user_rank'],
            'unlocked_achievements' => (int)$achievementData['unlocked_achievements']
        ],
        'recent_points' => $recentPoints
    ]);

} catch (Exception $e) {
    error_log('Error getting user gamification data: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>