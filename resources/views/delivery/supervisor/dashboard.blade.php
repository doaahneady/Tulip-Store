<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم مشرف التوصيل - Tulip Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/store.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f7fafc;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
            padding-top: 100px; /* Space for fixed navbar */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: calc(100vh - 80px);
        }

        .header {
            background: white;
            padding: 25px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            color: #2d3748;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-manage {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-manage:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(72, 187, 120, 0.4);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #718096;
            font-size: 14px;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 25px;
        }

        .map-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            height: 700px;
        }

        #map {
            width: 100%;
            height: calc(100% - 60px);
            border-radius: 12px;
            margin-top: 15px;
        }

        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .map-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
        }

        .map-controls {
            display: flex;
            gap: 10px;
        }

        .control-btn {
            padding: 8px 16px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .control-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .drivers-panel {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            height: 700px;
            overflow-y: auto;
        }

        .panel-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .driver-card {
            background: #f7fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .driver-card:hover {
            background: #edf2f7;
            border-color: #667eea;
        }

        .driver-card.active {
            border-color: #667eea;
            background: #e6f0ff;
        }

        .driver-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .driver-name {
            font-weight: 700;
            color: #2d3748;
            font-size: 16px;
        }

        .driver-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-available {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-busy {
            background: #bee3f8;
            color: #2c5282;
        }

        .status-offline {
            background: #e2e8f0;
            color: #4a5568;
        }

        .status-on_break {
            background: #fef5e7;
            color: #975a16;
        }

        .driver-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 13px;
            color: #4a5568;
        }

        .driver-info-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-icon {
            width: 16px;
            text-align: center;
        }

        .driver-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .driver-stat {
            text-align: center;
        }

        .driver-stat-value {
            font-weight: 700;
            color: #2d3748;
            font-size: 18px;
        }

        .driver-stat-label {
            font-size: 11px;
            color: #718096;
        }

        .no-drivers {
            text-align: center;
            padding: 40px 20px;
            color: #718096;
        }

        .refresh-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #48bb78;
            border-radius: 50%;
            margin-left: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #718096;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .drivers-panel {
                height: auto;
                max-height: 500px;
            }
        }

        /* Custom Leaflet Popup Styles */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            font-family: 'Cairo', sans-serif;
        }

        .driver-popup {
            min-width: 200px;
        }

        .popup-header {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 10px;
            color: #2d3748;
        }

        .popup-info {
            font-size: 13px;
            color: #4a5568;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>🚚 لوحة تحكم مشرف التوصيل</h1>
                <p style="color: #718096; margin-top: 5px;">تتبع السائقين والطلبات في الوقت الفعلي</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('delivery.supervisor.manage-drivers') }}" class="btn-manage">
                    👥 إدارة السائقين
                </a>
                <button class="btn btn-primary" onclick="refreshData()">
                    🔄 تحديث البيانات
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e6f0ff;">👥</div>
                <div class="stat-value">{{ $stats['total_drivers'] }}</div>
                <div class="stat-label">إجمالي السائقين</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #c6f6d5;">✅</div>
                <div class="stat-value">{{ $stats['available_drivers'] }}</div>
                <div class="stat-label">سائقين متاحين</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #bee3f8;">🚗</div>
                <div class="stat-value">{{ $stats['busy_drivers'] }}</div>
                <div class="stat-label">سائقين مشغولين</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef5e7;">📦</div>
                <div class="stat-value">{{ $stats['active_deliveries'] }}</div>
                <div class="stat-label">توصيلات نشطة</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #d4f4dd;">✨</div>
                <div class="stat-value">{{ $stats['completed_today'] }}</div>
                <div class="stat-label">مكتمل اليوم</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Map Container -->
            <div class="map-container">
                <div class="map-header">
                    <div class="map-title">
                        🗺️ خريطة السائقين
                        <span class="refresh-indicator"></span>
                    </div>
                    <div class="map-controls">
                        <button class="control-btn active" data-filter="all" onclick="filterDrivers('all')">
                            الكل
                        </button>
                        <button class="control-btn" data-filter="available" onclick="filterDrivers('available')">
                            متاح
                        </button>
                        <button class="control-btn" data-filter="busy" onclick="filterDrivers('busy')">
                            مشغول
                        </button>
                    </div>
                </div>
                <div id="map"></div>
            </div>

            <!-- Drivers Panel -->
            <div class="drivers-panel">
                <div class="panel-title">📋 قائمة السائقين</div>
                <div id="drivers-list">
                    <div class="loading">
                        <div class="spinner"></div>
                        <p>جاري تحميل بيانات السائقين...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let map;
        let markers = {};
        let driversData = [];
        let currentFilter = 'all';

        // Initialize map
        function initMap() {
            // Center on Sweida, Syria
            map = L.map('map').setView([32.7081, 36.5686], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
        }

        // Custom marker icons
        function getMarkerIcon(status) {
            const colors = {
                available: '#48bb78',
                busy: '#4299e1',
                offline: '#a0aec0',
                on_break: '#ed8936'
            };

            return L.divIcon({
                className: 'custom-marker',
                html: `<div style="
                    background: ${colors[status] || colors.offline};
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    border: 3px solid white;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 16px;
                ">🚗</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
        }

        // Load drivers data
        async function loadDrivers() {
            try {
                const response = await fetch('/delivery/supervisor/locations');
                driversData = await response.json();
                updateMap();
                updateDriversList();
            } catch (error) {
                console.error('Error loading drivers:', error);
                document.getElementById('drivers-list').innerHTML = `
                    <div class="no-drivers">
                        ❌ حدث خطأ في تحميل البيانات
                    </div>
                `;
            }
        }

        // Update map markers with smooth animation
        function updateMap() {
            // Filter drivers
            const filteredDrivers = currentFilter === 'all' 
                ? driversData 
                : driversData.filter(d => d.status === currentFilter);

            // Track which markers to keep
            const activeDriverIds = new Set(filteredDrivers.map(d => d.id));

            // Remove markers for drivers no longer in filtered list
            Object.keys(markers).forEach(driverId => {
                if (!activeDriverIds.has(parseInt(driverId))) {
                    map.removeLayer(markers[driverId]);
                    delete markers[driverId];
                }
            });

            // Add or update markers for each driver
            filteredDrivers.forEach(driver => {
                const newLatLng = L.latLng(driver.latitude, driver.longitude);
                
                if (markers[driver.id]) {
                    // Update existing marker with smooth animation
                    const marker = markers[driver.id];
                    const currentLatLng = marker.getLatLng();
                    
                    // Only animate if position changed significantly (more than 10 meters)
                    const distance = currentLatLng.distanceTo(newLatLng);
                    if (distance > 10) {
                        animateMarker(marker, newLatLng);
                    }
                    
                    // Update icon if status changed
                    marker.setIcon(getMarkerIcon(driver.status));
                    
                    // Update popup content
                    const popupContent = `
                        <div class="driver-popup">
                            <div class="popup-header">${driver.name}</div>
                            <div class="popup-info">
                                <strong>الحالة:</strong> ${getStatusText(driver.status)}<br>
                                <strong>الهاتف:</strong> ${driver.phone}<br>
                                <strong>المركبة:</strong> ${driver.vehicle_type || 'غير محدد'}<br>
                                <strong>اللوحة:</strong> ${driver.vehicle_plate || 'غير محدد'}<br>
                                <strong>التقييم:</strong> ⭐ ${driver.rating}<br>
                                <strong>آخر تحديث:</strong> ${driver.last_update || 'غير متوفر'}
                            </div>
                        </div>
                    `;
                    marker.getPopup().setContent(popupContent);
                } else {
                    // Create new marker
                    const marker = L.marker(newLatLng, {
                        icon: getMarkerIcon(driver.status)
                    }).addTo(map);

                    const popupContent = `
                        <div class="driver-popup">
                            <div class="popup-header">${driver.name}</div>
                            <div class="popup-info">
                                <strong>الحالة:</strong> ${getStatusText(driver.status)}<br>
                                <strong>الهاتف:</strong> ${driver.phone}<br>
                                <strong>المركبة:</strong> ${driver.vehicle_type || 'غير محدد'}<br>
                                <strong>اللوحة:</strong> ${driver.vehicle_plate || 'غير محدد'}<br>
                                <strong>التقييم:</strong> ⭐ ${driver.rating}<br>
                                <strong>آخر تحديث:</strong> ${driver.last_update || 'غير متوفر'}
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    markers[driver.id] = marker;
                }
            });

            // Only fit bounds on first load or when explicitly requested
            if (Object.keys(markers).length > 0 && !map._initialBoundsFit) {
                const bounds = L.latLngBounds(filteredDrivers.map(d => [d.latitude, d.longitude]));
                map.fitBounds(bounds, { padding: [50, 50] });
                map._initialBoundsFit = true;
            }
        }

        // Update drivers list
        function updateDriversList() {
            const listContainer = document.getElementById('drivers-list');
            
            if (driversData.length === 0) {
                listContainer.innerHTML = `
                    <div class="no-drivers">
                        📭 لا يوجد سائقين نشطين حالياً
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = driversData.map(driver => `
                <div class="driver-card" onclick="focusDriver(${driver.id})">
                    <div class="driver-header">
                        <div class="driver-name">${driver.name}</div>
                        <div class="driver-status status-${driver.status}">
                            ${getStatusText(driver.status)}
                        </div>
                    </div>
                    <div class="driver-info">
                        <div class="driver-info-row">
                            <span class="info-icon">📱</span>
                            <span>${driver.phone}</span>
                        </div>
                        <div class="driver-info-row">
                            <span class="info-icon">🚗</span>
                            <span>${driver.vehicle_type || 'غير محدد'} - ${driver.vehicle_plate || 'N/A'}</span>
                        </div>
                        <div class="driver-info-row">
                            <span class="info-icon">🕐</span>
                            <span>${driver.last_update || 'غير متوفر'}</span>
                        </div>
                        ${driver.active_assignments.length > 0 ? `
                            <div class="driver-info-row">
                                <span class="info-icon">📦</span>
                                <span>${driver.active_assignments.length} توصيل نشط</span>
                            </div>
                        ` : ''}
                    </div>
                    <div class="driver-stats">
                        <div class="driver-stat">
                            <div class="driver-stat-value">${driver.total_deliveries}</div>
                            <div class="driver-stat-label">إجمالي التوصيلات</div>
                        </div>
                        <div class="driver-stat">
                            <div class="driver-stat-value">⭐ ${driver.rating}</div>
                            <div class="driver-stat-label">التقييم</div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Focus on specific driver
        function focusDriver(driverId) {
            const driver = driversData.find(d => d.id === driverId);
            if (driver && markers[driverId]) {
                map.setView([driver.latitude, driver.longitude], 15);
                markers[driverId].openPopup();
                
                // Highlight driver card
                document.querySelectorAll('.driver-card').forEach(card => {
                    card.classList.remove('active');
                });
                event.currentTarget.classList.add('active');
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

        // Get status text in Arabic
        function getStatusText(status) {
            const statusMap = {
                available: 'متاح',
                busy: 'مشغول',
                offline: 'غير متصل',
                on_break: 'في استراحة'
            };
            return statusMap[status] || status;
        }

        // Refresh data
        function refreshData() {
            loadDrivers();
        }

        // Real-time updates using polling (every 5 seconds for live updates)
        let updateInterval;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            loadDrivers();
            
            // Live updates every 5 seconds (real-time feel)
            updateInterval = setInterval(loadDrivers, 5000);
        });

        // Smooth marker animation when location updates
        function animateMarker(marker, newLatLng) {
            const currentLatLng = marker.getLatLng();
            const steps = 20;
            let step = 0;
            
            const latStep = (newLatLng.lat - currentLatLng.lat) / steps;
            const lngStep = (newLatLng.lng - currentLatLng.lng) / steps;
            
            const animate = setInterval(() => {
                step++;
                const lat = currentLatLng.lat + (latStep * step);
                const lng = currentLatLng.lng + (lngStep * step);
                marker.setLatLng([lat, lng]);
                
                if (step >= steps) {
                    clearInterval(animate);
                }
            }, 50);
        }
    </script>
</body>
</html>
