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
    // Get all achievements with user progress
    $stmt = $pdo->prepare("
        SELECT a.*,
               ua.unlocked_at,
               ua.progress,
               CASE WHEN ua.id IS NOT NULL THEN 1 ELSE 0 END as is_unlocked
        FROM achievements a
        LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = ?
        ORDER BY a.category, a.points_required ASC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $achievements = $stmt->fetchAll();

    // Get user stats for progress calculation
    $stmt = $pdo->prepare("
        SELECT us.*,
               COUNT(DISTINCT ss.id) as study_sessions,
               COUNT(DISTINCT fs.id) as focus_sessions,
               COUNT(DISTINCT qr.id) as quiz_attempts,
               COUNT(DISTINCT fc.id) as flashcard_reviews
        FROM user_stats us
        LEFT JOIN study_sessions ss ON us.user_id = ss.user_id
        LEFT JOIN focus_sessions fs ON us.user_id = fs.user_id
        LEFT JOIN quiz_results qr ON us.user_id = qr.user_id
        LEFT JOIN flashcard_reviews fc ON us.user_id = fc.user_id
        WHERE us.user_id = ?
        GROUP BY us.user_id
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $userStats = $stmt->fetch();

    // Calculate progress for locked achievements
    foreach ($achievements as &$achievement) {
        if (!$achievement['is_unlocked']) {
            $progress = 0;
            
            switch ($achievement['type']) {
                case 'study_time':
                    $progress = min(100, ($userStats['total_study_time'] / 60 / $achievement['target_value']) * 100);
                    break;
                case 'sessions':
                    $progress = min(100, ($userStats['study_sessions'] / $achievement['target_value']) * 100);
                    break;
                case 'streak':
                    $progress = min(100, ($userStats['streak_days'] / $achievement['target_value']) * 100);
                    break;
                case 'level':
                    $progress = min(100, ($userStats['level'] / $achievement['target_value']) * 100);
                    break;
                case 'focus_time':
                    $progress = min(100, (($userStats['total_focus_time'] ?? 0) / 60 / $achievement['target_value']) * 100);
                    break;
                case 'quiz_master':
                    $progress = min(100, ($userStats['quiz_attempts'] / $achievement['target_value']) * 100);
                    break;
            }
            
            $achievement['progress'] = round($progress, 1);
        } else {
            $achievement['progress'] = 100;
        }
    }

    // Get leaderboard data
    $stmt = $pdo->prepare("
        SELECT u.first_name, u.last_name, us.points, us.level, us.streak_days,
               COUNT(ua.id) as achievements_count,
               RANK() OVER (ORDER BY us.points DESC) as rank
        FROM user_stats us
        JOIN users u ON us.user_id = u.id
        LEFT JOIN user_achievements ua ON us.user_id = ua.user_id
        GROUP BY us.user_id
        ORDER BY us.points DESC
        LIMIT 10
    ");
    $stmt->execute();
    $leaderboard = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'achievements' => $achievements,
        'user_stats' => $userStats,
        'leaderboard' => $leaderboard
    ]);

} catch (Exception $e) {
    error_log('Error getting achievements: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>