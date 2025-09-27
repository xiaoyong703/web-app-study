<?php
// Gamification helper functions

function awardPoints($userId, $points, $type, $description) {
    global $pdo;
    
    try {
        // Add points to user stats
        $stmt = $pdo->prepare("
            UPDATE user_stats 
            SET points = points + ?, 
                level = FLOOR(SQRT(points + ?) / 10) + 1,
                updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$points, $points, $userId]);
        
        // Log the points award
        $stmt = $pdo->prepare("
            INSERT INTO point_history (user_id, points, type, description, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$userId, $points, $type, $description]);
        
        return true;
    } catch (Exception $e) {
        error_log('Error awarding points: ' . $e->getMessage());
        return false;
    }
}

function checkLevelUp($userId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT points, level, 
                   FLOOR(SQRT(points) / 10) + 1 as calculated_level
            FROM user_stats 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $stats = $stmt->fetch();
        
        if ($stats && $stats['calculated_level'] > $stats['level']) {
            // Level up!
            $stmt = $pdo->prepare("
                UPDATE user_stats 
                SET level = ?, updated_at = NOW()
                WHERE user_id = ?
            ");
            $stmt->execute([$stats['calculated_level'], $userId]);
            
            return [
                'leveled_up' => true,
                'new_level' => $stats['calculated_level'],
                'old_level' => $stats['level']
            ];
        }
        
        return ['leveled_up' => false];
    } catch (Exception $e) {
        error_log('Error checking level up: ' . $e->getMessage());
        return ['leveled_up' => false];
    }
}

function updateStreak($userId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT last_activity_date, streak_days
            FROM user_stats
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $stats = $stmt->fetch();
        
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        
        $newStreak = 1;
        if ($stats) {
            $lastActivity = $stats['last_activity_date'];
            if ($lastActivity === $yesterday) {
                $newStreak = $stats['streak_days'] + 1;
            } elseif ($lastActivity === $today) {
                $newStreak = $stats['streak_days'];
            }
        }
        
        $stmt = $pdo->prepare("
            UPDATE user_stats 
            SET streak_days = ?, 
                last_activity_date = ?,
                updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$newStreak, $today, $userId]);
        
        return $newStreak;
    } catch (Exception $e) {
        error_log('Error updating streak: ' . $e->getMessage());
        return 1;
    }
}
?>