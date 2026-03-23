<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تتبع السائق - Tulip Store</title>
      <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family:  'El Messiri', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .logo {
            text-align: center;
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            text-align: center;
            color: #2d3748;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 8px;
        }

        select, input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            font-family:  'El Messiri', sans-serif;
            transition: all 0.3s;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family:  'El Messiri', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            margin-bottom: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(72, 187, 120, 0.4);
        }

        .btn-danger {
            background: #f56565;
            color: white;
        }

        .btn-danger:hover {
            background: #e53e3e;
        }

        .status-box {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .status-inactive {
            background: #fed7d7;
            color: #c53030;
        }

        .status-active {
            background: #c6f6d5;
            color: #22543d;
        }

        .info-box {
            background: #f7fafc;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #718096;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
        }

        .pulse {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #48bb78;
            border-radius: 50%;
            margin-left: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .hidden {
            display: none;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🚗</div>
        <h1>تتبع موقع السائق</h1>
        <p class="subtitle">Tulip Store Driver Tracking</p>

        <div id="alertBox" class="hidden"></div>

        <!-- Login Form -->
        <div id="loginForm">
            <div class="form-group">
                <label>اختر اسمك</label>
                <select id="driverId">
                    <option value="">-- اختر السائق --</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary" onclick="startTracking()">
                🚀 بدء التتبع
            </button>
        </div>

        <!-- Tracking Status -->
        <div id="trackingStatus" class="hidden">
            <div class="status-box status-active">
                <span class="pulse"></span>
                التتبع نشط - يتم إرسال الموقع
            </div>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">السائق:</span>
                    <span class="info-value" id="displayDriver">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">الموقع:</span>
                    <span class="info-value" id="displayLocation">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">السرعة:</span>
                    <span class="info-value" id="displaySpeed">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">آخر تحديث:</span>
                    <span class="info-value" id="displayLastUpdate">-</span>
                </div>
                <div class="info-row">
                    <span class="info-label">عدد التحديثات:</span>
                    <span class="info-value" id="displayCount">0</span>
                </div>
            </div>

            <button class="btn btn-danger" onclick="stopTracking()">
                ⏹️ إيقاف التتبع
            </button>
        </div>

        <div class="back-link">
            <a href="/">← العودة للصفحة الرئيسية</a>
        </div>
    </div>

    <script>
        let watchId = null;
        let updateCount = 0;
        let currentDriverId = null;
        let currentDriverName = null;
        const API_URL = '{{ url("/api/driver/location/update") }}';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Check if tracking was active before
        window.addEventListener('load', function() {
            const savedDriverId = localStorage.getItem('tracking_driver_id');
            const savedDriverName = localStorage.getItem('tracking_driver_name');
            
            if (savedDriverId && savedDriverName) {
                // Resume tracking
                currentDriverId = savedDriverId;
                currentDriverName = savedDriverName;
                document.getElementById('driverId').value = savedDriverId;
                resumeTracking();
            }
        });

        // Start tracking
        function startTracking() {
            const driverId = document.getElementById('driverId').value;
            
            if (!driverId) {
                showAlert('الرجاء اختيار السائق', 'error');
                return;
            }

            if (!navigator.geolocation) {
                showAlert('المتصفح لا يدعم تحديد الموقع', 'error');
                return;
            }

            currentDriverId = driverId;
            const driverSelect = document.getElementById('driverId');
            currentDriverName = driverSelect.options[driverSelect.selectedIndex].text;

            // Save to localStorage for persistence
            localStorage.setItem('tracking_driver_id', currentDriverId);
            localStorage.setItem('tracking_driver_name', currentDriverName);

            // Request permission and start watching
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    // Success
                    document.getElementById('loginForm').classList.add('hidden');
                    document.getElementById('trackingStatus').classList.remove('hidden');
                    document.getElementById('displayDriver').textContent = currentDriverName;

                    // Send initial location
                    sendLocation(position);

                    // Watch position continuously
                    watchId = navigator.geolocation.watchPosition(
                        sendLocation,
                        handleError,
                        {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 10000
                        }
                    );

                    showAlert('تم بدء التتبع بنجاح!', 'success');

                    // Register service worker for background tracking
                    registerServiceWorker();
                },
                (error) => {
                    handleError(error);
                }
            );
        }

        // Resume tracking (after page reload)
        function resumeTracking() {
            if (!navigator.geolocation) {
                return;
            }

            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('trackingStatus').classList.remove('hidden');
            document.getElementById('displayDriver').textContent = currentDriverName;

            watchId = navigator.geolocation.watchPosition(
                sendLocation,
                handleError,
                {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 10000
                }
            );

            showAlert('تم استئناف التتبع', 'success');
        }

        // Stop tracking
        function stopTracking() {
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }

            // Clear localStorage
            localStorage.removeItem('tracking_driver_id');
            localStorage.removeItem('tracking_driver_name');

            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('trackingStatus').classList.add('hidden');
            updateCount = 0;

            showAlert('تم إيقاف التتبع', 'success');
        }

        // Send location to server
        function sendLocation(position) {
            const data = {
                driver_id: currentDriverId,
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                speed: position.coords.speed || 0,
                accuracy: position.coords.accuracy
            };

            // Update display
            updateCount++;
            document.getElementById('displayLocation').textContent = 
                `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
            document.getElementById('displaySpeed').textContent = 
                `${((position.coords.speed || 0) * 3.6).toFixed(1)} كم/س`;
            document.getElementById('displayLastUpdate').textContent = 
                new Date().toLocaleTimeString('ar-SA');
            document.getElementById('displayCount').textContent = updateCount;

            // Send to server
            fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Location sent successfully');
                } else {
                    console.error('Failed to send location:', result);
                }
            })
            .catch(error => {
                console.error('Error sending location:', error);
            });
        }

        // Handle errors
        function handleError(error) {
            let message = 'حدث خطأ في تحديد الموقع';
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message = 'تم رفض الإذن. الرجاء السماح بالوصول للموقع في إعدادات المتصفح.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = 'معلومات الموقع غير متوفرة';
                    break;
                case error.TIMEOUT:
                    message = 'انتهت مهلة طلب الموقع';
                    break;
            }
            
            showAlert(message, 'error');
        }

        // Show alert
        function showAlert(message, type) {
            const alertBox = document.getElementById('alertBox');
            alertBox.className = `alert alert-${type}`;
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');

            setTimeout(() => {
                alertBox.classList.add('hidden');
            }, 5000);
        }

        // Register service worker for background tracking
        function registerServiceWorker() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('Service Worker registered');
                    })
                    .catch(error => {
                        console.log('Service Worker registration failed:', error);
                    });
            }
        }

        // Keep tracking alive even when page is in background
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible' && currentDriverId) {
                // Page became visible again, ensure tracking is active
                if (watchId === null) {
                    resumeTracking();
                }
            }
        });

        // Prevent accidental page close
        window.addEventListener('beforeunload', function(e) {
            if (watchId !== null) {
                e.preventDefault();
                e.returnValue = 'التتبع نشط. هل تريد حقاً إغلاق الصفحة؟';
                return e.returnValue;
            }
        });
    </script>
</body>
</html>
