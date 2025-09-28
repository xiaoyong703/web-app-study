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

        /* Brand Logo */
        .brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Main Content */
        .main-content {
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Top Header */
        .top-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
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

        /* Features Showcase (Before Sign In) */
        .features-showcase {
            margin-bottom: 3rem;
        }

        .features-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .features-header h2 {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .features-header p {
            font-size: 1.125rem;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.05), transparent);
            transition: left 0.6s ease;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .feature-benefits {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .benefit {
            font-size: 0.875rem;
            color: #16a34a;
            font-weight: 500;
        }

        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1rem;
            padding: 3rem;
            text-align: center;
            color: white;
        }

        .cta-section h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-buttons .btn {
            padding: 1rem 2rem;
            font-size: 1rem;
            min-width: 180px;
        }

        .cta-buttons .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .cta-buttons .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .cta-buttons .btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: white;
        }

        .cta-buttons .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

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
            .header-left {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-nav {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .top-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-buttons .btn {
                width: 100%;
                max-width: 300px;
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


    </style>
</head>
<body>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <div class="brand">
                    <i class="fas fa-graduation-cap"></i> YPT Study
                </div>
                
                <?php if ($isAuthenticated && $user): ?>
                <div class="header-nav">
                    <a href="index.php?page=dashboard" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="header-actions">
                <?php if ($isAuthenticated && $user): ?>
                    <div class="user-info">
                        <span><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1)); ?>
                        </div>
                    </div>
                    <a href="api/auth/logout.php" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i>
                        Sign Out
                    </a>
                <?php else: ?>
                    <a href="pages/login.php" class="btn btn-outline">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </a>
                    <a href="pages/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Get Started
                    </a>
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
                    </div>
                <?php elseif ($isAuthenticated && $user): ?>
                    <div class="welcome-content">
                        <h2>Ready to Study, <?php echo htmlspecialchars($user['first_name']); ?>?</h2>
                        <p>Continue where you left off or start a new study session. Your progress is waiting!</p>
                    </div>
                <?php else: ?>
                    <div class="welcome-content">
                        <h2>Transform Your Learning</h2>
                        <p>Join thousands of students who are achieving their academic goals with our personalized study platform.</p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Stats Grid (After Sign In) -->
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
            
            <!-- Features Showcase (After Sign In) -->
            <section class="features-showcase">
                <div class="features-header">
                    <h2>Your Study Tools</h2>
                    <p>Everything you need to excel in your studies, all in one platform</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h3>Smart Flashcards</h3>
                        <p>AI-powered spaced repetition system that adapts to your learning pace. Create, study, and master any subject with intelligent review scheduling.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Spaced Repetition Algorithm</span>
                            <span class="benefit">• Progress Tracking</span>
                            <span class="benefit">• Custom Categories</span>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3>Interactive Quizzes</h3>
                        <p>Test your knowledge with customizable quizzes. Multiple choice, true/false, and open-ended questions with instant feedback and explanations.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Multiple Question Types</span>
                            <span class="benefit">• Instant Feedback</span>
                            <span class="benefit">• Performance Analytics</span>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon purple">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Focus Timer</h3>
                        <p>Boost productivity with Pomodoro technique sessions. Track study time, set goals, and maintain focus with ambient sounds and notifications.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Pomodoro Technique</span>
                            <span class="benefit">• Ambient Sounds</span>
                            <span class="benefit">• Session Analytics</span>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon orange">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <h3>Smart Notes</h3>
                        <p>Organize your thoughts with rich-text notes. Add images, links, and formatting. Search across all notes and sync across devices.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Rich Text Editor</span>
                            <span class="benefit">• File Attachments</span>
                            <span class="benefit">• Advanced Search</span>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Study Groups</h3>
                        <p>Collaborate with classmates and study partners. Share resources, discuss topics, and learn together in real-time chat environments.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Real-time Chat</span>
                            <span class="benefit">• Resource Sharing</span>
                            <span class="benefit">• Group Progress</span>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon green">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Progress Analytics</h3>
                        <p>Detailed insights into your learning patterns. Track study time, identify strengths and weaknesses, and optimize your study strategy.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Performance Insights</span>
                            <span class="benefit">• Study Patterns</span>
                            <span class="benefit">• Goal Tracking</span>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon purple">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>Achievements</h3>
                        <p>Stay motivated with badges, streaks, and leaderboards. Unlock achievements as you reach study milestones and build consistent habits.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Badge System</span>
                            <span class="benefit">• Study Streaks</span>
                            <span class="benefit">• Leaderboards</span>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon orange">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <h3>Daily Review</h3>
                        <p>Reflect on your daily progress with guided review sessions. Set goals, track completion, and build sustainable study habits.</p>
                        <div class="feature-benefits">
                            <span class="benefit">• Daily Goals</span>
                            <span class="benefit">• Progress Reflection</span>
                            <span class="benefit">• Habit Building</span>
                        </div>
                    </div>
                </div>
            </section>
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
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>