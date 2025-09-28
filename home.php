<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YPT Study - Modern Learning Platform</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }

        /* Sidebar Navigation */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            padding: 2rem 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 2rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-nav {
            padding: 0 1rem;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .nav-item:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .nav-item.active {
            background: #3b82f6;
            color: white;
        }

        .nav-item i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Top Header */
        .top-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .header-title h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .header-title p {
            color: #64748b;
            font-size: 0.875rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #64748b;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1rem;
            padding: 3rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .welcome-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: #3b82f6;
            border: 1px solid #3b82f6;
        }

        .btn-outline:hover {
            background: #3b82f6;
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-title {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }

        .stat-icon.blue { background: #dbeafe; color: #3b82f6; }
        .stat-icon.green { background: #dcfce7; color: #16a34a; }
        .stat-icon.purple { background: #f3e8ff; color: #9333ea; }
        .stat-icon.orange { background: #fed7aa; color: #ea580c; }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stat-change {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-change.positive { color: #16a34a; }
        .stat-change.negative { color: #dc2626; }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .quick-actions-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .quick-actions-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .action-item {
            padding: 1.5rem;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #64748b;
        }

        .action-item:hover {
            background: #f8fafc;
            color: #1e293b;
        }

        .action-item:last-child {
            border-right: none;
        }

        .action-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            background: #f1f5f9;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #3b82f6;
        }

        .action-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .action-desc {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu {
                display: block;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: #64748b;
                cursor: pointer;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .welcome-section {
                padding: 2rem;
            }

            .welcome-actions {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-graduation-cap"></i> YPT Study</h2>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="#" class="nav-item active">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
                <a href="index.php?page=dashboard" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    Analytics
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-book"></i>
                    Study Sessions
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Learning</div>
                <a href="#" class="nav-item">
                    <i class="fas fa-brain"></i>
                    Flashcards
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-question-circle"></i>
                    Quizzes
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-sticky-note"></i>
                    Notes
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-users"></i>
                    Study Groups
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Tools</div>
                <a href="#" class="nav-item">
                    <i class="fas fa-clock"></i>
                    Focus Timer
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-trophy"></i>
                    Achievements
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-calendar"></i>
                    Daily Review
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <?php if ($isAuthenticated && $user): ?>
                    <a href="#" class="nav-item">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                    <a href="#" class="nav-item">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                    <a href="api/auth/logout.php" class="nav-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Sign Out
                    </a>
                <?php else: ?>
                    <a href="pages/login.php" class="nav-item">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </a>
                    <a href="pages/register.php" class="nav-item">
                        <i class="fas fa-user-plus"></i>
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <button class="mobile-menu" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="header-title">
                <h1><?php echo $isAuthenticated && $user ? 'Welcome back, ' . htmlspecialchars($user['first_name']) . '!' : 'Welcome to YPT Study'; ?></h1>
                <p><?php echo $isAuthenticated && $user ? 'Ready to continue your learning journey?' : 'Your personalized learning experience awaits'; ?></p>
            </div>
            
            <div class="header-actions">
                <?php if ($isAuthenticated && $user): ?>
                    <div class="user-info">
                        <span><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1)); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <?php if (isset($_GET['welcome']) && $isAuthenticated && $user): ?>
                    <div class="welcome-content">
                        <h2>🎉 Welcome to YPT Study!</h2>
                        <p>You're all set, <?php echo htmlspecialchars($user['first_name']); ?>! Let's start your personalized learning journey.</p>
                        <div class="welcome-actions">
                            <a href="index.php?page=dashboard" class="btn btn-secondary">
                                <i class="fas fa-rocket"></i>
                                Launch Dashboard
                            </a>
                            <a href="#quick-actions" class="btn btn-secondary">
                                <i class="fas fa-compass"></i>
                                Explore Features
                            </a>
                        </div>
                    </div>
                <?php elseif ($isAuthenticated && $user): ?>
                    <div class="welcome-content">
                        <h2>Ready to Study?</h2>
                        <p>Continue where you left off or start a new study session. Your progress is waiting!</p>
                        <div class="welcome-actions">
                            <a href="index.php?page=dashboard" class="btn btn-secondary">
                                <i class="fas fa-chart-line"></i>
                                View Dashboard
                            </a>
                            <a href="#" class="btn btn-secondary">
                                <i class="fas fa-play"></i>
                                Start Session
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="welcome-content">
                        <h2>Transform Your Learning</h2>
                        <p>Join thousands of students who are achieving their academic goals with our personalized study platform.</p>
                        <div class="welcome-actions">
                            <a href="pages/register.php" class="btn btn-secondary">
                                <i class="fas fa-rocket"></i>
                                Get Started Free
                            </a>
                            <a href="pages/login.php" class="btn btn-secondary">
                                <i class="fas fa-sign-in-alt"></i>
                                Sign In
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Stats Grid -->
            <?php if ($isAuthenticated && $user): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Study Time Today</span>
                        <div class="stat-icon blue">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-value">2h 34m</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        +12% from yesterday
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Cards Reviewed</span>
                        <div class="stat-icon green">
                            <i class="fas fa-brain"></i>
                        </div>
                    </div>
                    <div class="stat-value">147</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        +28 new cards
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Current Streak</span>
                        <div class="stat-icon purple">
                            <i class="fas fa-fire"></i>
                        </div>
                    </div>
                    <div class="stat-value">12 days</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        Personal best!
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Points Earned</span>
                        <div class="stat-icon orange">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="stat-value">1,247</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        +89 today
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <section class="quick-actions" id="quick-actions">
                <div class="quick-actions-header">
                    <h3><?php echo $isAuthenticated && $user ? 'Quick Actions' : 'Get Started'; ?></h3>
                </div>
                
                <div class="actions-grid">
                    <a href="#" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="action-title">Smart Flashcards</div>
                        <div class="action-desc">AI-powered spaced repetition</div>
                    </a>
                    
                    <a href="#" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="action-title">Focus Timer</div>
                        <div class="action-desc">Pomodoro technique sessions</div>
                    </a>
                    
                    <a href="#" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="action-title">Practice Quizzes</div>
                        <div class="action-desc">Test your knowledge</div>
                    </a>
                    
                    <a href="#" class="action-item">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="action-title">Study Groups</div>
                        <div class="action-desc">Collaborate with peers</div>
                    </a>
                </div>
            </section>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileMenu.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>