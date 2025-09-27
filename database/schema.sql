-- YPT Study App Database Schema

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `grade_level` varchar(20) DEFAULT NULL,
    `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
    `newsletter_subscription` tinyint(1) NOT NULL DEFAULT '0',
    `remember_token` varchar(255) DEFAULT NULL,
    `oauth_provider` enum('google','github','facebook') DEFAULT NULL,
    `oauth_id` varchar(255) DEFAULT NULL,
    `profile_picture` varchar(255) DEFAULT NULL,
    `last_login` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_email` (`email`),
    KEY `idx_status` (`status`),
    KEY `idx_oauth` (`oauth_provider`, `oauth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create study_sessions table
CREATE TABLE IF NOT EXISTS `study_sessions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `subject` varchar(100) NOT NULL DEFAULT 'General',
    `duration` int(11) NOT NULL COMMENT 'Duration in seconds',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_created_at` (`created_at`),
    KEY `idx_subject` (`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create todos table
CREATE TABLE IF NOT EXISTS `todos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `task` text NOT NULL,
    `completed` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_completed` (`completed`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create users table (for future phases)
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL UNIQUE,
    `email` varchar(100) NOT NULL UNIQUE,
    `password_hash` varchar(255) NOT NULL,
    `display_name` varchar(100) DEFAULT NULL,
    `avatar_url` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `last_login` timestamp NULL DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    KEY `idx_username` (`username`),
    KEY `idx_email` (`email`),
    KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_settings table (for future phases)
CREATE TABLE IF NOT EXISTS `user_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `setting_key` varchar(100) NOT NULL,
    `setting_value` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_setting_key` (`setting_key`),
    UNIQUE KEY `unique_user_setting` (`user_id`, `setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create breaks table (for Phase 2)
CREATE TABLE IF NOT EXISTS `breaks` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `break_type` enum('nap','meal','walk','custom') NOT NULL,
    `duration` int(11) NOT NULL COMMENT 'Duration in seconds',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_break_type` (`break_type`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create subjects table (for future phases)
CREATE TABLE IF NOT EXISTS `subjects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `color` varchar(7) DEFAULT '#6366f1',
    `icon` varchar(50) DEFAULT 'fas fa-book',
    `target_hours_per_week` int(11) DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    UNIQUE KEY `unique_user_subject` (`user_id`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default subjects
INSERT IGNORE INTO `subjects` (`user_id`, `name`, `color`, `icon`) VALUES
('default', 'Math', '#3b82f6', 'fas fa-calculator'),
('default', 'Science', '#10b981', 'fas fa-flask'),
('default', 'English', '#f59e0b', 'fas fa-book-open'),
('default', 'History', '#8b5cf6', 'fas fa-landmark'),
('default', 'Other', '#6b7280', 'fas fa-book');

-- Create sample data for demonstration (optional)
-- INSERT INTO `study_sessions` (`user_id`, `subject`, `duration`, `created_at`) VALUES
-- ('demo_user', 'Math', 1500, NOW() - INTERVAL 1 DAY),
-- ('demo_user', 'Science', 2700, NOW() - INTERVAL 2 DAY),
-- ('demo_user', 'English', 1800, NOW() - INTERVAL 3 DAY);

-- INSERT INTO `todos` (`user_id`, `task`, `completed`) VALUES
-- ('demo_user', 'Review calculus chapter 5', 0),
-- ('demo_user', 'Complete physics homework', 1),
-- ('demo_user', 'Read history assignment', 0);

-- Phase 2 & 3 Additional Tables

-- Create study_groups table
CREATE TABLE IF NOT EXISTS `study_groups` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text,
    `creator_id` int(11) NOT NULL,
    `invite_code` varchar(20) UNIQUE NOT NULL,
    `is_public` tinyint(1) NOT NULL DEFAULT '1',
    `max_members` int(11) DEFAULT '50',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_creator_id` (`creator_id`),
    KEY `idx_invite_code` (`invite_code`),
    KEY `idx_is_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create group_members table
CREATE TABLE IF NOT EXISTS `group_members` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `group_id` int(11) NOT NULL,
    `user_id` varchar(100) NOT NULL,
    `role` enum('member','admin','owner') NOT NULL DEFAULT 'member',
    `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_group_id` (`group_id`),
    KEY `idx_user_id` (`user_id`),
    UNIQUE KEY `unique_group_member` (`group_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create flashcard_sets table
CREATE TABLE IF NOT EXISTS `flashcard_sets` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `title` varchar(200) NOT NULL,
    `description` text,
    `subject` varchar(100) DEFAULT 'General',
    `is_public` tinyint(1) NOT NULL DEFAULT '0',
    `total_cards` int(11) DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_subject` (`subject`),
    KEY `idx_is_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create flashcards table
CREATE TABLE IF NOT EXISTS `flashcards` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `set_id` int(11) NOT NULL,
    `question` text NOT NULL,
    `answer` text NOT NULL,
    `hint` text,
    `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
    `times_reviewed` int(11) DEFAULT '0',
    `times_correct` int(11) DEFAULT '0',
    `last_reviewed` timestamp NULL DEFAULT NULL,
    `next_review` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_set_id` (`set_id`),
    KEY `idx_difficulty` (`difficulty`),
    KEY `idx_next_review` (`next_review`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_achievements table (gamification)
CREATE TABLE IF NOT EXISTS `user_achievements` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `achievement_type` varchar(50) NOT NULL,
    `achievement_name` varchar(100) NOT NULL,
    `description` text,
    `points` int(11) DEFAULT '0',
    `badge_icon` varchar(50) DEFAULT 'fas fa-trophy',
    `earned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_achievement_type` (`achievement_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_stats table (enhanced statistics)
CREATE TABLE IF NOT EXISTS `user_stats` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `total_study_time` int(11) DEFAULT '0',
    `total_sessions` int(11) DEFAULT '0',
    `current_streak` int(11) DEFAULT '0',
    `longest_streak` int(11) DEFAULT '0',
    `total_points` int(11) DEFAULT '0',
    `level` int(11) DEFAULT '1',
    `experience_points` int(11) DEFAULT '0',
    `last_study_date` date DEFAULT NULL,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_user_stats` (`user_id`),
    KEY `idx_total_points` (`total_points`),
    KEY `idx_level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create d_day_events table
CREATE TABLE IF NOT EXISTS `d_day_events` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `title` varchar(200) NOT NULL,
    `description` text,
    `target_date` date NOT NULL,
    `color` varchar(7) DEFAULT '#6366f1',
    `is_completed` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_target_date` (`target_date`),
    KEY `idx_is_completed` (`is_completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create focus_sessions table
CREATE TABLE IF NOT EXISTS `focus_sessions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `blocked_sites` text COMMENT 'JSON array of blocked websites',
    `whitelist_sites` text COMMENT 'JSON array of allowed websites',
    `duration` int(11) NOT NULL COMMENT 'Duration in seconds',
    `completed` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_completed` (`completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create quizzes table
CREATE TABLE IF NOT EXISTS `quizzes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` varchar(100) NOT NULL,
    `title` varchar(200) NOT NULL,
    `description` text,
    `subject` varchar(100) DEFAULT 'General',
    `total_questions` int(11) DEFAULT '0',
    `time_limit` int(11) DEFAULT NULL COMMENT 'Time limit in minutes',
    `is_public` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_subject` (`subject`),
    KEY `idx_is_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create quiz_questions table
CREATE TABLE IF NOT EXISTS `quiz_questions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `quiz_id` int(11) NOT NULL,
    `question` text NOT NULL,
    `question_type` enum('multiple_choice','true_false','short_answer') DEFAULT 'multiple_choice',
    `options` text COMMENT 'JSON array for multiple choice options',
    `correct_answer` text NOT NULL,
    `explanation` text,
    `points` int(11) DEFAULT '1',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_quiz_id` (`quiz_id`),
    KEY `idx_question_type` (`question_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create quiz_attempts table
CREATE TABLE IF NOT EXISTS `quiz_attempts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `quiz_id` int(11) NOT NULL,
    `user_id` varchar(100) NOT NULL,
    `score` int(11) DEFAULT '0',
    `total_points` int(11) DEFAULT '0',
    `percentage` decimal(5,2) DEFAULT '0.00',
    `time_taken` int(11) DEFAULT NULL COMMENT 'Time taken in seconds',
    `answers` text COMMENT 'JSON array of user answers',
    `completed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_quiz_id` (`quiz_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_percentage` (`percentage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample achievements
INSERT IGNORE INTO `user_achievements` (`user_id`, `achievement_type`, `achievement_name`, `description`, `points`, `badge_icon`) VALUES
('default', 'first_session', 'First Steps', 'Complete your first study session', 10, 'fas fa-baby'),
('default', 'daily_goal', 'Daily Warrior', 'Study for 2+ hours in a single day', 25, 'fas fa-fire'),
('default', 'weekly_goal', 'Weekly Champion', 'Complete your weekly study goal', 50, 'fas fa-crown'),
('default', 'streak_7', 'Week Streak', 'Study for 7 consecutive days', 75, 'fas fa-calendar-check'),
('default', 'streak_30', 'Month Master', 'Study for 30 consecutive days', 200, 'fas fa-gem'),
('default', 'flashcard_master', 'Card Shark', 'Review 100 flashcards', 30, 'fas fa-cards'),
('default', 'quiz_ace', 'Quiz Master', 'Score 90%+ on 5 quizzes', 40, 'fas fa-medal'),
('default', 'focus_master', 'Zen Master', 'Complete 10 focus sessions', 60, 'fas fa-yin-yang');

-- Sample D-Day events
INSERT IGNORE INTO `d_day_events` (`user_id`, `title`, `description`, `target_date`, `color`) VALUES
('demo_user', 'Math Final Exam', 'Calculus final exam preparation', DATE_ADD(CURDATE(), INTERVAL 30 DAY), '#3b82f6'),
('demo_user', 'SAT Test', 'Standardized test preparation', DATE_ADD(CURDATE(), INTERVAL 60 DAY), '#ef4444'),
('demo_user', 'Science Project Due', 'Biology research project submission', DATE_ADD(CURDATE(), INTERVAL 14 DAY), '#10b981');

-- Sample flashcard set
INSERT IGNORE INTO `flashcard_sets` (`user_id`, `title`, `description`, `subject`, `is_public`) VALUES
('demo_user', 'Basic Math Formulas', 'Essential mathematical formulas for exams', 'Math', 1);

-- Sample flashcards
INSERT IGNORE INTO `flashcards` (`set_id`, `question`, `answer`, `hint`, `difficulty`) VALUES
(1, 'What is the quadratic formula?', 'x = (-b ± √(b²-4ac)) / 2a', 'Used to solve ax² + bx + c = 0', 'medium'),
(1, 'What is the Pythagorean theorem?', 'a² + b² = c²', 'Relates to right triangles', 'easy'),
(1, 'What is the derivative of sin(x)?', 'cos(x)', 'Basic calculus derivative', 'medium');

-- Useful queries for maintenance:

-- Get user statistics:
-- SELECT 
--     subject,
--     COUNT(*) as sessions,
--     SUM(duration) as total_time,
--     AVG(duration) as avg_time
-- FROM study_sessions 
-- WHERE user_id = 'your_user_id' 
-- GROUP BY subject;

-- Get leaderboard:
-- SELECT user_id, total_points, level, current_streak 
-- FROM user_stats 
-- ORDER BY total_points DESC 
-- LIMIT 10;

-- Clean up old guest sessions (run periodically):
-- DELETE FROM study_sessions 
-- WHERE user_id LIKE 'guest_%' 
-- AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);

-- DELETE FROM todos 
-- WHERE user_id LIKE 'guest_%' 
-- AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);