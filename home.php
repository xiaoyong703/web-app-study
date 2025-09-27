<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YPT Study - Next-Gen Learning Platform</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #ffffff;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Futuristic Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: linear-gradient(45deg, #00f5ff, #0099ff);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        .grid-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 245, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: -1;
        }

        /* Glassmorphism Header */
        .header {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .nav-brand h2 {
            background: linear-gradient(45deg, #00f5ff, #0099ff, #ff0080);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 3s ease infinite;
            font-weight: 800;
            font-size: 1.8rem;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Futuristic Buttons */
        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-outline {
            background: transparent;
            color: #00f5ff;
            border: 2px solid #00f5ff;
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.3);
        }

        .btn-outline:hover {
            background: #00f5ff;
            color: #0a0a0a;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 245, 255, 0.4);
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff0080, #7928ca);
            color: white;
            border: 2px solid transparent;
            box-shadow: 0 0 20px rgba(255, 0, 128, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 0, 128, 0.4);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 2rem;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            z-index: 2;
        }

        .hero h1 {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #ffffff, #00f5ff, #ff0080);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 4s ease infinite;
            line-height: 1.1;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 3rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 300;
        }

        .hero-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn-hero {
            padding: 18px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            min-width: 200px;
        }

        /* Welcome Message */
        .welcome-banner {
            background: linear-gradient(135deg, rgba(0, 245, 255, 0.1), rgba(255, 0, 128, 0.1));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-banner h3 {
            margin: 0 0 1rem 0;
            font-size: 1.8rem;
            color: #00f5ff;
        }

        /* Features Section */
        .features {
            padding: 8rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .features h2 {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 4rem;
            background: linear-gradient(45deg, #ffffff, #00f5ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(0, 245, 255, 0.1), transparent);
            animation: rotate 4s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 245, 255, 0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #00f5ff, #ff0080);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #ffffff;
            position: relative;
            z-index: 2;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        /* CTA Section */
        .cta {
            padding: 6rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 245, 255, 0.1), rgba(255, 0, 128, 0.1));
        }

        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .cta p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Footer */
        .footer {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
            padding: 3rem 2rem 2rem;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer p {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .auth-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-hero {
                width: 100%;
                max-width: 300px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #00f5ff, #ff0080);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #ff0080, #00f5ff);
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="grid-overlay"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <h2>⚡ YPT Study</h2>
            </div>
            <div class="auth-buttons">
                <?php if ($isAuthenticated && $user): ?>
                    <span style="color: #00f5ff; margin-right: 1rem; font-weight: 500;">Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</span>
                    <a href="index.php?page=dashboard" class="btn btn-primary">Launch Dashboard</a>
                    <a href="api/auth/logout.php" class="btn btn-outline">Sign Out</a>
                <?php else: ?>
                    <a href="pages/login.php" class="btn btn-outline">Sign In</a>
                    <a href="pages/register.php" class="btn btn-primary">Get Started</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <?php if (isset($_GET['welcome']) && $isAuthenticated && $user): ?>
                <div class="welcome-banner">
                    <h3>🚀 Welcome to the Future of Learning!</h3>
                    <p>You're all set, <?php echo htmlspecialchars($user['first_name']); ?>! Ready to unlock your potential with next-gen study tools?</p>
                </div>
            <?php endif; ?>
            
            <h1><?php echo $isAuthenticated && $user ? "Welcome Back, " . htmlspecialchars($user['first_name']) . "!" : "The Future of Learning is Here"; ?></h1>
            <p><?php echo $isAuthenticated && $user ? "Your personalized AI-powered study experience awaits. Access cutting-edge tools and analytics." : "Experience next-generation learning with AI-powered study tools, immersive environments, and personalized analytics."; ?></p>
            
            <div class="hero-buttons">
                <?php if ($isAuthenticated && $user): ?>
                    <a href="index.php?page=dashboard" class="btn btn-primary btn-hero">
                        <i class="fas fa-rocket"></i> Launch Dashboard
                    </a>
                    <a href="#features" class="btn btn-outline btn-hero">
                        <i class="fas fa-star"></i> Explore Features
                    </a>
                <?php else: ?>
                    <a href="pages/register.php" class="btn btn-primary btn-hero">
                        <i class="fas fa-rocket"></i> Start Your Journey
                    </a>
                    <a href="pages/login.php" class="btn btn-outline btn-hero">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2>Next-Gen Learning Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3>AI-Powered Analytics</h3>
                <p>Advanced machine learning algorithms analyze your study patterns and provide personalized insights to optimize your learning efficiency.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-vr-cardboard"></i>
                </div>
                <h3>Immersive Study Environment</h3>
                <p>Focus-enhancing virtual environments with binaural audio, ambient lighting effects, and distraction-free interfaces.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Real-Time Progress Tracking</h3>
                <p>Live performance metrics, streak tracking, and achievement systems that gamify your learning experience.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Collaborative Learning</h3>
                <p>Connect with study groups, share notes, participate in real-time discussions, and learn from peers globally.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Smart Flashcards</h3>
                <p>AI-generated flashcards with spaced repetition algorithms that adapt to your memory patterns and learning speed.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Achievement System</h3>
                <p>Unlock badges, level up your profile, and compete in leaderboards while building consistent study habits.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>Ready to Transform Your Learning?</h2>
        <p>Join thousands of students already experiencing the future of education</p>
        <?php if (!$isAuthenticated || !$user): ?>
            <a href="pages/register.php" class="btn btn-primary btn-hero">
                <i class="fas fa-rocket"></i> Get Started Free
            </a>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 YPT Study App. Powered by next-generation learning technology.</p>
    </footer>

    <script>
        // Create floating particles
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            const size = Math.random() * 4 + 2;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            particle.style.top = Math.random() * window.innerHeight + 'px';
            particle.style.animationDelay = Math.random() * 6 + 's';
            particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
            
            document.querySelector('.bg-animation').appendChild(particle);
            
            // Remove particle after animation
            setTimeout(() => {
                if (particle.parentNode) {
                    particle.parentNode.removeChild(particle);
                }
            }, 10000);
        }

        // Create particles periodically
        setInterval(createParticle, 500);

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(10, 10, 10, 0.95)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.05)';
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add entrance animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(50px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>