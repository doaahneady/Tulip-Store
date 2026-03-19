<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم مشرف التوصيل - Tulip Store</title>
      <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            /* Futuristic Color Palette */
            --primary: #00d4ff;
            --primary-dark: #0099cc;
            --secondary: #ff6b35;
            --accent: #7c3aed;
            --success: #00ff88;
            --warning: #ffaa00;
            --danger: #ff3366;
            --info: #3b82f6;
            
            /* Neon Gradients */
            --gradient-cyber: linear-gradient(135deg, #00d4ff 0%, #7c3aed 50%, #ff6b35 100%);
            --gradient-neon: linear-gradient(135deg, #ff6b35 0%, #00ff88 100%);
            --gradient-electric: linear-gradient(135deg, #7c3aed 0%, #00d4ff 100%);
            --gradient-plasma: linear-gradient(135deg, #ff3366 0%, #ffaa00 100%);
            
            /* Dark Theme */
            --bg-primary: #0a0a0f;
            --bg-secondary: #1a1a2e;
            --bg-tertiary: #16213e;
            --bg-card: rgba(26, 26, 46, 0.8);
            --text-primary: #ffffff;
            --text-secondary: #b8bcc8;
            --text-muted: #6b7280;
            
            /* Glass Effects */
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --neon-glow: 0 0 20px rgba(0, 212, 255, 0.3);
            
            /* Spacing */
            --border-radius: 20px;
            --border-radius-lg: 28px;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family:  'El Messiri', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Animated Cyber Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(0, 212, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(124, 58, 237, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(255, 107, 53, 0.08) 0%, transparent 50%);
            animation: cyberShift 15s ease-in-out infinite;
            pointer-events: none;
            z-index: -2;
        }
        
        @keyframes cyberShift {
            0%, 100% { 
                background: 
                    radial-gradient(circle at 20% 20%, rgba(0, 212, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(124, 58, 237, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 60%, rgba(255, 107, 53, 0.08) 0%, transparent 50%);
            }
            50% { 
                background: 
                    radial-gradient(circle at 80% 20%, rgba(124, 58, 237, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 60% 40%, rgba(0, 212, 255, 0.08) 0%, transparent 50%);
            }
        }
        
        /* Cyber Grid Lines */
        .cyber-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
            pointer-events: none;
            z-index: -1;
        }
        
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        /* Container */
        .dashboard-container {
            padding: 2rem;
            max-width: 1920px;
            margin: 0 auto;
            position: relative;
        }
        
        /* Futuristic Header */
        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(30px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius-lg);
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                var(--neon-glow);
            position: relative;
            overflow: hidden;
        }
        
        efore {
            content: '';
            posit
            top: 0;
            left: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% { 
                transform: translateY(-100px) scale(1);
                opacity: 0;
            }
        }
        
        /* Ultra Modern Container */
        .dashboard-wrapper {
            padding: 2rem;
            max-width: 1800px;
            margin: 0 auto;
            position: relative;
        }
        
        /* Ultra Modern Header */
        .header {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--border-radius-lg);
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: 
                var(--shadow-xl),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
            opacity: 0.8;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .header-icon {
            width: 90px;
            height: 90px;
            background: var(--gradient-primary);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: white;
            box-shadow: 
                0 12px 32px rgba(99, 102, 241, 0.4),
                inset 0 2px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            animation: iconPulse 3s ease-in-out infinite;
        }
        
        .header-icon::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: var(--gradient-primary);
            border-radius: 25px;
            z-index: -1;
            opacity: 0.3;
            filter: blur(12px);
            animation: iconGlow 3s ease-in-out infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes iconGlow {
            0%, 100% { opacity: 0.3; filter: blur(12px); }
            50% { opacity: 0.5; filter: blur(16px); }
        }
        
        .header-text h1 {
            font-size: 2.8rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-bottom: 0.8rem;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header-text p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn {
            padding: 1.2rem 2.5rem;
            border: none;
            border-radius: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s ease;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: var(--gradient-secondary);
            color: white;
            box-shadow: 0 8px 32px rgba(236, 72, 153, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 16px 48px rgba(236, 72, 153, 0.6);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 32px rgba(255, 255, 255, 0.1);
        }
        
        /* Ultra Modern Stats Grid */
        .stats-container {
            margin-bottom: 2.5rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }
        
        .stat-card {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.5s ease;
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.5s ease;
            pointer-events: none;
        }
        
        .stat-card:hover::before {
            transform: scaleX(1);
        }
        
        .stat-card:hover::after {
            width: 300px;
            height: 300px;
        }
        
        .stat-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: var(--shadow-xl);
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.15) 0%, 
                rgba(255, 255, 255, 0.08) 100%);
        }
        
        .stat-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-info h3 {
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.8rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        
        .stat-info p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            position: relative;
            animation: statIconFloat 4s ease-in-out infinite;
        }
        
        .stat-icon::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: inherit;
            border-radius: 23px;
            z-index: -1;
            opacity: 0.4;
            filter: blur(12px);
            animation: statIconGlow 4s ease-in-out infinite;
        }
        
        @keyframes statIconFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        
        @keyframes statIconGlow {
            0%, 100% { opacity: 0.4; filter: blur(12px); }
            50% { opacity: 0.6; filter: blur(16px); }
        }
        
        /* Ultra Modern Main Content Layout */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 450px;
            gap: 2.5rem;
            height: 700px;
        }
        
        /* Ultra Modern Map Section */
        .map-section {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .map-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-accent);
            opacity: 0.8;
        }
        
        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .map-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .map-title i {
            color: #06b6d4;
            font-size: 1.4rem;
            animation: mapIconPulse 2s ease-in-out infinite;
        }
        
        @keyframes mapIconPulse {
            0%, 100% { transform: scale(1); color: #06b6d4; }
            50% { transform: scale(1.1); color: #0891b2; }
        }
        
        .map-controls {
            display: flex;
            gap: 0.8rem;
        }
        
        .control-btn {
            padding: 0.8rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 0.9rem;
            backdrop-filter: blur(20px);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.1);
        }
        
        .control-btn.active {
            background: var(--gradient-accent);
            color: white;
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
            transform: translateY(-2px);
        }
        
        #map {
            flex: 1;
            border-radius: 18px;
            box-shadow: 
                var(--shadow-lg),
                inset 0 2px 0 rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }
        
        /* Ultra Modern Drivers Panel */
        .drivers-panel {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .drivers-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-secondary);
            opacity: 0.8;
        }
        
        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .panel-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .panel-title i {
            color: #ec4899;
            font-size: 1.4rem;
            animation: panelIconSpin 3s linear infinite;
        }
        
        @keyframes panelIconSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .drivers-count {
            background: var(--gradient-accent);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: countPulse 2s ease-in-out infinite;
        }
        
        @keyframes countPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .drivers-list {
            flex: 1;
            overflow-y: auto;
            padding-right: 0.5rem;
        }
        
        /* Custom Elegant Scrollbar */
        .drivers-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .drivers-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        
        .drivers-list::-webkit-scrollbar-thumb {
            background: var(--accent-gradient);
            border-radius: 3px;
        }
        
        .drivers-list::-webkit-scrollbar-thumb:hover {
            background: var(--primary-gradient);
        }
        
        /* Ultra Modern Driver Cards */
        .driver-card {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 18px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .driver-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 5px;
            height: 100%;
            background: var(--gradient-primary);
            transform: scaleY(0);
            transition: transform 0.4s ease;
        }
        
        .driver-card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.5s ease;
            pointer-events: none;
        }
        
        .driver-card:hover::before {
            transform: scaleY(1);
        }
        
        .driver-card:hover::after {
            width: 200px;
            height: 200px;
        }
        
        .driver-card:hover {
            transform: translateX(-12px) scale(1.02);
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.15) 0%, 
                rgba(255, 255, 255, 0.08) 100%);
            box-shadow: var(--shadow-xl);
        }
        
        .driver-card.available { 
            border-left: 4px solid #10b981; 
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
        }
        .driver-card.busy { 
            border-left: 4px solid #f59e0b; 
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);
        }
        .driver-card.offline { 
            border-left: 4px solid #ef4444; 
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.2);
        }
        
        .driver-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .driver-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .driver-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.4rem;
            box-shadow: 
                0 8px 24px rgba(99, 102, 241, 0.4),
                inset 0 2px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            animation: avatarFloat 3s ease-in-out infinite;
        }
        
        .driver-avatar::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: var(--gradient-primary);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.4;
            filter: blur(10px);
            animation: avatarGlow 3s ease-in-out infinite;
        }
        
        @keyframes avatarFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        
        @keyframes avatarGlow {
            0%, 100% { opacity: 0.4; filter: blur(10px); }
            50% { opacity: 0.6; filter: blur(14px); }
        }
        
        .driver-details h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.3rem;
        }
        
        .driver-details p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
        }
        
        .driver-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .driver-status.available { 
            background: var(--success-gradient);
            color: white;
        }
        
        .driver-status.busy { 
            background: var(--warning-gradient);
            color: white;
        }
        
        .driver-status.offline { 
            background: var(--danger-gradient);
            color: white;
        }
        
        .driver-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
            margin-top: 1rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .meta-item i {
            color: #4facfe;
            width: 16px;
        }
        
        /* Loading and Empty States */
        .loading, .no-data {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top: 3px solid #4facfe;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .no-data i {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 1rem;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
                height: auto;
            }
            
            .drivers-panel {
                height: 400px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-wrapper {
                padding: 1rem;
            }
            
            .header {
                padding: 2rem;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .header-text h1 {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .main-content {
                gap: 1rem;
            }
            
            .map-section,
            .drivers-panel {
                padding: 1.5rem;
            }
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .auto-rows-fr {
            grid-auto-rows: 1fr;
            align-items: stretch;
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .mb-8 {
            margin-bottom: 2rem;
        }

        .h-full {
            height: 100%;
        }

        @media (min-width: 640px) {
            .sm\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .xl\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @supports not (display: grid) {
            .grid {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .grid > * {
                flex: 1 1 320px;
                min-width: 260px;
            }

            @supports not (gap: 1rem) {
                .grid {
                    margin: -0.75rem;
                }

                .grid > * {
                    margin: 0.75rem;
                }
            }
        }
    </style>
</head>
<body>
    <!-- Geometric Background -->
    <div class="geometric-bg">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="dashboard-wrapper">
        <!-- Elegant Header -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <div class="header-text">
                        <h1>لوحة تحكم مشرف التوصيل</h1>
                        <p>إدارة وتتبع السائقين والطلبات بأناقة وفعالية</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="/driver-supervisor/orders" class="btn btn-primary">
                        <i class="fas fa-clipboard-list"></i>
                        إدارة الطلبات
                    </a>
                    <button class="btn btn-secondary" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i>
                        تحديث
                    </button>
                </div>
            </div>
        </div>

        <div class="stats-container">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 auto-rows-fr gap-6 mb-8">
                @include('components.dashboard.stat-card', ['title' => 'إجمالي السائقين', 'value' => $totalDrivers, 'icon' => 'fas fa-users', 'color' => 'primary'])
                @include('components.dashboard.stat-card', ['title' => 'سائقين متاحين', 'value' => $availableDrivers, 'icon' => 'fas fa-check-circle', 'color' => 'green'])
                @include('components.dashboard.stat-card', ['title' => 'سائقين مشغولين', 'value' => $busyDrivers, 'icon' => 'fas fa-shipping-fast', 'color' => 'orange'])
                @include('components.dashboard.stat-card', ['title' => 'مكتمل اليوم', 'value' => $completedToday, 'icon' => 'fas fa-check-double', 'color' => 'purple'])
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Elegant Map Section -->
            <div class="map-section">
                <div class="map-header">
                    <div class="map-title">
                        <i class="fas fa-map-marked-alt"></i>
                        خريطة السائقين المباشرة
                    </div>
                    <div class="map-controls">
                        <button class="control-btn active" onclick="filterDrivers('all')">الكل</button>
                        <button class="control-btn" onclick="filterDrivers('available')">متاح</button>
                        <button class="control-btn" onclick="filterDrivers('busy')">مشغول</button>
                        <button class="control-btn" onclick="refreshLocations()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div id="map"></div>
            </div>

            <!-- Elegant Drivers Panel -->
            <div class="drivers-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-list"></i>
                        السائقين النشطين
                    </div>
                    <div class="drivers-count" id="drivers-count">0</div>
                </div>
                <div class="drivers-list" id="drivers-list">
                    <div class="loading">
                        <div class="spinner"></div>
                        <p>جاري تحميل بيانات السائقين...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        let driverMarkers = {};
        let driversData = [];
        let currentFilter = 'all';

        // Initialize map
        function initMap() {
            map = L.map('map').setView([32.7125, 36.5669], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
        }

        // Elegant driver icons
        function createDriverIcon(status) {
            const config = {
                available: { 
                    color: '#43e97b', 
                    icon: 'fas fa-motorcycle', 
                    shadow: '0 0 20px rgba(67, 233, 123, 0.6)',
                    pulse: true
                },
                busy: { 
                    color: '#fee140', 
                    icon: 'fas fa-shipping-fast', 
                    shadow: '0 0 20px rgba(254, 225, 64, 0.6)',
                    pulse: true
                },
                offline: { 
                    color: '#ff9a9e', 
                    icon: 'fas fa-ban', 
                    shadow: '0 0 20px rgba(255, 154, 158, 0.6)',
                    pulse: false
                },
                on_break: { 
                    color: '#4facfe', 
                    icon: 'fas fa-coffee', 
                    shadow: '0 0 20px rgba(79, 172, 254, 0.6)',
                    pulse: false
                }
            };
            
            const statusConfig = config[status] || config.offline;
            const pulseClass = statusConfig.pulse ? 'driver-marker-pulse' : '';
            
            return L.divIcon({
                className: 'elegant-driver-marker',
                html: `
                    <div class="marker-container ${pulseClass}" style="
                        background: ${statusConfig.color}; 
                        border: 3px solid white; 
                        border-radius: 50%; 
                        width: 48px; 
                        height: 48px; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center; 
                        font-size: 1.1rem; 
                        color: white; 
                        box-shadow: ${statusConfig.shadow}, 0 4px 16px rgba(0,0,0,0.2); 
                        font-weight: 700;
                        position: relative;
                    ">
                        <i class="${statusConfig.icon}"></i>
                    </div>
                    <style>
                        .driver-marker-pulse {
                            animation: elegantPulse 2s ease-in-out infinite;
                        }
                        @keyframes elegantPulse {
                            0% { transform: scale(1); box-shadow: ${statusConfig.shadow}, 0 4px 16px rgba(0,0,0,0.2); }
                            50% { transform: scale(1.1); box-shadow: ${statusConfig.shadow}, 0 6px 20px rgba(0,0,0,0.3); }
                            100% { transform: scale(1); box-shadow: ${statusConfig.shadow}, 0 4px 16px rgba(0,0,0,0.2); }
                        }
                    </style>
                `,
                iconSize: [48, 48],
                iconAnchor: [24, 24]
            });
        }

        // Load and display drivers
        async function loadDriverLocations() {
            try {
                const response = await fetch('/delivery/supervisor/locations');
                driversData = await response.json();
                updateMap();
                updateDriversList();
                updateDriversCount();
            } catch (error) {
                console.error('Error loading drivers:', error);
                document.getElementById('drivers-list').innerHTML = `
                    <div class="no-data">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>حدث خطأ في تحميل بيانات السائقين</p>
                    </div>
                `;
            }
        }

        // Update map with driver markers
        function updateMap() {
            // Clear existing markers
            Object.values(driverMarkers).forEach(marker => map.removeLayer(marker));
            driverMarkers = {};

            // Filter drivers based on current filter
            const filteredDrivers = currentFilter === 'all' 
                ? driversData 
                : driversData.filter(d => d.status === currentFilter);

            // Add markers for each driver
            filteredDrivers.forEach(driver => {
                if (driver.latitude && driver.longitude) {
                    const marker = L.marker([driver.latitude, driver.longitude], {
                        icon: createDriverIcon(driver.status)
                    }).addTo(map);

                    // Elegant popup
                    const popupContent = `
                        <div style="min-width: 300px; font-family:  'El Messiri', sans-serif; padding: 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 16px; color: white;">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.4rem; backdrop-filter: blur(10px);">
                                    ${driver.name ? driver.name.charAt(0) : 'S'}
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.3rem; margin-bottom: 0.5rem;">${driver.name || 'سائق'}</h4>
                                    <span style="background: ${getStatusGradient(driver.status)}; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">
                                        ${getStatusText(driver.status)}
                                    </span>
                                </div>
                            </div>
                            <div style="display: grid; gap: 0.8rem; font-size: 0.95rem;">
                                <div style="display: flex; justify-content: space-between;"><strong>الهاتف:</strong> <span>${driver.phone || 'غير متوفر'}</span></div>
                                <div style="display: flex; justify-content: space-between;"><strong>المركبة:</strong> <span>${driver.vehicle_type || 'غير محدد'}</span></div>
                                <div style="display: flex; justify-content: space-between;"><strong>اللوحة:</strong> <span>${driver.vehicle_plate || 'غير محدد'}</span></div>
                                <div style="display: flex; justify-content: space-between;"><strong>آخر تحديث:</strong> <span>${driver.last_update || 'غير متوفر'}</span></div>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    driverMarkers[driver.id] = marker;
                }
            });

            // Fit map to show all markers
            if (Object.keys(driverMarkers).length > 0) {
                const group = new L.featureGroup(Object.values(driverMarkers));
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        // Update drivers list
        function updateDriversList() {
            const listContainer = document.getElementById('drivers-list');
            
            if (driversData.length === 0) {
                listContainer.innerHTML = `
                    <div class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>لا يوجد سائقين نشطين حالياً</p>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = driversData.map(driver => `
                <div class="driver-card ${driver.status}" onclick="focusDriver(${driver.id})">
                    <div class="driver-header">
                        <div class="driver-info">
                            <div class="driver-avatar">
                                ${driver.name ? driver.name.charAt(0) : 'S'}
                            </div>
                            <div class="driver-details">
                                <h4>${driver.name || 'سائق'}</h4>
                                <p>${driver.phone || 'غير متوفر'}</p>
                            </div>
                        </div>
                        <div class="driver-status ${driver.status}">
                            ${getStatusText(driver.status)}
                        </div>
                    </div>
                    <div class="driver-meta">
                        <div class="meta-item">
                            <i class="fas fa-car"></i>
                            <span>${driver.vehicle_type || 'غير محدد'}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>${driver.last_update || 'غير متوفر'}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-id-card"></i>
                            <span>${driver.vehicle_plate || 'غير محدد'}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-star"></i>
                            <span>⭐ ${driver.rating || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Update drivers count
        function updateDriversCount() {
            document.getElementById('drivers-count').textContent = driversData.length;
        }

        // Focus on specific driver
        function focusDriver(driverId) {
            const driver = driversData.find(d => d.id === driverId);
            if (driver && driverMarkers[driverId]) {
                map.setView([driver.latitude, driver.longitude], 15);
                driverMarkers[driverId].openPopup();
            }
        }

        // Filter drivers
        function filterDrivers(filter) {
            currentFilter = filter;
            
            // Update button states
            document.querySelectorAll('.control-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            updateMap();
        }

        // Refresh data
        function refreshData() {
            loadDriverLocations();
        }

        // Helper functions
        function getStatusText(status) {
            const statusMap = {
                available: 'متاح',
                busy: 'مشغول',
                offline: 'غير متصل',
                on_break: 'في استراحة'
            };
            return statusMap[status] || status;
        }

        function getStatusGradient(status) {
            const gradientMap = {
                available: 'linear-gradient(135deg, #43e97b, #38f9d7)',
                busy: 'linear-gradient(135deg, #fa709a, #fee140)',
                offline: 'linear-gradient(135deg, #ff9a9e, #fecfef)',
                on_break: 'linear-gradient(135deg, #4facfe, #00f2fe)'
            };
            return gradientMap[status] || gradientMap.offline;
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            loadDriverLocations();
            
            // Auto-refresh every 30 seconds
            setInterval(loadDriverLocations, 30000);
        });
    </script>
</body>
</html>
