<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YPT Study - Modern Learning Platform</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Orbitron', 'Exo 2', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 25%, #16213e 50%, #0f3460 75%, #0a0a0a 100%);
            color: #ffffff;
            line-height: 1.6;
            overflow-x: hidden;
            position: relative;
        }

        /* Futuristic Particle Background */
        .particle-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -2;
        }

        /* Animated Grid Background */
        .grid-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 245, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 245, 255, 0.1) 1px, transparent 1px),
                linear-gradient(rgba(255, 0, 128, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 0, 128, 0.05) 1px, transparent 1px);
            background-size: 100px 100px, 100px 100px, 20px 20px, 20px 20px;
            animation: grid-move 20s linear infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes grid-move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(100px, 100px); }
        }

        /* Holographic Overlay */
        .holographic-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(0, 255, 255, 0.1) 0%, transparent 30%),
                        radial-gradient(circle at 80% 20%, rgba(255, 0, 128, 0.1) 0%, transparent 30%),
                        radial-gradient(circle at 40% 80%, rgba(0, 255, 128, 0.1) 0%, transparent 30%),
                        radial-gradient(circle at 60% 30%, rgba(128, 0, 255, 0.1) 0%, transparent 30%);
            animation: hologram-shift 15s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes hologram-shift {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        /* Futuristic Brand Logo */
        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.8rem;
            font-weight: 900;
            color: #00f5ff;
            text-shadow: 0 0 10px #00f5ff, 0 0 20px #00f5ff;
            position: relative;
        }

        .brand i {
            font-size: 2rem;
            animation: brand-rotate 4s linear infinite;
            filter: drop-shadow(0 0 10px #00f5ff);
        }

        @keyframes brand-rotate {
            0% { transform: rotateY(0deg); }
            100% { transform: rotateY(360deg); }
        }

        .brand::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00f5ff, transparent);
            animation: brand-underline 2s ease-in-out infinite;
        }

        @keyframes brand-underline {
            0%, 100% { transform: scaleX(0); }
            50% { transform: scaleX(1); }
        }

        /* Futuristic Main Content */
        .main-content {
            min-height: 100vh;
            background: transparent;
            position: relative;
        }

        /* 3D Floating Elements */
        .floating-3d-element {
            position: absolute;
            pointer-events: none;
            animation: float-3d 6s ease-in-out infinite;
        }

        .floating-3d-element.element-1 {
            top: 20%;
            left: 10%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, rgba(0, 245, 255, 0.2), rgba(255, 0, 128, 0.2));
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            animation-delay: 0s;
        }

        .floating-3d-element.element-2 {
            top: 60%;
            right: 15%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, rgba(255, 0, 128, 0.2), rgba(0, 255, 128, 0.2));
            border-radius: 50%;
            animation-delay: 2s;
        }

        .floating-3d-element.element-3 {
            top: 40%;
            right: 5%;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, rgba(0, 255, 128, 0.2), rgba(128, 0, 255, 0.2));
            clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
            animation-delay: 4s;
        }

        @keyframes float-3d {
            0%, 100% {
                transform: translateY(0px) rotateX(0deg) rotateY(0deg);
                opacity: 0.6;
            }
            33% {
                transform: translateY(-30px) rotateX(120deg) rotateY(120deg);
                opacity: 1;
            }
            66% {
                transform: translateY(-10px) rotateX(240deg) rotateY(240deg);
                opacity: 0.8;
            }
        }

        /* Futuristic Top Header */
        .top-header {
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.9), rgba(26, 26, 46, 0.9), rgba(0, 0, 0, 0.9));
            backdrop-filter: blur(20px);
            border-bottom: 2px solid rgba(0, 245, 255, 0.3);
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            border-radius: 0 0 20px 20px;
            margin: 0 1rem;
        }

        .top-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00f5ff, #ff0080, #00f5ff, transparent);
            animation: header-glow 3s ease-in-out infinite;
        }

        @keyframes header-glow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
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

        /* Futuristic Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, 
                rgba(0, 245, 255, 0.1) 0%, 
                rgba(255, 0, 128, 0.1) 25%, 
                rgba(0, 255, 128, 0.1) 50%, 
                rgba(128, 0, 255, 0.1) 75%, 
                rgba(0, 245, 255, 0.1) 100%);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(0, 245, 255, 0.3);
            border-radius: 30px;
            padding: 4rem;
            margin: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 0 50px rgba(0, 245, 255, 0.2),
                inset 0 0 50px rgba(255, 255, 255, 0.05);
        }

        /* 3D Holographic Projections */
        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                from 0deg,
                transparent,
                rgba(0, 245, 255, 0.1),
                transparent,
                rgba(255, 0, 128, 0.1),
                transparent,
                rgba(0, 255, 128, 0.1),
                transparent
            );
            animation: hologram-rotate 20s linear infinite;
        }

        @keyframes hologram-rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* 3D Geometric Shapes */
        .welcome-section::after {
            content: '';
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, rgba(0, 245, 255, 0.3), rgba(255, 0, 128, 0.3));
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            animation: shape-float 4s ease-in-out infinite;
        }

        @keyframes shape-float {
            0%, 100% { transform: translateY(0px) rotateZ(0deg); }
            50% { transform: translateY(-20px) rotateZ(180deg); }
        }

        .welcome-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .welcome-section h2 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #00f5ff, #ff0080, #00ff80, #8000ff);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-text 3s ease infinite;
            text-shadow: 0 0 30px rgba(0, 245, 255, 0.5);
        }

        @keyframes gradient-text {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .welcome-section p {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            animation: text-glow 2s ease-in-out infinite;
        }

        @keyframes text-glow {
            0%, 100% { text-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
            50% { text-shadow: 0 0 20px rgba(0, 245, 255, 0.6); }
        }

        .welcome-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Futuristic Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(45deg, #00f5ff, #ff0080);
            color: white;
            border: 2px solid transparent;
            box-shadow: 
                0 0 20px rgba(0, 245, 255, 0.4),
                0 0 40px rgba(255, 0, 128, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 
                0 10px 30px rgba(0, 245, 255, 0.6),
                0 0 60px rgba(255, 0, 128, 0.4);
        }

        .btn-outline {
            background: rgba(0, 245, 255, 0.1);
            color: #00f5ff;
            border: 2px solid #00f5ff;
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.3);
        }

        .btn-outline:hover {
            background: rgba(0, 245, 255, 0.2);
            color: white;
            border-color: #00f5ff;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 245, 255, 0.5);
        }

        /* Holographic Button Effects */
        .btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 30px;
            padding: 2px;
            background: linear-gradient(45deg, #00f5ff, #ff0080, #00ff80, #8000ff);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn:hover::after {
            opacity: 1;
        }

        /* Futuristic Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 3rem 2rem;
            perspective: 1000px;
        }

        .stat-card {
            background: linear-gradient(135deg, 
                rgba(0, 245, 255, 0.1) 0%, 
                rgba(255, 0, 128, 0.05) 50%, 
                rgba(0, 255, 128, 0.1) 100%);
            backdrop-filter: blur(30px);
            border-radius: 25px;
            padding: 2rem;
            border: 2px solid rgba(0, 245, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                from 0deg,
                transparent,
                rgba(0, 245, 255, 0.1),
                transparent,
                rgba(255, 0, 128, 0.1),
                transparent
            );
            animation: card-glow 4s linear infinite;
            opacity: 0;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        @keyframes card-glow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stat-card:hover {
            transform: translateY(-15px) rotateX(5deg) rotateY(5deg);
            box-shadow: 
                0 20px 40px rgba(0, 245, 255, 0.3),
                0 0 60px rgba(255, 0, 128, 0.2);
            border-color: #00f5ff;
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

        /* Futuristic Features Section */
        .futuristic-features {
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            position: relative;
            overflow: hidden;
        }

        .futuristic-features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 0, 128, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(0, 255, 128, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .features-container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .features-title {
            text-align: center;
            margin-bottom: 4rem;
            font-size: 3rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .glitch-text {
            position: relative;
            color: #00f5ff;
            text-shadow: 0 0 10px #00f5ff, 0 0 20px #00f5ff, 0 0 30px #00f5ff;
            animation: glitch 2s infinite;
        }

        @keyframes glitch {
            0%, 100% { transform: translateX(0); }
            10% { transform: translateX(-2px) skew(-5deg); }
            20% { transform: translateX(2px) skew(5deg); }
            30% { transform: translateX(-1px) skew(-3deg); }
            40% { transform: translateX(1px) skew(3deg); }
            50% { transform: translateX(-2px) skew(-2deg); }
        }

        .features-3d-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            perspective: 1000px;
        }

        .feature-3d-card {
            position: relative;
            height: 220px;
            transform-style: preserve-3d;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.320, 1);
            cursor: pointer;
        }

        .feature-3d-card:hover {
            transform: rotateY(15deg) rotateX(10deg) translateZ(50px);
        }

        .card-3d-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 245, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            overflow: hidden;
            position: relative;
        }

        .card-3d-inner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                from 0deg,
                transparent,
                rgba(0, 245, 255, 0.1),
                transparent,
                rgba(255, 0, 128, 0.1),
                transparent
            );
            animation: rotate-border 4s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-3d-card:hover .card-3d-inner::before {
            opacity: 1;
        }

        @keyframes rotate-border {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .hologram-icon {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #00f5ff;
            background: radial-gradient(circle, rgba(0, 245, 255, 0.2), transparent);
            border-radius: 50%;
            animation: float-hologram 3s ease-in-out infinite;
        }

        @keyframes float-hologram {
            0%, 100% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-10px) rotateY(180deg); }
        }

        .hologram-rings {
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px solid rgba(0, 245, 255, 0.3);
            border-radius: 50%;
            animation: pulse-ring 2s ease-in-out infinite;
        }

        .hologram-rings::before,
        .hologram-rings::after {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            right: -15px;
            bottom: -15px;
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 50%;
            animation: pulse-ring 2s ease-in-out infinite;
        }

        .hologram-rings::after {
            top: -25px;
            left: -25px;
            right: -25px;
            bottom: -25px;
            border-color: rgba(255, 0, 128, 0.2);
            animation-delay: 0.5s;
        }

        @keyframes pulse-ring {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.3; }
        }

        .feature-3d-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 10px rgba(0, 245, 255, 0.5);
            position: relative;
            z-index: 3;
        }

        .feature-3d-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            position: relative;
            z-index: 3;
            margin-bottom: 1rem;
        }

        .neural-network {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            opacity: 0.6;
        }

        .node {
            width: 8px;
            height: 8px;
            background: linear-gradient(45deg, #00f5ff, #ff0080);
            border-radius: 50%;
            animation: neural-pulse 1.5s ease-in-out infinite;
        }

        .node:nth-child(2) { animation-delay: 0.3s; }
        .node:nth-child(3) { animation-delay: 0.6s; }

        @keyframes neural-pulse {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.5); opacity: 1; }
        }

        /* Tilt Effect */
        .feature-3d-card[data-tilt] {
            transform-style: preserve-3d;
        }

        /* Holographic Scanner Effect */
        .card-3d-inner::after {
            content: '';
            position: absolute;
            top: -100%;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(0, 245, 255, 0.4),
                transparent
            );
            transition: top 0.8s ease;
        }

        .feature-3d-card:hover .card-3d-inner::after {
            top: 100%;
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

            .features-3d-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .features-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .feature-3d-card {
                height: 200px;
            }

            .feature-3d-card:hover {
                transform: translateY(-10px) scale(1.02);
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
    <!-- Futuristic Background Elements -->
    <div class="grid-background"></div>
    <div class="holographic-overlay"></div>
    <canvas class="particle-canvas" id="particleCanvas"></canvas>
    
    <!-- 3D Floating Elements -->
    <div class="floating-3d-element element-1"></div>
    <div class="floating-3d-element element-2"></div>
    <div class="floating-3d-element element-3"></div>

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

            <!-- Futuristic Features Grid -->
            <section class="futuristic-features" id="features">
                <div class="features-container">
                    <h2 class="features-title">
                        <span class="glitch-text">Get Started</span>
                    </h2>
                    
                    <div class="features-3d-grid">
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-brain"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Smart Flashcards</h3>
                                <p class="feature-3d-desc">AI-powered spaced repetition</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-clock"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Focus Timer</h3>
                                <p class="feature-3d-desc">Pomodoro technique sessions</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-question-circle"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Practice Quizzes</h3>
                                <p class="feature-3d-desc">Test your knowledge</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-users"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Study Groups</h3>
                                <p class="feature-3d-desc">Collaborate with peers</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-sticky-note"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Smart Notes</h3>
                                <p class="feature-3d-desc">Advanced note-taking</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-chart-line"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Analytics</h3>
                                <p class="feature-3d-desc">Performance insights</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-trophy"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Achievements</h3>
                                <p class="feature-3d-desc">Unlock rewards</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-3d-card" data-tilt>
                            <div class="card-3d-inner">
                                <div class="hologram-icon">
                                    <i class="fas fa-calendar"></i>
                                    <div class="hologram-rings"></div>
                                </div>
                                <h3 class="feature-3d-title">Daily Review</h3>
                                <p class="feature-3d-desc">Track progress daily</p>
                                <div class="neural-network">
                                    <div class="node"></div>
                                    <div class="node"></div>
                                    <div class="node"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        // Advanced Particle System
        class ParticleSystem {
            constructor(canvas) {
                this.canvas = canvas;
                this.ctx = canvas.getContext('2d');
                this.particles = [];
                this.mouse = { x: 0, y: 0 };
                
                this.resizeCanvas();
                this.createParticles();
                this.animate();
                
                window.addEventListener('resize', () => this.resizeCanvas());
                document.addEventListener('mousemove', (e) => {
                    this.mouse.x = e.clientX;
                    this.mouse.y = e.clientY;
                });
            }
            
            resizeCanvas() {
                this.canvas.width = window.innerWidth;
                this.canvas.height = window.innerHeight;
            }
            
            createParticles() {
                const particleCount = Math.floor((this.canvas.width * this.canvas.height) / 15000);
                
                for (let i = 0; i < particleCount; i++) {
                    this.particles.push({
                        x: Math.random() * this.canvas.width,
                        y: Math.random() * this.canvas.height,
                        vx: (Math.random() - 0.5) * 0.5,
                        vy: (Math.random() - 0.5) * 0.5,
                        size: Math.random() * 2 + 1,
                        color: `hsl(${180 + Math.random() * 60}, 100%, 50%)`,
                        opacity: Math.random() * 0.5 + 0.2,
                        life: Math.random() * 100 + 100
                    });
                }
            }
            
            animate() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                
                this.particles.forEach((particle, index) => {
                    // Update position
                    particle.x += particle.vx;
                    particle.y += particle.vy;
                    
                    // Mouse interaction
                    const dx = this.mouse.x - particle.x;
                    const dy = this.mouse.y - particle.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < 100) {
                        particle.vx += dx * 0.0001;
                        particle.vy += dy * 0.0001;
                    }
                    
                    // Boundary check
                    if (particle.x < 0 || particle.x > this.canvas.width) particle.vx *= -1;
                    if (particle.y < 0 || particle.y > this.canvas.height) particle.vy *= -1;
                    
                    // Draw particle
                    this.ctx.save();
                    this.ctx.globalAlpha = particle.opacity;
                    this.ctx.fillStyle = particle.color;
                    this.ctx.shadowBlur = 10;
                    this.ctx.shadowColor = particle.color;
                    this.ctx.beginPath();
                    this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                    this.ctx.fill();
                    this.ctx.restore();
                    
                    // Connect nearby particles
                    this.particles.slice(index + 1).forEach(otherParticle => {
                        const dx = particle.x - otherParticle.x;
                        const dy = particle.y - otherParticle.y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        if (distance < 80) {
                            this.ctx.save();
                            this.ctx.globalAlpha = (80 - distance) / 80 * 0.2;
                            this.ctx.strokeStyle = '#00f5ff';
                            this.ctx.lineWidth = 0.5;
                            this.ctx.beginPath();
                            this.ctx.moveTo(particle.x, particle.y);
                            this.ctx.lineTo(otherParticle.x, otherParticle.y);
                            this.ctx.stroke();
                            this.ctx.restore();
                        }
                    });
                });
                
                requestAnimationFrame(() => this.animate());
            }
        }
        
        // Initialize particle system
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('particleCanvas');
            if (canvas) {
                new ParticleSystem(canvas);
            }
        });

        // Enhanced 3D Tilt Effect for all cards
        document.querySelectorAll('.stat-card, [data-tilt]').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 8;
                const rotateY = (centerX - x) / 8;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
            });
        });

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

        // Staggered entrance animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0) rotateX(0deg)';
                    }, index * 150);
                }
            });
        }, observerOptions);

        // Initialize entrance animations
        document.querySelectorAll('.feature-3d-card, .stat-card').forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(50px) rotateX(-15deg)';
            element.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(element);
        });

        // Dynamic color shifting for elements
        setInterval(() => {
            const elements = document.querySelectorAll('.floating-3d-element');
            elements.forEach(element => {
                const hue = Math.random() * 360;
                element.style.filter = `hue-rotate(${hue}deg)`;
            });
        }, 3000);

        // Header interaction effects
        const header = document.querySelector('.top-header');
        if (header) {
            document.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                header.style.transform = `translateY(${scrolled * 0.1}px)`;
                header.style.opacity = Math.max(0.8, 1 - scrolled / 500);
            });
        }
    </script>
</body>
</html>