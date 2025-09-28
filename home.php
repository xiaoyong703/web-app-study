<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YPT Study - Online Education Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Light Mode Colors */
            --primary-orange: #FF6B35;
            --secondary-orange: #FF8A65;
            --light-orange: #FFF3E0;
            --dark-blue: #1E3A8A;
            --navy-blue: #0F172A;
            --light-gray: #F8FAFC;
            --medium-gray: #64748B;
            --dark-gray: #334155;
            --white: #FFFFFF;
            --text-primary: #0F172A;
            --text-secondary: #64748B;
            --border-light: #E2E8F0;
            
            /* Current theme variables */
            --bg-primary: var(--light-gray);
            --bg-secondary: var(--white);
            --text-main: var(--text-primary);
            --text-sub: var(--text-secondary);
            --border-color: var(--border-light);
        }

        [data-theme="dark"] {
            --bg-primary: #0F172A;
            --bg-secondary: #1E293B;
            --text-main: #F1F5F9;
            --text-sub: #CBD5E1;
            --border-color: #334155;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            line-height: 1.6;
            transition: all 0.3s ease;
        }

        /* Header */
        .header {
            background-color: var(--bg-secondary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: between;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-orange);
            text-decoration: none;
        }

        .logo i {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            align-items: center;
            margin-left: auto;
            gap: 30px;
        }

        .nav-menu li a {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-menu li a:hover {
            color: var(--primary-orange);
        }

        .nav-menu li.dropdown {
            position: relative;
        }

        .nav-menu li.dropdown:after {
            content: '▼';
            font-size: 12px;
            margin-left: 5px;
        }

        .theme-toggle {
            background: none;
            border: 2px solid var(--border-color);
            border-radius: 25px;
            padding: 8px 12px;
            cursor: pointer;
            color: var(--text-main);
            transition: all 0.3s ease;
            margin-left: 20px;
        }

        .theme-toggle:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
        }

        .get-quote-btn {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-left: 20px;
        }

        .get-quote-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        /* Hero Section */
        .hero-section {
            margin-top: 70px;
            padding: 80px 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            min-height: calc(100vh - 70px);
        }

        .hero-content {
            padding-right: 40px;
        }

        .hero-badge {
            background-color: var(--light-orange);
            color: var(--primary-orange);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        [data-theme="dark"] .hero-badge {
            background-color: rgba(255, 107, 53, 0.2);
            color: var(--secondary-orange);
        }

        .hero-title {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            color: var(--dark-blue);
        }

        [data-theme="dark"] .hero-title {
            color: var(--text-main);
        }

        .hero-description {
            font-size: 18px;
            color: var(--text-sub);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(255, 107, 53, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-main);
            border: 2px solid var(--border-color);
            padding: 14px 30px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            border-color: var(--primary-orange);
            color: var(--primary-orange);
            transform: translateY(-2px);
        }

        /* Hero Illustration */
        .hero-illustration {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .illustration-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            height: 400px;
        }

        /* Book Stack */
        .book-stack {
            position: absolute;
            bottom: 50px;
            left: 50px;
            z-index: 3;
        }

        .book {
            width: 120px;
            height: 15px;
            border-radius: 3px;
            margin-bottom: 3px;
            position: relative;
        }

        .book:nth-child(1) { background: #FF6B35; }
        .book:nth-child(2) { background: #FFB74D; }
        .book:nth-child(3) { background: #FF8A65; }
        .book:nth-child(4) { background: #FFA726; }

        /* Light Bulb */
        .lightbulb {
            position: absolute;
            top: 20px;
            left: 80px;
            width: 60px;
            height: 80px;
            z-index: 4;
        }

        .bulb-glass {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFD54F, #FFC107);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            position: relative;
            box-shadow: 0 0 20px rgba(255, 193, 7, 0.6);
            animation: glow 2s ease-in-out infinite alternate;
        }

        .bulb-base {
            width: 40px;
            height: 20px;
            background: #666;
            border-radius: 0 0 20px 20px;
            margin: 0 auto;
        }

        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(255, 193, 7, 0.6); }
            to { box-shadow: 0 0 30px rgba(255, 193, 7, 0.9); }
        }

        /* Laptop */
        .laptop {
            position: absolute;
            top: 120px;
            right: 50px;
            width: 200px;
            height: 130px;
            z-index: 2;
        }

        .laptop-screen {
            width: 200px;
            height: 120px;
            background: linear-gradient(135deg, #2196F3, #21CBF3);
            border-radius: 10px 10px 2px 2px;
            position: relative;
            border: 8px solid #333;
        }

        .laptop-base {
            width: 220px;
            height: 10px;
            background: #444;
            border-radius: 10px;
            margin: 0 auto;
        }

        /* Trophy */
        .trophy {
            position: absolute;
            top: 60px;
            right: 120px;
            width: 50px;
            height: 60px;
            z-index: 3;
        }

        .trophy-cup {
            width: 40px;
            height: 35px;
            background: linear-gradient(135deg, #FFD700, #FFA000);
            border-radius: 20px 20px 5px 5px;
            margin: 0 auto;
            position: relative;
        }

        .trophy-cup:before {
            content: '';
            position: absolute;
            top: 8px;
            left: -8px;
            width: 15px;
            height: 20px;
            border: 3px solid #FFD700;
            border-right: none;
            border-radius: 15px 0 0 15px;
        }

        .trophy-cup:after {
            content: '';
            position: absolute;
            top: 8px;
            right: -8px;
            width: 15px;
            height: 20px;
            border: 3px solid #FFD700;
            border-left: none;
            border-radius: 0 15px 15px 0;
        }

        .trophy-base {
            width: 50px;
            height: 15px;
            background: #B8860B;
            border-radius: 5px;
            margin-top: 5px;
        }

        /* Ruler */
        .ruler {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 150px;
            height: 15px;
            background: #FF5722;
            border-radius: 8px;
            transform: rotate(-15deg);
            z-index: 1;
        }

        .ruler:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                90deg,
                transparent,
                transparent 8px,
                rgba(255, 255, 255, 0.3) 8px,
                rgba(255, 255, 255, 0.3) 10px
            );
        }

        /* Floating Elements Animation */
        .floating {
            animation: float 3s ease-in-out infinite;
        }

        .floating:nth-child(2) {
            animation-delay: 0.5s;
        }

        .floating:nth-child(3) {
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .hero-section {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }
            
            .hero-content {
                padding-right: 0;
            }
            
            .hero-title {
                font-size: 36px;
            }
            
            .hero-buttons {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        /* Theme transition */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="fas fa-graduation-cap"></i>
                YPT Study
            </a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#courses">Courses</a></li>
                    <li><a href="#blog">Blog</a></li>
                    <li class="dropdown"><a href="#pages">Pages</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
            
            <div style="display: flex; align-items: center;">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
                <a href="#quote" class="get-quote-btn">Get A Quote</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-badge">EVERY CHILD YEARNS TO LEARN</div>
            <h1 class="hero-title">Making Your Childs World Better</h1>
            <p class="hero-description">
                Replenish seasons may male hath fruit beast were seas saw you ariel said man beast whales his void unto last session for bite. Set have great youll male grass yielding yielding man.
            </p>
            <div class="hero-buttons">
                <a href="#courses" class="btn-primary">View Courses</a>
                <a href="#started" class="btn-secondary">Get Started</a>
            </div>
        </div>
        
        <div class="hero-illustration">
            <div class="illustration-container">
                <!-- Book Stack -->
                <div class="book-stack floating">
                    <div class="book"></div>
                    <div class="book"></div>
                    <div class="book"></div>
                    <div class="book"></div>
                </div>
                
                <!-- Light Bulb -->
                <div class="lightbulb floating">
                    <div class="bulb-glass"></div>
                    <div class="bulb-base"></div>
                </div>
                
                <!-- Laptop -->
                <div class="laptop floating">
                    <div class="laptop-screen"></div>
                    <div class="laptop-base"></div>
                </div>
                
                <!-- Trophy -->
                <div class="trophy floating">
                    <div class="trophy-cup"></div>
                    <div class="trophy-base"></div>
                </div>
                
                <!-- Ruler -->
                <div class="ruler floating"></div>
            </div>
        </div>
    </section>

    <script>
        // Theme Toggle Functionality
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            const currentTheme = html.getAttribute('data-theme');
            
            if (currentTheme === 'light') {
                html.setAttribute('data-theme', 'dark');
                themeIcon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            } else {
                html.setAttribute('data-theme', 'light');
                themeIcon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            }
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            
            html.setAttribute('data-theme', savedTheme);
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });

        // Smooth scrolling for navigation links
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