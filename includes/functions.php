<?php
// Utility functions for the study app

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function formatTime($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    
    if ($hours > 0) {
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }
    return sprintf("%02d:%02d", $minutes, $seconds);
}

function getStudyStats($user_id, $pdo) {
    try {
        // Get today's stats
        $stmt = $pdo->prepare("SELECT 
            SUM(duration) as total_time,
            COUNT(*) as sessions_count,
            AVG(duration) as avg_session
            FROM study_sessions 
            WHERE user_id = ? AND DATE(created_at) = CURDATE()");
        $stmt->execute([$user_id]);
        $today = $stmt->fetch();
        
        // Get week's stats
        $stmt = $pdo->prepare("SELECT 
            SUM(duration) as total_time,
            COUNT(*) as sessions_count
            FROM study_sessions 
            WHERE user_id = ? AND WEEK(created_at) = WEEK(NOW())");
        $stmt->execute([$user_id]);
        $week = $stmt->fetch();
        
        return [
            'today' => $today,
            'week' => $week
        ];
    } catch(PDOException $e) {
        error_log("Error fetching stats: " . $e->getMessage());
        return ['today' => null, 'week' => null];
    }
}

function getTodoItems($user_id, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error fetching todos: " . $e->getMessage());
        return [];
    }
}

function addTodoItem($user_id, $task, $pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO todos (user_id, task) VALUES (?, ?)");
        return $stmt->execute([$user_id, $task]);
    } catch(PDOException $e) {
        error_log("Error adding todo: " . $e->getMessage());
        return false;
    }
}

function toggleTodoItem($todo_id, $user_id, $pdo) {
    try {
        $stmt = $pdo->prepare("UPDATE todos SET completed = NOT completed WHERE id = ? AND user_id = ?");
        return $stmt->execute([$todo_id, $user_id]);
    } catch(PDOException $e) {
        error_log("Error toggling todo: " . $e->getMessage());
        return false;
    }
}

function saveStudySession($user_id, $subject, $duration, $pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO study_sessions (user_id, subject, duration) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $subject, $duration]);
    } catch(PDOException $e) {
        error_log("Error saving study session: " . $e->getMessage());
        return false;
    }
}
?>