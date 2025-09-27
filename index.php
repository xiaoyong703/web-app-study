<?php
session_start();

// Database configuration
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && !str_starts_with($_SESSION['user_id'], 'guest_');

// Redirect to login if not authenticated and not already on login/register page
$page = $_GET['page'] ?? 'dashboard';
$publicPages = ['login', 'register'];

if (!$isLoggedIn && !in_array($page, $publicPages)) {
    header('Location: pages/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YPT Study App - Focus & Excel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="light-theme">
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <i class="fas fa-graduation-cap"></i>
                <span>YPT Study</span>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=flashcards" class="nav-link <?php echo $page === 'flashcards' ? 'active' : ''; ?>">
                        <i class="fas fa-cards"></i> Flashcards
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=quizzes" class="nav-link <?php echo $page === 'quizzes' ? 'active' : ''; ?>">
                        <i class="fas fa-question-circle"></i> Quizzes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=groups" class="nav-link <?php echo $page === 'groups' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Study Groups
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=focus" class="nav-link <?php echo $page === 'focus' ? 'active' : ''; ?>">
                        <i class="fas fa-eye-slash"></i> Focus Mode
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=analytics" class="nav-link <?php echo $page === 'analytics' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i> Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=achievements" class="nav-link <?php echo $page === 'achievements' ? 'active' : ''; ?>">
                        <i class="fas fa-trophy"></i> Achievements
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=notes" class="nav-link <?php echo $page === 'notes' ? 'active' : ''; ?>">
                        <i class="fas fa-sticky-note"></i> Notes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=daily-review" class="nav-link <?php echo $page === 'daily-review' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-check"></i> Daily Review
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=ai-tutor" class="nav-link <?php echo $page === 'ai-tutor' ? 'active' : ''; ?> coming-soon">
                        <i class="fas fa-robot"></i> AI Tutor
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=marketplace" class="nav-link <?php echo $page === 'marketplace' ? 'active' : ''; ?> coming-soon">
                        <i class="fas fa-store"></i> Marketplace
                    </a>
                </li>
            </ul>
            
            <div class="nav-controls">
                <select id="language-selector" class="language-selector" title="Change Language">
                    <option value="en">🇺🇸 EN</option>
                    <option value="es">🇪🇸 ES</option>
                    <option value="fr">🇫🇷 FR</option>
                    <option value="de">🇩🇪 DE</option>
                    <option value="zh">🇨🇳 中文</option>
                    <option value="ja">🇯🇵 日本語</option>
                    <option value="ko">🇰🇷 한국어</option>
                </select>
                <button id="theme-toggle" class="theme-btn" title="Toggle Theme">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="profile-btn" title="Profile">
                    <i class="fas fa-user"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?php
        switch ($page) {
            case 'dashboard':
                include 'pages/dashboard.php';
                break;
            case 'flashcards':
                include 'pages/flashcards.php';
                break;
            case 'quizzes':
                include 'pages/quizzes.php';
                break;
            case 'groups':
                include 'pages/groups.php';
                break;
            case 'focus':
                include 'pages/focus.php';
                break;
            case 'analytics':
                include 'pages/analytics.php';
                break;
            case 'achievements':
                include 'pages/achievements.php';
                break;
            case 'notes':
                include 'pages/notes.php';
                break;
            case 'daily-review':
                include 'pages/daily-review.php';
                break;
            case 'ai-tutor':
            case 'marketplace':
                include 'pages/coming-soon.php';
                break;
            default:
                include 'pages/dashboard.php';
        }
        ?>
    </main>

    <!-- Scripts -->
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/timer.js"></script>
    <script src="assets/js/todo.js"></script>
    <script src="assets/js/stats.js"></script>
    <script src="assets/js/flashcards.js"></script>
    <script src="assets/js/quizzes.js"></script>
    <script src="assets/js/groups.js"></script>
    <script src="assets/js/focus.js"></script>
    <script src="assets/js/gamification.js"></script>
    <script src="assets/js/analytics.js"></script>
    <script src="assets/js/achievements.js"></script>
    <script src="assets/js/daily-review.js"></script>
    <script src="assets/js/notes.js"></script>
    <script src="assets/js/language.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>