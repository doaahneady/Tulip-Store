<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم مشرف التوصيل - Tulip Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Cairo', sans-serif; 
        }
        
        body { 
            background: #0a0a0a;
            background-image: 
                radial-gradient(circle at 25% 25%, #1a1a2e 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, #16213e 0%, transparent 50%);
            min-height: 100vh;
            color: #ffffff;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(45deg, transparent 30%, rgba(0, 255, 255, 0.03) 50%, transparent 70%),
                linear-gradient(-45deg, transparent 30%, rgba(255, 0, 255, 0.03) 50%, transparent 70%);
            animation: backgroundShift 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }
        
        @keyframes backgroundShift {
            0%, 100% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(20px) translateY(-20px); }
        }
        
        /* Header */
        .header {
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid #00ffff;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.3);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #00ffff, #ff00ff, #00ffff);
            animation: neonFlow 3s linear infinite;
        }
        
        @keyframes neonFlow {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .header h1 { 
            font-size: 2rem; 
            display: flex; 
            align-items: center; 
            gap: 1rem;
            font-weight: 800;
            text-shadow: 0 0 20px #00ffff;
            color: #00ffff;
        }
        
        .header .info { 
            display: flex; 
            gap: 2rem; 
            align-items: center; 
            font-size: 0.95rem;
        }
        
        .header .info a { 
            background: linear-gradient(135deg, #ff0080, #ff8000);
            color: white; 
            padding: 1rem 2rem; 
            border-radius: 25px; 
            text-decoration: none; 
            font-weight: 700; 
            display: flex; 
            align-items: center; 
            gap: 0.8rem; 
            transition: all 0.4s ease;
            box-shadow: 0 0 20px rgba(255, 0, 128, 0.5);
            border: 1px solid #ff0080;
            position: relative;
            overflow: hidden;
        }
        
        .header .info a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .header .info a:hover::before {
            left: 100%;
        }
        
        .header .info a:hover { 
            transform: translateY(-3px);
            box-shadow: 0 0 30px rgba(255, 0, 128, 0.8);
        }
        
        .header .info > div {
            background: rgba(0, 255, 255, 0.1);
            padding: 0.8rem 1.2rem;
            border-radius: 15px;
            border: 1px solid #00ffff;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
        }
        
        /* Main Layout */
        .main-container { 
            display: flex;
            gap: 2rem;
            padding: 2rem;
            height: calc(100vh - 120px);
        }
        
        /* Sidebar */
        .sidebar {
            width: 400px;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow-y: auto;
            border: 1px solid #00ffff;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.2);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 2rem;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border-radius: 20px 20px 0 0;
            border-bottom: 2px solid #00ffff;
        }
        
        .stat-card {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        
        .stat-card:hover::before {
            transform: translateX(100%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 255, 255, 0.3);
        }
        
        .stat-card .value {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 15px currentColor;
        }
        
        .stat-card .label {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 600;
        }
        
        .stat-card:nth-child(1) {
            border-color: #00ff00;
            color: #00ff00;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.2);
        }
        
        .stat-card:nth-child(2) {
            border-color: #00ffff;
            color: #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
        }
        
        .stat-card:nth-child(3) {
            border-color: #ffff00;
            color: #ffff00;
            box-shadow: 0 0 20px rgba(255, 255, 0, 0.2);
        }
        
        .stat-card:nth-child(4) {
            border-color: #ff0080;
            color: #ff0080;
            box-shadow: 0 0 20px rgba(255, 0, 128, 0.2);
        }
        
        /* Performance Section */
        .performance-section {
            padding: 2rem;
            background: rgba(0, 0, 0, 0.3);
            margin: 0 2rem 2rem 2rem;
            border-radius: 15px;
            border: 1px solid #ff00ff;
            box-shadow: 0 0 25px rgba(255, 0, 255, 0.2);
        }
        
        .performance-section h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #ff00ff;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-shadow: 0 0 15px #ff00ff;
        }
        
        .performance-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .performance-card {
            background: linear-gradient(135deg, rgba(0,0,0,0.6), rgba(20,20,40,0.6));
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid;
            transition: all 0.3s ease;
        }
        
        .performance-card:nth-child(1) {
            border-color: #00ff80;
            box-shadow: 0 0 15px rgba(0, 255, 128, 0.3);
        }
        
        .performance-card:nth-child(2) {
            border-color: #ff8000;
            box-shadow: 0 0 15px rgba(255, 128, 0, 0.3);
        }
        
        .performance-card:nth-child(3) {
            border-color: #8000ff;
            box-shadow: 0 0 15px rgba(128, 0, 255, 0.3);
        }
        
        .performance-card:nth-child(4) {
            border-color: #ff0040;
            box-shadow: 0 0 15px rgba(255, 0, 64, 0.3);
        }
        
        .performance-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px currentColor;
        }
        
        .performance-card .value {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            color: inherit;
            text-shadow: 0 0 10px currentColor;
        }
        
        .performance-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 600;
            color: #ffffff;
        }
        
        /* Drivers Section */
        .drivers-section {
            padding: 2rem;
            background: rgba(0, 0, 0, 0.3);
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #00ffff;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #00ffff;
            text-shadow: 0 0 15px #00ffff;
        }
        
        .driver-card {
            background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(20,20,40,0.7));
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 15px;
            border-left: 4px solid;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .driver-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1));
            transition: width 0.3s ease;
        }
        
        .driver-card:hover::after {
            width: 100%;
        }
        
        .driver-card:hover {
            transform: translateX(-10px);
            box-shadow: 0 10px 30px rgba(0, 255, 255, 0.3);
        }
        
        .driver-card.available { 
            border-left-color: #00ff00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.2);
        }
        
        .driver-card.busy { 
            border-left-color: #ffff00;
            box-shadow: 0 0 15px rgba(255, 255, 0, 0.2);
        }
        
        .driver-card.offline { 
            border-left-color: #ff0000;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
        }
        
        .driver-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .driver-name {
            font-weight: 800;
            color: #ffffff;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .driver-name::before {
            content: '';
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #00ff00;
            box-shadow: 0 0 10px currentColor;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .driver-card.busy .driver-name::before { background: #ffff00; }
        .driver-card.offline .driver-name::before { background: #ff0000; }
        
        .driver-status {
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid;
            background: rgba(0,0,0,0.5);
        }
        
        .driver-status.available { 
            color: #00ff00;
            border-color: #00ff00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        .driver-status.busy { 
            color: #ffff00;
            border-color: #ffff00;
            box-shadow: 0 0 10px rgba(255, 255, 0, 0.5);
        }
        
        .driver-status.offline { 
            color: #ff0000;
            border-color: #ff0000;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }
        
        .driver-info {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #cccccc;
            margin-top: 0.8rem;
        }
        
        .driver-info i { 
            color: #00ffff;
            text-shadow: 0 0 5px #00ffff;
        }
        
        .current-delivery {
            background: rgba(0, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
            font-size: 0.9rem;
            border: 1px solid #00ffff;
        }
        
        .current-delivery .order-number {
            font-weight: 700;
            color: #00ffff;
            text-shadow: 0 0 10px #00ffff;
        }
        
        /* Map Container */
        .map-container {
            flex: 1;
            position: relative;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid #00ffff;
            box-shadow: 0 0 40px rgba(0, 255, 255, 0.3);
        }
        
        #map {
            width: 100%;
            height: 100%;
            border-radius: 18px;
            filter: hue-rotate(180deg) invert(1) contrast(1.2);
        }
        
        /* Map Controls */
        .map-controls {
            position: absolute;
            top: 2rem;
            right: 2rem;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .map-btn {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid #00ffff;
            padding: 1rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
            transition: all 0.4s ease;
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            color: #00ffff;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            position: relative;
            overflow: hidden;
        }
        
        .map-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .map-btn:hover::before {
            left: 100%;
        }
        
        .map-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.6);
            text-shadow: 0 0 10px #00ffff;
        }
        
        .map-btn.active {
            background: linear-gradient(135deg, #00ffff, #0080ff);
            color: #000000;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.8);
        }
        
        .map-btn i {
            font-size: 1.1rem;
        }
        
        /* Active Deliveries Panel */
        .active-deliveries {
            position: absolute;
            bottom: 2rem;
            left: 2rem;
            right: 2rem;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid #ff00ff;
            box-shadow: 0 0 30px rgba(255, 0, 255, 0.3);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .active-deliveries h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ff00ff;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px #ff00ff;
        }
        
        .delivery-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 0, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 0.8rem;
            border: 1px solid rgba(255, 0, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .delivery-item:hover {
            background: rgba(255, 0, 255, 0.2);
            transform: scale(1.02);
        }
        
        .delivery-item:last-child { 
            margin-bottom: 0; 
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }
        
        .loading-overlay.active { 
            display: flex; 
        }
        
        .spinner {
            border: 4px solid rgba(0, 255, 255, 0.3);
            border-top: 4px solid #00ffff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            box-shadow: 0 0 20px #00ffff;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-container {
                flex-direction: column;
                height: auto;
            }
            .sidebar {
                width: 100%;
                height: 500px;
            }
            .map-container {
                height: 500px;
            }
        }
        
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
                gap: 1rem;
            }
            .header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            .header .info {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1.5rem;
            }
            .performance-grid {
                grid-template-columns: 1fr;
            }
            .map-controls {
                top: 1rem;
                right: 1rem;
                flex-direction: row;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>
            <i class="fas fa-motorcycle"></i>
            لوحة تحكم مشرف التوصيل
        </h1>
        <div class="info">
            <a href="/driver-supervisor/orders">
                <i class="fas fa-clipboard-list"></i>
                إدارة الطلبات
            </a>
            <div><i class="far fa-calendar"></i> {{ date('Y-m-d') }}</div>
            <div><i class="far fa-clock"></i> <span id="current-time">{{ date('H:i') }}</span></div>
            <div><i class="fas fa-user"></i> {{ auth()->user()->name }}</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="value">{{ $totalDrivers }}</div>
                    <div class="label">إجمالي السائقين</div>
                </div>
                <div class="stat-card">
                    <div class="value">{{ $availableDrivers }}</div>
                    <div class="label">متاح</div>
                </div>
                <div class="stat-card">
                    <div class="value">{{ $busyDrivers }}</div>
                    <div class="label">مشغول</div>
                </div>
                <div class="stat-card">
                    <div class="value">{{ $offlineDrivers }}</div>
                    <div class="label">غير متصل</div>
                </div>
            </div>

            <!-- Today's Performance -->
            <div class="performance-section">
                <h3>
                    <i class="fas fa-chart-line"></i>
                    أداء اليوم
                </h3>
                <div class="performance-grid">
                    <div class="performance-card" style="border-color: #00ff80; color: #00ff80;">
                        <div class="value">{{ $completedToday }}</div>
                        <div class="label">مكتملة اليوم</div>
                    </div>
                    <div class="performance-card" style="border-color: #ff8000; color: #ff8000;">
                        <div class="value">{{ $pendingDeliveries }}</div>
                        <div class="label">قيد التوصيل</div>
                    </div>
                    <div class="performance-card" style="border-color: #8000ff; color: #8000ff;">
                        <div class="value">{{ number_format($avgDeliveryTime ?? 0, 0) }}</div>
                        <div class="label">متوسط الوقت (دقيقة)</div>
                    </div>
                    <div class="performance-card" style="border-color: #ff0040; color: #ff0040;">
                        <div class="value">{{ number_format($totalDistance ?? 0, 1) }}</div>
                        <div class="label">المسافة (كم)</div>
                    </div>
                </div>
            </div>

            <!-- Drivers List -->
            <div class="drivers-section">
                <div class="section-title">
                    <i class="fas fa-users"></i>
                    السائقين النشطين
                </div>
                
                @forelse($drivers as $driver)
                <div class="driver-card {{ $driver->status }}" onclick="focusOnDriver({{ $driver->id }}, {{ $driver->current_latitude }}, {{ $driver->current_longitude }})">
                    <div class="driver-header">
                        <div class="driver-name">{{ $driver->driver_name }}</div>
                        <div class="driver-status {{ $driver->status }}">{{ $driver->status_label }}</div>
                    </div>
                    <div class="driver-info">
                        <span><i class="fas fa-phone"></i> {{ $driver->phone }}</span>
                        <span><i class="fas fa-{{ $driver->vehicle_type === 'motorcycle' ? 'motorcycle' : 'car' }}"></i> {{ $driver->vehicle_plate }}</span>
                    </div>
                    @if($driver->currentAssignment)
                    <div class="current-delivery">
                        <div><i class="fas fa-box"></i> طلب: <span class="order-number">{{ $driver->currentAssignment->order->order_number }}</span></div>
                        <div style="margin-top: 0.3rem;"><i class="fas fa-user"></i> {{ $driver->currentAssignment->order->recipient_name }}</div>
                        <div style="margin-top: 0.3rem;"><i class="fas fa-map-marker-alt"></i> {{ $driver->currentAssignment->order->village }}</div>
                    </div>
                    @endif
                    @if($driver->last_location_update)
                    <div style="font-size: 0.75rem; color: #999; margin-top: 0.5rem;">
                        <i class="far fa-clock"></i> آخر تحديث: {{ $driver->last_location_update->diffForHumans() }}
                    </div>
                    @endif
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: #666;">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: #00ffff;"></i>
                    <div>لا يوجد سائقين نشطين حالياً</div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Map Container -->
        <div class="map-container">
            <!-- Map Controls -->
            <div class="map-controls">
                <button class="map-btn active" onclick="showAllDrivers()">
                    <i class="fas fa-users"></i> جميع السائقين
                </button>
                <button class="map-btn" onclick="showAvailableOnly()">
                    <i class="fas fa-check-circle"></i> المتاحين فقط
                </button>
                <button class="map-btn" onclick="showBusyOnly()">
                    <i class="fas fa-shipping-fast"></i> المشغولين فقط
                </button>
                <button class="map-btn" onclick="toggleTraffic()">
                    <i class="fas fa-traffic-light"></i> حركة المرور
                </button>
                <button class="map-btn" onclick="refreshLocations()">
                    <i class="fas fa-sync-alt"></i> تحديث
                </button>
            </div>

            <!-- Map -->
            <div id="map"></div>

            <!-- Active Deliveries Panel -->
            @if($activeDeliveries->count() > 0)
            <div class="active-deliveries">
                <h3><i class="fas fa-shipping-fast"></i> التوصيلات النشطة ({{ $activeDeliveries->count() }})</h3>
                @foreach($activeDeliveries as $delivery)
                <div class="delivery-item">
                    <div>
                        <div style="font-weight: 700; color: #00ffff;">{{ $delivery->order->order_number }}</div>
                        <div style="font-size: 0.85rem; color: #ccc;">{{ $delivery->driver->driver_name }} → {{ $delivery->order->recipient_name }}</div>
                    </div>
                    <div>
                        <span style="background: rgba(255, 255, 0, 0.2); color: #ffff00; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; border: 1px solid #ffff00;">
                            {{ $delivery->status === 'assigned' ? 'تم التعيين' : ($delivery->status === 'picked_up' ? 'تم الاستلام' : 'في الطريق') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([32.7125, 36.5669], 12); // Suwayda coordinates

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Store markers
        let driverMarkers = {};
        let deliveryMarkers = {};

        // Custom icons
        const createDriverIcon = (status) => {
            const colors = {
                available: '#00ff00',
                busy: '#ffff00',
                offline: '#ff0000',
                on_break: '#ff8000'
            };
            
            return L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background: ${colors[status]}; border: 3px solid #000; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #000; box-shadow: 0 0 20px ${colors[status]};">
                    <i class="fas fa-motorcycle"></i>
                </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
        };

        // Load driver locations
        function loadDriverLocations() {
            fetch('/delivery/supervisor/locations')
                .then(response => response.json())
                .then(drivers => {
                    // Clear existing markers
                    Object.values(driverMarkers).forEach(marker => map.removeLayer(marker));
                    driverMarkers = {};

                    // Add new markers
                    drivers.forEach(driver => {
                        if (driver.latitude && driver.longitude) {
                            const marker = L.marker([driver.latitude, driver.longitude], {
                                icon: createDriverIcon(driver.status)
                            }).addTo(map);

                            // Create popup content
                            let popupContent = `
                                <div style="min-width: 250px; font-family: 'Cairo', sans-serif; background: #000; color: #fff; padding: 1rem; border-radius: 10px; border: 1px solid #00ffff;">
                                    <h4 style="color: #00ffff; font-size: 1.1rem; margin-bottom: 0.5rem; text-shadow: 0 0 10px #00ffff;"><i class="fas fa-user"></i> ${driver.name}</h4>
                                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #333;">
                                        <span style="color: #ccc; font-size: 0.85rem;">الحالة:</span>
                                        <span style="font-weight: 700; color: ${driver.status_color}; text-shadow: 0 0 5px ${driver.status_color};">${driver.status_label}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #333;">
                                        <span style="color: #ccc; font-size: 0.85rem;">الهاتف:</span>
                                        <span style="font-weight: 700; color: #fff;">${driver.phone}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                                        <span style="color: #ccc; font-size: 0.85rem;">المركبة:</span>
                                        <span style="font-weight: 700; color: #fff;">${driver.vehicle_plate}</span>
                                    </div>
                            `;

                            if (driver.current_order) {
                                popupContent += `
                                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-top: 1px solid #333; margin-top: 0.5rem; padding-top: 0.8rem;">
                                        <span style="color: #ccc; font-size: 0.85rem;">الطلب:</span>
                                        <span style="font-weight: 700; color: #00ffff;">${driver.current_order.order_number}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                                        <span style="color: #ccc; font-size: 0.85rem;">العميل:</span>
                                        <span style="font-weight: 700; color: #fff;">${driver.current_order.customer}</span>
                                    </div>
                                `;

                                // Add destination marker
                                if (driver.current_order.destination_lat && driver.current_order.destination_lng) {
                                    const destMarker = L.marker([driver.current_order.destination_lat, driver.current_order.destination_lng], {
                                        icon: L.divIcon({
                                            className: 'custom-div-icon',
                                            html: '<div style="background: #ff0080; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px #ff0080; border: 2px solid #fff;"><i class="fas fa-map-marker-alt"></i></div>',
                                            iconSize: [30, 30],
                                            iconAnchor: [15, 15]
                                        })
                                    }).addTo(map);

                                    destMarker.bindPopup(`<div style="background: #000; color: #fff; padding: 1rem; border-radius: 10px; border: 1px solid #ff0080;"><b style="color: #ff0080;">وجهة التوصيل</b><br>${driver.current_order.customer}</div>`);

                                    // Draw line between driver and destination
                                    L.polyline([
                                        [driver.latitude, driver.longitude],
                                        [driver.current_order.destination_lat, driver.current_order.destination_lng]
                                    ], {
                                        color: '#ffff00',
                                        weight: 3,
                                        opacity: 0.8,
                                        dashArray: '10, 10'
                                    }).addTo(map);
                                }
                            }

                            if (driver.last_update) {
                                popupContent += `
                                    <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-top: 1px solid #333; margin-top: 0.5rem; padding-top: 0.8rem;">
                                        <span style="color: #ccc; font-size: 0.85rem;">آخر تحديث:</span>
                                        <span style="font-weight: 700; color: #fff;">${driver.last_update}</span>
                                    </div>
                                `;
                            }

                            popupContent += '</div>';
                            marker.bindPopup(popupContent);

                            driverMarkers[driver.id] = marker;
                        }
                    });
                })
                .catch(error => console.error('Error loading locations:', error));
        }

        // Focus on specific driver
        function focusOnDriver(driverId, lat, lng) {
            if (lat && lng) {
                map.setView([lat, lng], 15);
                if (driverMarkers[driverId]) {
                    driverMarkers[driverId].openPopup();
                }
            }
        }

        // Filter functions
        function showAllDrivers() {
            Object.values(driverMarkers).forEach(marker => marker.addTo(map));
            updateActiveButton(0);
        }

        function showAvailableOnly() {
            // Implementation would filter markers by status
            updateActiveButton(1);
        }

        function showBusyOnly() {
            // Implementation would filter markers by status
            updateActiveButton(2);
        }

        function toggleTraffic() {
            // Traffic layer toggle
            updateActiveButton(3);
        }

        function refreshLocations() {
            document.getElementById('loadingOverlay').classList.add('active');
            loadDriverLocations();
            setTimeout(() => {
                document.getElementById('loadingOverlay').classList.remove('active');
            }, 1000);
        }

        function updateActiveButton(index) {
            document.querySelectorAll('.map-btn').forEach((btn, i) => {
                btn.classList.toggle('active', i === index);
            });
        }

        // Update time
        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = 
                now.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
        }

        // Initialize
        loadDriverLocations();
        setInterval(loadDriverLocations, 30000); // Refresh every 30 seconds
        setInterval(updateTime, 1000);
        
        // Order location marker
        let orderLocationMarker = null;
        
        // Show order location on map
        function showOrderOnMap(lat, lng, customerName, village) {
            // Remove previous order marker if exists
            if (orderLocationMarker) {
                map.removeLayer(orderLocationMarker);
            }
            
            // Create custom icon for order location
            const orderIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background: #ff6b35; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 25px #ff6b35; border: 3px solid white; font-size: 1.2rem;">
                    <i class="fas fa-box"></i>
                </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
            
            // Add marker
            orderLocationMarker = L.marker([lat, lng], { icon: orderIcon }).addTo(map);
            
            // Create popup with order info
            const popupContent = `
                <div style="min-width: 200px; font-family: 'Cairo', sans-serif; background: #000; color: #fff; padding: 1rem; border-radius: 10px; border: 1px solid #ff6b35;">
                    <h4 style="color: #ff6b35; margin: 0 0 0.5rem 0; font-size: 1.1rem; text-shadow: 0 0 10px #ff6b35;">
                        <i class="fas fa-box"></i> موقع التوصيل
                    </h4>
                    <div style="margin-bottom: 0.3rem;"><strong>العميل:</strong> ${customerName}</div>
                    <div style="margin-bottom: 0.3rem;"><strong>المنطقة:</strong> ${village}</div>
                    <div style="margin-top: 0.8rem;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #00ffff, #0080ff); color: #000; padding: 0.5rem 1rem; border-radius: 20px; text-decoration: none; font-weight: 700; font-size: 0.85rem;">
                            <i class="fas fa-directions"></i> فتح في خرائط جوجل
                        </a>
                    </div>
                </div>
            `;
            
            orderLocationMarker.bindPopup(popupContent).openPopup();
            
            // Center map on order location with animation
            map.flyTo([lat, lng], 15, {
                duration: 1.5
            });
        }

        // Order Assignment Functions
        let currentOrderId = null;
        
        function assignOrderToDriver(orderId) {
            currentOrderId = orderId;
            // Implementation for order assignment modal
            alert('تم النقر على تعيين سائق للطلب رقم: ' + orderId);
        }
    </script>
</body>
</html>