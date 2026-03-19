<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم مشرف التوصيل - Tulip Store</title>
    
      <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family:  'El Messiri', sans-serif; 
        }
        
        body { 
            background: #f8fafc;
            background-image: 
                linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: #1a202c;
        }
        
        /* Subtle background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }
        
        .dashboard-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 2rem;
            padding-top: 120px;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            border-radius: 16px;
            margin-bottom: 2rem;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
        }
        
        .header h1 { 
            font-size: 2.25rem; 
            display: flex; 
            align-items: center; 
            gap: 1rem;
            font-weight: 800;
            color: #1e293b;
        }
        
        .header h1 i {
            color: #6366f1;
        }
        
        .header-actions { 
            display: flex; 
            gap: 2rem; 
            align-items: center; 
            font-size: 0.95rem;
        }
        
        .btn-manage { 
            background: #6366f1;
            color: white; 
            padding: 1rem 2rem; 
            border-radius: 12px; 
            text-decoration: none; 
            font-weight: 700; 
            display: flex; 
            align-items: center; 
            gap: 0.8rem; 
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        }
        
        .btn-manage:hover { 
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }
        
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-family: 'El Messiri', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: #6366f1;
            color: white;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        }
        
        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #ffffff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 1rem;
            font-weight: 600;
        }
        
        /* Main Content */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }
        
        /* Map Container */
        .map-container {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            height: 700px;
        }
        
        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .map-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .map-title i {
            color: #6366f1;
        }
        
        .map-controls {
            display: flex;
            gap: 0.8rem;
        }
        
        .control-btn {
            padding: 0.8rem 1.2rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            border-radius: 10px;
            font-family:  'El Messiri', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #475569;
        }
        
        .control-btn:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .control-btn.active {
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }
        
        #map {
            width: 100%;
            height: calc(100% - 80px);
            border-radius: 12px;
        }
        
        /* Drivers Panel */
        .drivers-panel {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            height: 700px;
            overflow-y: auto;
        }
        
        .panel-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .panel-title i {
            color: #6366f1;
        }
        
        .driver-card {
            background: #ffffff;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            border-right: 4px solid #e2e8f0;
        }
        
        .driver-card:hover {
            transform: translateX(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .driver-card.active {
            border-right-color: #6366f1;
            background: #f8fafc;
        }
        
        .driver-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .driver-name {
            font-weight: 800;
            color: #1e293b;
            font-size: 1.1rem;
        }
        
        .driver-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-available { 
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-busy { 
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-offline { 
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-on_break { 
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .driver-info {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            font-size: 0.9rem;
            color: #64748b;
        }
        
        .driver-info-row {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .info-icon {
            color: #6366f1;
            width: 20px;
            text-align: center;
        }
        
        .driver-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .driver-stat {
            text-align: center;
        }
        
        .driver-stat-value {
            font-weight: 800;
            color: #1e293b;
            font-size: 1.2rem;
        }
        
        .driver-stat-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
        }
        
        .no-drivers {
            text-align: center;
            padding: 3rem 2rem;
            color: #64748b;
        }
        
        .no-drivers i {
            font-size: 3rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }
        
        .refresh-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        .loading {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }
        
        .spinner {
            border: 4px solid #e2e8f0;
            border-top: 4px solid #6366f1;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .drivers-panel {
                height: auto;
                max-height: 500px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
                padding-top: 100px;
            }
            
            .header {
                padding: 1.5rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .header-actions {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .map-container,
            .drivers-panel {
                height: 400px;
            }
        }
        
        /* Custom Leaflet Popup Styles */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            font-family:  'El Messiri', sans-serif;
        }
        
        .driver-popup {
            min-width: 250px;
        }
        
        .popup-header {
            font-weight: 800;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .popup-info {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.6;
        }
        
        .popup-info strong {
            color: #1e293b;
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>
                    <i class="fas fa-motorcycle"></i>
                    لوحة تحكم مشرف التوصيل
                </h1>
                <p style="color: #64748b; margin-top: 0.5rem; font-weight: 600;">تتبع السائقين والطلبات في الوقت الفعلي</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('delivery.supervisor.manage-drivers') }}" class="btn-manage">
                    <i class="fas fa-users"></i>
                    إدارة السائقين
                </a>
                <button class="btn btn-primary" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i>
                    تحديث البيانات
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0e7ff; color: #6366f1;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ $stats['total_drivers'] }}</div>
                <div class="stat-label">إجمالي السائقين</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $stats['available_drivers'] }}</div>
                <div class="stat-label">سائقين متاحين</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="stat-value">{{ $stats['busy_drivers'] }}</div>
                <div class="stat-label">سائقين مشغولين</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fce7f3; color: #ec4899;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-value">{{ $stats['active_deliveries'] }}</div>
                <div class="stat-label">توصيلات نشطة</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                    <i class="fas fa-check-double"></i>
                </div>
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
                        <i class="fas fa-map-marked-alt"></i>
                        خريطة السائقين
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
                <div class="panel-title">
                    <i class="fas fa-list"></i>
                    قائمة السائقين
                </div>
                <div id="drivers-list">
                    <div class="loading">
                        <div class="spinner"></div>
                        <p>جاري تحميل بيانات السائقين...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                available: '#10b981',
                busy: '#f59e0b',
                offline: '#ef4444',
                on_break: '#6366f1'
            };

            return L.divIcon({
                className: 'custom-marker',
                html: `<div style="
                    background: ${colors[status] || colors.offline};
                    width: 35px;
                    height: 35px;
                    border-radius: 50%;
                    border: 3px solid white;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 16px;
                    color: white;
                    font-weight: 700;
                "><i class="fas fa-motorcycle"></i></div>`,
                iconSize: [35, 35],
                iconAnchor: [17, 17]
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
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>حدث خطأ في تحميل البيانات</div>
                    </div>
                `;
            }
        }

        // Update map markers
        function updateMap() {
            // Filter drivers
            const filteredDrivers = currentFilter === 'all' 
                ? driversData 
                : driversData.filter(d => d.status === currentFilter);

            // Clear existing markers
            Object.values(markers).forEach(marker => map.removeLayer(marker));
            markers = {};

            // Add markers for each driver
            filteredDrivers.forEach(driver => {
                if (driver.latitude && driver.longitude) {
                    const marker = L.marker([driver.latitude, driver.longitude], {
                        icon: getMarkerIcon(driver.status)
                    }).addTo(map);

                    const popupContent = `
                        <div class="driver-popup">
                            <div class="popup-header">
                                <i class="fas fa-user"></i>
                                ${driver.name}
                            </div>
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

            // Fit bounds if we have markers
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
                        <i class="fas fa-inbox"></i>
                        <div>لا يوجد سائقين نشطين حالياً</div>
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
                            <i class="fas fa-phone info-icon"></i>
                            <span>${driver.phone}</span>
                        </div>
                        <div class="driver-info-row">
                            <i class="fas fa-car info-icon"></i>
                            <span>${driver.vehicle_type || 'غير محدد'} - ${driver.vehicle_plate || 'N/A'}</span>
                        </div>
                        <div class="driver-info-row">
                            <i class="fas fa-clock info-icon"></i>
                            <span>${driver.last_update || 'غير متوفر'}</span>
                        </div>
                        ${driver.active_assignments && driver.active_assignments.length > 0 ? `
                            <div class="driver-info-row">
                                <i class="fas fa-box info-icon"></i>
                                <span>${driver.active_assignments.length} توصيل نشط</span>
                            </div>
                        ` : ''}
                    </div>
                    <div class="driver-stats">
                        <div class="driver-stat">
                            <div class="driver-stat-value">${driver.total_deliveries || 0}</div>
                            <div class="driver-stat-label">إجمالي التوصيلات</div>
                        </div>
                        <div class="driver-stat">
                            <div class="driver-stat-value">⭐ ${driver.rating || 'N/A'}</div>
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

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            loadDrivers();
            
            // Auto-refresh every 30 seconds
            setInterval(loadDrivers, 30000);
        });
    </script>
</body>
</html>