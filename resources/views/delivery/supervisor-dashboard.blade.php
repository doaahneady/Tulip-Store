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
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Cairo', sans-serif; }
        body { background: #f5f7fa; }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 1.5rem; display: flex; align-items: center; gap: 0.8rem; }
        .header .info { display: flex; gap: 2rem; align-items: center; font-size: 0.9rem; }
        .header .info a:hover { 
            background: #e55a2b !important; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
        }
        
        /* Main Layout */
        .main-container { display: flex; height: calc(100vh - 70px); }
        
        /* Sidebar */
        .sidebar {
            width: 350px;
            background: white;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 1rem;
            background: #f9fafb;
        }
        .stat-card {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: 800;
            color: #1e3a8a;
            font-family: monospace;
        }
        .stat-card .label {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 600;
            margin-top: 0.3rem;
        }
        .stat-card.available .value { color: #047857; }
        .stat-card.busy .value { color: #d97706; }
        .stat-card.offline .value { color: #dc2626; }
        
        /* Drivers List */
        .drivers-section {
            padding: 1rem;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .driver-card {
            background: white;
            padding: 1rem;
            margin-bottom: 0.8rem;
            border-radius: 8px;
            border-right: 4px solid #6b7280;
            cursor: pointer;
            transition: all 0.3s;
        }
        .driver-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(-3px);
        }
        .driver-card.available { border-right-color: #047857; }
        .driver-card.busy { border-right-color: #d97706; }
        .driver-card.offline { border-right-color: #dc2626; }
        
        .driver-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .driver-name {
            font-weight: 700;
            color: #1e3a8a;
            font-size: 1rem;
        }
        .driver-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .driver-status.available { background: #d1fae5; color: #065f46; }
        .driver-status.busy { background: #fef3c7; color: #92400e; }
        .driver-status.offline { background: #fee2e2; color: #991b1b; }
        
        .driver-info {
            display: flex;
            gap: 1rem;
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }
        .driver-info i { color: #1e3a8a; }
        
        .current-delivery {
            background: #eff6ff;
            padding: 0.8rem;
            border-radius: 6px;
            margin-top: 0.8rem;
            font-size: 0.85rem;
        }
        .current-delivery .order-number {
            font-weight: 700;
            color: #1e3a8a;
            font-family: monospace;
        }
        
        /* Map Container */
        .map-container {
            flex: 1;
            position: relative;
        }
        #map {
            width: 100%;
            height: 100%;
        }
        
        /* Map Controls */
        .map-controls {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 1000;
            display: flex;
            gap: 0.5rem;
        }
        .map-btn {
            background: white;
            border: none;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: all 0.3s;
            font-family: 'Cairo', sans-serif;
        }
        .map-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .map-btn.active {
            background: #1e3a8a;
            color: white;
        }
        
        /* Active Deliveries Panel */
        .active-deliveries {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            right: 1rem;
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }
        .active-deliveries h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 0.8rem;
        }
        .delivery-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            background: #f9fafb;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }
        .delivery-item:last-child { margin-bottom: 0; }
        
        /* Custom Marker Styles */
        .custom-marker {
            background: white;
            border: 3px solid;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .marker-available { border-color: #047857; color: #047857; }
        .marker-busy { border-color: #d97706; color: #d97706; }
        .marker-offline { border-color: #dc2626; color: #dc2626; }
        
        /* Popup Styles */
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
        }
        .driver-popup {
            min-width: 250px;
        }
        .driver-popup h4 {
            color: #1e3a8a;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .driver-popup .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.3rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .driver-popup .info-row:last-child { border-bottom: none; }
        .driver-popup .label { color: #6b7280; font-size: 0.85rem; }
        .driver-popup .value { font-weight: 700; color: #1e3a8a; }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }
        .loading-overlay.active { display: flex; }
        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #1e3a8a;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 107, 53, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 107, 53, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 107, 53, 0); }
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
            <a href="/driver-supervisor/orders" onclick="alert('الانتقال إلى صفحة إدارة الطلبات')" style="background: #ff6b35; color: white; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s; border: 2px solid white; box-shadow: 0 0 10px rgba(255,255,255,0.5); animation: pulse 2s infinite;">
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
                <div class="stat-card available">
                    <div class="value">{{ $availableDrivers }}</div>
                    <div class="label">متاح</div>
                </div>
                <div class="stat-card busy">
                    <div class="value">{{ $busyDrivers }}</div>
                    <div class="label">مشغول</div>
                </div>
                <div class="stat-card offline">
                    <div class="value">{{ $offlineDrivers }}</div>
                    <div class="label">غير متصل</div>
                </div>
            </div>

            <!-- Orders Ready for Delivery -->
            <div style="padding: 1rem; background: linear-gradient(135deg, #ff6b35, #e55a2b);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="color: white; font-size: 1.1rem; font-weight: 800; margin: 0;">
                        <i class="fas fa-clipboard-list"></i> الطلبات الجاهزة للتوصيل
                    </h3>
                    <span style="background: white; color: #ff6b35; padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 800; font-size: 0.9rem;">
                        {{ $readyOrders->count() ?? 0 }}
                    </span>
                </div>
                
                @if(isset($readyOrders) && $readyOrders->count() > 0)
                    <div style="max-height: 300px; overflow-y: auto;">
                        @foreach($readyOrders as $order)
                        <div style="background: white; border-radius: 10px; padding: 1rem; margin-bottom: 0.8rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="font-weight: 800; color: #1e3a8a;">#{{ $order->order_number }}</span>
                                <span style="background: {{ $order->payment_method === 'cash' ? '#fef3c7' : '#d1fae5' }}; color: {{ $order->payment_method === 'cash' ? '#92400e' : '#065f46' }}; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700;">
                                    {{ $order->payment_method === 'cash' ? 'نقداً' : 'مدفوع' }}
                                </span>
                            </div>
                            <div style="font-size: 0.9rem; color: #374151;">
                                <div><i class="fas fa-user" style="width: 20px; color: #6b7280;"></i> {{ $order->recipient_name }}</div>
                                <div><i class="fas fa-phone" style="width: 20px; color: #6b7280;"></i> {{ $order->phone }}</div>
                                <div><i class="fas fa-map-marker-alt" style="width: 20px; color: #6b7280;"></i> {{ $order->village }}</div>
                                <div style="margin-top: 0.5rem; font-weight: 800; color: #059669; font-size: 1.1rem;">
                                    ${{ number_format($order->total, 2) }}
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.8rem;">
                                <button onclick="showOrderOnMap({{ $order->latitude }}, {{ $order->longitude }}, '{{ $order->recipient_name }}', '{{ $order->village }}')" style="flex: 1; padding: 0.6rem; background: #1e3a8a; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-family: 'Cairo', sans-serif;">
                                    <i class="fas fa-map-marked-alt"></i> عرض على الخريطة
                                </button>
                                <button onclick="assignOrderToDriver({{ $order->id }})" style="flex: 1; padding: 0.6rem; background: #ff6b35; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-family: 'Cairo', sans-serif;">
                                    <i class="fas fa-user-plus"></i> تعيين سائق
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div style="background: rgba(255,255,255,0.2); border-radius: 10px; padding: 2rem; text-align: center; color: white;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.7;"></i>
                        <div>لا توجد طلبات جاهزة للتوصيل</div>
                    </div>
                @endif
            </div>

            <!-- Today's Stats -->
            <div style="padding: 1rem; background: #eff6ff; border-top: 3px solid #1e3a8a; border-bottom: 3px solid #1e3a8a;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; text-align: center;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #047857; font-family: monospace;">{{ $completedToday }}</div>
                        <div style="font-size: 0.8rem; color: #6b7280; font-weight: 600;">مكتملة اليوم</div>
                    </div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #d97706; font-family: monospace;">{{ $pendingDeliveries }}</div>
                        <div style="font-size: 0.8rem; color: #6b7280; font-weight: 600;">قيد التوصيل</div>
                    </div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #1e3a8a; font-family: monospace;">{{ number_format($avgDeliveryTime ?? 0, 0) }}</div>
                        <div style="font-size: 0.8rem; color: #6b7280; font-weight: 600;">متوسط الوقت (دقيقة)</div>
                    </div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #7c3aed; font-family: monospace;">{{ number_format($totalDistance ?? 0, 1) }}</div>
                        <div style="font-size: 0.8rem; color: #6b7280; font-weight: 600;">المسافة (كم)</div>
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
                    <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.5rem;">
                        <i class="far fa-clock"></i> آخر تحديث: {{ $driver->last_location_update->diffForHumans() }}
                    </div>
                    @endif
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: #9ca3af;">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem;"></i>
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
                        <div style="font-weight: 700; color: #1e3a8a;">{{ $delivery->order->order_number }}</div>
                        <div style="font-size: 0.85rem; color: #6b7280;">{{ $delivery->driver->driver_name }} → {{ $delivery->order->recipient_name }}</div>
                    </div>
                    <div>
                        <span style="background: #fef3c7; color: #92400e; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
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
        const map = L.map('map').setView([32.7125, 36.5669], 12); // Suwayda coordinates (warehouse location)

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
                available: '#047857',
                busy: '#d97706',
                offline: '#dc2626',
                on_break: '#6b7280'
            };
            
            return L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="custom-marker marker-${status}" style="border-color: ${colors[status]}; color: ${colors[status]};">
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
                                <div class="driver-popup">
                                    <h4><i class="fas fa-user"></i> ${driver.name}</h4>
                                    <div class="info-row">
                                        <span class="label">الحالة:</span>
                                        <span class="value" style="color: ${driver.status_color};">${driver.status_label}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">الهاتف:</span>
                                        <span class="value">${driver.phone}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">المركبة:</span>
                                        <span class="value">${driver.vehicle_plate}</span>
                                    </div>
                            `;

                            if (driver.current_order) {
                                popupContent += `
                                    <div class="info-row">
                                        <span class="label">الطلب:</span>
                                        <span class="value">${driver.current_order.order_number}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">العميل:</span>
                                        <span class="value">${driver.current_order.customer}</span>
                                    </div>
                                `;

                                // Add destination marker
                                if (driver.current_order.destination_lat && driver.current_order.destination_lng) {
                                    const destMarker = L.marker([driver.current_order.destination_lat, driver.current_order.destination_lng], {
                                        icon: L.divIcon({
                                            className: 'custom-div-icon',
                                            html: '<div style="background: #dc2626; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-map-marker-alt"></i></div>',
                                            iconSize: [30, 30],
                                            iconAnchor: [15, 15]
                                        })
                                    }).addTo(map);

                                    destMarker.bindPopup(`<b>وجهة التوصيل</b><br>${driver.current_order.customer}`);

                                    // Draw line between driver and destination
                                    L.polyline([
                                        [driver.latitude, driver.longitude],
                                        [driver.current_order.destination_lat, driver.current_order.destination_lng]
                                    ], {
                                        color: '#d97706',
                                        weight: 3,
                                        opacity: 0.7,
                                        dashArray: '10, 10'
                                    }).addTo(map);
                                }
                            }

                            if (driver.last_update) {
                                popupContent += `
                                    <div class="info-row">
                                        <span class="label">آخر تحديث:</span>
                                        <span class="value">${driver.last_update}</span>
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
            }, 500);
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
        let orderLocationPopup = null;
        
        // Show order location on map
        function showOrderOnMap(lat, lng, customerName, village) {
            // Remove previous order marker if exists
            if (orderLocationMarker) {
                map.removeLayer(orderLocationMarker);
            }
            
            // Create custom icon for order location
            const orderIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background: #ff6b35; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(255, 107, 53, 0.5); border: 3px solid white; font-size: 1.2rem;">
                    <i class="fas fa-box"></i>
                </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
            
            // Add marker
            orderLocationMarker = L.marker([lat, lng], { icon: orderIcon }).addTo(map);
            
            // Create popup with order info
            const popupContent = `
                <div style="min-width: 200px; font-family: 'Cairo', sans-serif;">
                    <h4 style="color: #ff6b35; margin: 0 0 0.5rem 0; font-size: 1.1rem;">
                        <i class="fas fa-box"></i> موقع التوصيل
                    </h4>
                    <div style="margin-bottom: 0.3rem;"><strong>العميل:</strong> ${customerName}</div>
                    <div style="margin-bottom: 0.3rem;"><strong>المنطقة:</strong> ${village}</div>
                    <div style="margin-top: 0.8rem;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" target="_blank" style="display: inline-block; background: #1e3a8a; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 0.85rem;">
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
            
            // Show clear button
            showClearOrderButton();
        }
        
        // Clear order marker from map
        function clearOrderMarker() {
            if (orderLocationMarker) {
                map.removeLayer(orderLocationMarker);
                orderLocationMarker = null;
            }
            hideClearOrderButton();
        }
        
        // Show clear order button
        function showClearOrderButton() {
            let btn = document.getElementById('clearOrderBtn');
            if (!btn) {
                btn = document.createElement('button');
                btn.id = 'clearOrderBtn';
                btn.className = 'map-btn';
                btn.innerHTML = '<i class="fas fa-times-circle"></i> إخفاء موقع الطلب';
                btn.onclick = clearOrderMarker;
                btn.style.background = '#ff6b35';
                btn.style.color = 'white';
                document.querySelector('.map-controls').appendChild(btn);
            }
            btn.style.display = 'block';
        }
        
        // Hide clear order button
        function hideClearOrderButton() {
            const btn = document.getElementById('clearOrderBtn');
            if (btn) {
                btn.style.display = 'none';
            }
        }
        
        // Order Assignment Functions
        let currentOrderId = null;
        
        function showOrderModal(orderId) {
            currentOrderId = orderId;
            fetch(`/api/driver-supervisor/orders/${orderId}`)
                .then(r => r.json())
                .then(order => {
                    const modal = document.getElementById('orderAssignModal');
                    document.getElementById('orderModalContent').innerHTML = `
                        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
                            <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e3a8a; margin-bottom: 1rem;">معلومات الطلب</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div><strong>رقم الطلب:</strong> ${order.order_number}</div>
                                <div><strong>العميل:</strong> ${order.recipient_name}</div>
                                <div><strong>الهاتف:</strong> ${order.phone}</div>
                                <div><strong>المنطقة:</strong> ${order.village}</div>
                                <div><strong>طريقة الدفع:</strong> ${order.payment_method === 'cash' ? 'نقداً' : 'مدفوع'}</div>
                                <div><strong>المجموع:</strong> $${order.total}</div>
                            </div>
                            ${order.address_note ? `<div style="margin-top: 1rem;"><strong>ملاحظات:</strong> ${order.address_note}</div>` : ''}
                        </div>
                        
                        <h4 style="font-weight: 700; margin-bottom: 0.8rem;">المنتجات:</h4>
                        <div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
                            ${order.items ? order.items.map(item => `
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                                    <span>${item.product ? item.product.name : item.product_name} × ${item.quantity}</span>
                                    <span style="font-weight: 600;">$${item.subtotal}</span>
                                </div>
                            `).join('') : '<div>لا توجد منتجات</div>'}
                        </div>
                    `;
                    modal.style.display = 'flex';
                })
                .catch(err => {
                    console.error(err);
                    alert('حدث خطأ في تحميل بيانات الطلب');
                });
        }
        
        function closeOrderModal() {
            document.getElementById('orderAssignModal').style.display = 'none';
            currentOrderId = null;
        }
        
        function assignOrderToDriver(orderId) {
            currentOrderId = orderId;
            document.getElementById('orderAssignModal').style.display = 'flex';
            showOrderModal(orderId);
        }
        
        async function submitDriverAssignment() {
            const driverId = document.getElementById('assignDriverSelect').value;
            const notes = document.getElementById('assignDeliveryNotes').value;
            
            if (!driverId) {
                alert('الرجاء اختيار سائق');
                return;
            }
            
            try {
                const response = await fetch(`/api/driver-supervisor/orders/${currentOrderId}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        driver_id: driverId,
                        delivery_notes: notes
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('تم تعيين السائق بنجاح!\\n\\nرابط التأكيد:\\n' + data.confirmation_link);
                    navigator.clipboard.writeText(data.confirmation_link);
                    closeOrderModal();
                    location.reload();
                } else {
                    alert('حدث خطأ: ' + (data.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error(error);
                alert('حدث خطأ في تعيين السائق');
            }
        }
    </script>
    
    <!-- Order Assignment Modal -->
    <div id="orderAssignModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 2rem; max-width: 700px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e3a8a;">
                    <i class="fas fa-file-invoice"></i> تعيين سائق للطلب
                </h2>
                <button onclick="closeOrderModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="orderModalContent"></div>
            
            <div style="margin-top: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">اختر السائق:</label>
                <select id="assignDriverSelect" style="width: 100%; padding: 0.9rem; border: 2px solid #e0e0e0; border-radius: 10px; font-family: 'Cairo', sans-serif; font-size: 1rem; margin-bottom: 1rem;">
                    <option value="">-- اختر سائق --</option>
                    @if(isset($availableDriversForAssignment))
                        @foreach($availableDriversForAssignment as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->phone }}</option>
                        @endforeach
                    @endif
                </select>
                
                <textarea id="assignDeliveryNotes" placeholder="ملاحظات التوصيل (اختياري)" style="width: 100%; padding: 0.9rem; border: 2px solid #e0e0e0; border-radius: 10px; font-family: 'Cairo', sans-serif; font-size: 0.95rem; min-height: 80px; margin-bottom: 1rem;"></textarea>
                
                <div style="display: flex; gap: 1rem;">
                    <button onclick="closeOrderModal()" style="flex: 1; padding: 1rem; background: #6c757d; color: white; border: none; border-radius: 10px; font-family: 'Cairo', sans-serif; font-size: 1.1rem; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                    <button onclick="submitDriverAssignment()" style="flex: 2; padding: 1rem; background: #28a745; color: white; border: none; border-radius: 10px; font-family: 'Cairo', sans-serif; font-size: 1.1rem; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-check"></i> تعيين وإنشاء رابط التأكيد
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
