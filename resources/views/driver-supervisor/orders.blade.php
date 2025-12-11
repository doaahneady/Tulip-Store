<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الطلبات - مشرف السائقين</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Cairo', sans-serif; }
        body { background: #f5f7fa; min-height: 100vh; }
        
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #fff;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
        }
        
        .header-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .header-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        .container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        
        .order-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border-color: #2563eb;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .order-number {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e3a8a;
        }
        
        .payment-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        
        .payment-cash { background: #fef3c7; color: #92400e; }
        .payment-paid { background: #d1fae5; color: #065f46; }
        
        .order-info { margin-bottom: 1rem; }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 0.95rem;
            border-bottom: 1px solid #f5f5f5;
        }
        .info-row:last-child { border-bottom: none; }
        
        .info-label { color: #6b7280; }
        .info-value { font-weight: 600; color: #1a1a1a; }
        
        .order-total {
            font-size: 1.3rem;
            font-weight: 800;
            color: #059669;
            text-align: center;
            padding: 0.8rem;
            background: #ecfdf5;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .btn-group {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff6b35, #e55a2b);
            color: #fff;
        }
        
        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
        }
        
        .btn-secondary {
            background: #1e3a8a;
            color: #fff;
        }
        
        .btn-secondary:hover {
            background: #1e40af;
        }
        
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .modal-overlay.active { display: flex; }
        
        .modal-container {
            background: #fff;
            border-radius: 20px;
            max-width: 900px;
            width: 100%;
            max-height: 95vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            font-size: 1.4rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .close-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-btn:hover {
            background: #dc2626;
            transform: rotate(90deg);
        }
        
        .modal-body {
            padding: 1.5rem 2rem;
            overflow-y: auto;
            flex: 1;
        }
        
        .modal-footer {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-top: 2px solid #e5e7eb;
            display: flex;
            gap: 1rem;
        }
        
        .modal-footer .btn {
            padding: 1rem 2rem;
        }
        
        .btn-cancel {
            background: #6b7280;
            color: white;
        }
        
        .btn-cancel:hover {
            background: #4b5563;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            flex: 2;
        }
        
        .btn-success:hover {
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
        }
        
        #orderMap {
            width: 100%;
            height: 350px;
            border-radius: 12px;
            margin: 1rem 0;
            border: 3px solid #e5e7eb;
        }
        
        .order-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }
        
        .order-details-grid div {
            padding: 0.5rem;
        }
        
        .order-details-grid strong {
            color: #6b7280;
            font-weight: 600;
        }
        
        .products-list {
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .products-list-header {
            background: #1e3a8a;
            color: white;
            padding: 0.8rem 1rem;
            font-weight: 700;
        }
        
        .product-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .product-item:last-child { border-bottom: none; }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #374151;
        }
        
        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 4rem 2rem;
            color: #9ca3af;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        
        .google-maps-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #4285f4;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .google-maps-link:hover {
            background: #3367d6;
        }
        
        /* Leaflet routing styles */
        .leaflet-routing-container {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>
            <i class="fas fa-clipboard-list"></i>
            إدارة الطلبات الجاهزة للتوصيل
        </h1>
        <div class="header-actions">
            <a href="/delivery/supervisor/dashboard" class="header-btn">
                <i class="fas fa-map-marked-alt"></i>
                خريطة السائقين
            </a>
            <a href="/" class="header-btn">
                <i class="fas fa-home"></i>
                الرئيسية
            </a>
        </div>
    </div>

    <div class="container">
        <div class="orders-grid">
            @forelse($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-number">#{{ $order->order_number }}</div>
                    <div class="payment-badge {{ $order->payment_method === 'cash' ? 'payment-cash' : 'payment-paid' }}">
                        {{ $order->payment_method === 'cash' ? 'دفع عند الاستلام' : 'مدفوع' }}
                    </div>
                </div>
                
                <div class="order-info">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-user"></i> العميل:</span>
                        <span class="info-value">{{ $order->recipient_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-phone"></i> الهاتف:</span>
                        <span class="info-value">{{ $order->phone }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-map-marker-alt"></i> المنطقة:</span>
                        <span class="info-value">{{ $order->village }}</span>
                    </div>
                </div>
                
                <div class="order-total">
                    ${{ number_format($order->total, 2) }}
                </div>
                
                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="showOnMap({{ $order->latitude }}, {{ $order->longitude }}, '{{ $order->recipient_name }}', '{{ $order->village }}')">
                        <i class="fas fa-route"></i> الخريطة
                    </button>
                    <button class="btn btn-primary" onclick="openAssignModal({{ $order->id }})">
                        <i class="fas fa-user-plus"></i> تعيين سائق
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>لا توجد طلبات جاهزة للتوصيل حالياً</h3>
                <p>ستظهر الطلبات هنا عندما تكون جاهزة للتوصيل</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Order Assignment Modal -->
    <div id="orderModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2>
                    <i class="fas fa-file-invoice"></i>
                    تعيين سائق للطلب
                </h2>
                <button class="close-btn" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div id="orderDetails"></div>
                <div id="orderMap"></div>
                <div id="googleMapsLink"></div>
                
                <div class="form-group">
                    <label><i class="fas fa-user-tie"></i> اختر السائق:</label>
                    <select id="driverSelect" class="form-control">
                        <option value="">-- اختر سائق --</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->phone ?? 'بدون رقم' }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-sticky-note"></i> ملاحظات التوصيل (اختياري):</label>
                    <textarea id="deliveryNotes" class="form-control" rows="3" placeholder="أضف أي ملاحظات للسائق..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-cancel" onclick="closeModal()">
                    <i class="fas fa-times"></i> إلغاء
                </button>
                <button class="btn btn-success" onclick="assignDriver()">
                    <i class="fas fa-check-circle"></i> تعيين وإنشاء رابط التأكيد
                </button>
            </div>
        </div>
    </div>

    <!-- Map Modal for viewing location -->
    <div id="mapModal" class="modal-overlay">
        <div class="modal-container" style="max-width: 1000px;">
            <div class="modal-header">
                <h2>
                    <i class="fas fa-map-marked-alt"></i>
                    موقع التوصيل
                </h2>
                <button class="close-btn" onclick="closeMapModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body" style="padding: 0;">
                <div id="viewMap" style="width: 100%; height: 500px;"></div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-cancel" onclick="closeMapModal()">
                    <i class="fas fa-times"></i> إغلاق
                </button>
                <a id="directionsLink" href="#" target="_blank" class="btn btn-primary" style="text-decoration: none;">
                    <i class="fas fa-directions"></i> فتح في خرائط جوجل
                </a>
            </div>
        </div>
    </div>

    <script>
        let currentOrderId = null;
        let orderMap = null;
        let viewMap = null;
        let routingControl = null;
        
        // Suwayda center coordinates (storage/warehouse location)
        const defaultStart = [32.7125, 36.5669];
        
        function openAssignModal(orderId) {
            currentOrderId = orderId;
            document.getElementById('orderModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            
            fetch(`/api/driver-supervisor/orders/${orderId}`)
                .then(r => r.json())
                .then(order => {
                    document.getElementById('orderDetails').innerHTML = `
                        <div class="order-details-grid">
                            <div><strong>رقم الطلب:</strong><br>${order.order_number}</div>
                            <div><strong>العميل:</strong><br>${order.recipient_name}</div>
                            <div><strong>الهاتف:</strong><br>${order.phone}</div>
                            <div><strong>المنطقة:</strong><br>${order.village}</div>
                            <div><strong>طريقة الدفع:</strong><br>${order.payment_method === 'cash' ? 'نقداً عند الاستلام' : 'مدفوع مسبقاً'}</div>
                            <div><strong>المجموع:</strong><br><span style="color:#059669;font-size:1.2rem;font-weight:800;">$${order.total}</span></div>
                        </div>
                        ${order.address_note ? `<div style="background:#fef3c7;padding:1rem;border-radius:8px;margin-bottom:1rem;"><strong><i class="fas fa-info-circle"></i> ملاحظات العنوان:</strong> ${order.address_note}</div>` : ''}
                        
                        <div class="products-list">
                            <div class="products-list-header"><i class="fas fa-shopping-bag"></i> المنتجات</div>
                            ${order.items ? order.items.map(item => `
                                <div class="product-item">
                                    <span>${item.product ? item.product.name : (item.product_name || 'منتج')} × ${item.quantity}</span>
                                    <span style="font-weight:700;">$${item.subtotal}</span>
                                </div>
                            `).join('') : '<div class="product-item">لا توجد منتجات</div>'}
                        </div>
                    `;
                    
                    // Google Maps link
                    document.getElementById('googleMapsLink').innerHTML = `
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${order.latitude},${order.longitude}" target="_blank" class="google-maps-link">
                            <i class="fab fa-google"></i> فتح الاتجاهات في خرائط جوجل
                        </a>
                    `;
                    
                    // Initialize map with routing
                    setTimeout(() => {
                        if (orderMap) {
                            orderMap.remove();
                        }
                        
                        orderMap = L.map('orderMap').setView([order.latitude, order.longitude], 14);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(orderMap);
                        
                        // Add destination marker
                        const destIcon = L.divIcon({
                            html: `<div style="background:#ff6b35; width:40px; height:40px; border-radius:50%; border:4px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:white; font-size:1.2rem;">
                                <i class="fas fa-flag-checkered"></i>
                            </div>`,
                            iconSize: [40, 40],
                            iconAnchor: [20, 20]
                        });
                        
                        L.marker([order.latitude, order.longitude], {icon: destIcon})
                            .addTo(orderMap)
                            .bindPopup(`<b>${order.village}</b><br>${order.recipient_name}<br><strong>$${order.total}</strong>`)
                            .openPopup();
                        
                        // Add routing from default start to destination
                        if (routingControl) {
                            orderMap.removeControl(routingControl);
                        }
                        
                        routingControl = L.Routing.control({
                            waypoints: [
                                L.latLng(defaultStart[0], defaultStart[1]),
                                L.latLng(order.latitude, order.longitude)
                            ],
                            routeWhileDragging: false,
                            showAlternatives: false,
                            fitSelectedRoutes: true,
                            lineOptions: {
                                styles: [{color: '#2563eb', weight: 5, opacity: 0.8}]
                            },
                            createMarker: function() { return null; }
                        }).addTo(orderMap);
                        
                    }, 100);
                })
                .catch(err => {
                    console.error(err);
                    alert('حدث خطأ في تحميل بيانات الطلب');
                });
        }
        
        function closeModal() {
            document.getElementById('orderModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            currentOrderId = null;
            document.getElementById('driverSelect').value = '';
            document.getElementById('deliveryNotes').value = '';
        }
        
        // Drivers data from server
        const driversData = @json($driversJson ?? []);
        
        // Calculate distance between two points (Haversine formula)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }
        
        function showOnMap(lat, lng, customerName, village) {
            document.getElementById('mapModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Set Google Maps link
            document.getElementById('directionsLink').href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
            
            setTimeout(() => {
                if (viewMap) {
                    viewMap.remove();
                }
                
                viewMap = L.map('viewMap').setView([lat, lng], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(viewMap);
                
                // Add destination marker (order location)
                const destIcon = L.divIcon({
                    html: `<div style="background:#ff6b35; width:50px; height:50px; border-radius:50%; border:4px solid #fff; box-shadow:0 4px 15px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:white; font-size:1.5rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>`,
                    iconSize: [50, 50],
                    iconAnchor: [25, 25]
                });
                
                L.marker([lat, lng], {icon: destIcon})
                    .addTo(viewMap)
                    .bindPopup(`<div style="text-align:center;font-family:Cairo,sans-serif;"><b style="font-size:1.1rem;color:#ff6b35;">📦 موقع التوصيل</b><br><b>${customerName}</b><br><span style="color:#6b7280;">${village}</span></div>`)
                    .openPopup();
                
                // Add warehouse marker
                const warehouseIcon = L.divIcon({
                    html: `<div style="background:#059669; width:40px; height:40px; border-radius:50%; border:3px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:white; font-size:1rem;">
                        <i class="fas fa-warehouse"></i>
                    </div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });
                
                L.marker(defaultStart, {icon: warehouseIcon})
                    .addTo(viewMap)
                    .bindPopup(`<div style="text-align:center;font-family:Cairo,sans-serif;"><b style="color:#059669;">🏭 المستودع</b><br>السويداء</div>`);
                
                // Add nearby drivers with distance
                let driversInfo = '<div style="font-family:Cairo,sans-serif;"><b style="color:#1e3a8a;">🚗 السائقين القريبين:</b><br>';
                
                driversData.forEach(driver => {
                    if (driver.lat && driver.lng) {
                        const distance = calculateDistance(lat, lng, driver.lat, driver.lng);
                        const statusColor = driver.status === 'available' ? '#059669' : (driver.status === 'busy' ? '#d97706' : '#dc2626');
                        const statusText = driver.status === 'available' ? 'متاح' : (driver.status === 'busy' ? 'مشغول' : 'غير متصل');
                        
                        // Driver icon
                        const driverIcon = L.divIcon({
                            html: `<div style="background:${statusColor}; width:35px; height:35px; border-radius:50%; border:3px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:white; font-size:0.9rem;">
                                <i class="fas fa-motorcycle"></i>
                            </div>`,
                            iconSize: [35, 35],
                            iconAnchor: [17, 17]
                        });
                        
                        L.marker([driver.lat, driver.lng], {icon: driverIcon})
                            .addTo(viewMap)
                            .bindPopup(`<div style="text-align:center;font-family:Cairo,sans-serif;min-width:150px;">
                                <b style="color:${statusColor};">${driver.name}</b><br>
                                <span style="background:${statusColor};color:white;padding:2px 8px;border-radius:10px;font-size:0.8rem;">${statusText}</span><br>
                                <span style="color:#6b7280;">📞 ${driver.phone || 'غير متوفر'}</span><br>
                                <b style="color:#1e3a8a;">📍 ${distance.toFixed(1)} كم من الطلب</b>
                            </div>`);
                        
                        driversInfo += `<div style="padding:5px 0;border-bottom:1px solid #eee;">
                            <span style="color:${statusColor};">●</span> ${driver.name} - <b>${distance.toFixed(1)} كم</b> (${statusText})
                        </div>`;
                    }
                });
                
                driversInfo += '</div>';
                
                // Add drivers info panel
                const infoPanel = L.control({position: 'bottomleft'});
                infoPanel.onAdd = function() {
                    const div = L.DomUtil.create('div', 'info-panel');
                    div.style.cssText = 'background:white;padding:10px 15px;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.2);max-height:200px;overflow-y:auto;';
                    div.innerHTML = driversInfo;
                    return div;
                };
                infoPanel.addTo(viewMap);
                
                // Add routing from warehouse to destination
                L.Routing.control({
                    waypoints: [
                        L.latLng(defaultStart[0], defaultStart[1]),
                        L.latLng(lat, lng)
                    ],
                    routeWhileDragging: false,
                    showAlternatives: false,
                    fitSelectedRoutes: false,
                    lineOptions: {
                        styles: [{color: '#2563eb', weight: 5, opacity: 0.7, dashArray: '10, 10'}]
                    },
                    createMarker: function() { return null; }
                }).addTo(viewMap);
                
                // Fit bounds to show all markers
                const bounds = L.latLngBounds([defaultStart, [lat, lng]]);
                driversData.forEach(driver => {
                    if (driver.lat && driver.lng) {
                        bounds.extend([driver.lat, driver.lng]);
                    }
                });
                viewMap.fitBounds(bounds, {padding: [50, 50]});
                
            }, 100);
        }
        
        function closeMapModal() {
            document.getElementById('mapModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        async function assignDriver() {
            const driverId = document.getElementById('driverSelect').value;
            const notes = document.getElementById('deliveryNotes').value;
            
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
                    // Copy link to clipboard
                    navigator.clipboard.writeText(data.confirmation_link);
                    
                    alert('✅ تم تعيين السائق بنجاح!\n\n📋 تم نسخ رابط التأكيد:\n' + data.confirmation_link);
                    
                    closeModal();
                    location.reload();
                } else {
                    alert('❌ حدث خطأ: ' + (data.message || 'خطأ غير معروف'));
                }
            } catch (error) {
                console.error(error);
                alert('❌ حدث خطأ في تعيين السائق');
            }
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeMapModal();
            }
        });
        
        // Close modal on overlay click
        document.getElementById('orderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMapModal();
            }
        });
    </script>
</body>
</html>
