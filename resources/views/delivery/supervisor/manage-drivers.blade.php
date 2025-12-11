<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة السائقين - Tulip Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/store.css">
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
            padding-top: 80px; /* Space for fixed navbar */
        }



        /* Banner */
        .banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .banner-content {
            position: relative;
            z-index: 1;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(5px);
        }

        .banner h1 {
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .banner p {
            font-size: 18px;
            opacity: 0.9;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: -30px auto 30px;
            padding: 0 30px;
            position: relative;
            z-index: 2;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
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

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 25px 30px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-danger {
            background: #f56565;
            color: white;
        }

        .btn-warning {
            background: #ed8936;
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 15px 20px;
            text-align: right;
            font-weight: 700;
            color: #4a5568;
            font-size: 14px;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
        }

        tr:hover {
            background: #f7fafc;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
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

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #2d3748;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #718096;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: #f7fafc;
            color: #2d3748;
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 2px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 2px solid #fc8181;
        }

        .rating {
            color: #f6ad55;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <!-- Banner -->
    <div class="banner">
        <div class="banner-content">
            <a href="{{ route('delivery.supervisor.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-right"></i> العودة للخريطة
            </a>
            <h1>🚗 إدارة السائقين</h1>
            <p>إضافة وتعديل وحذف معلومات السائقين</p>
        </div>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e6f0ff;">👥</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">إجمالي السائقين</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #c6f6d5;">✅</div>
                <div class="stat-value">{{ $stats['available'] }}</div>
                <div class="stat-label">متاح</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #bee3f8;">🚗</div>
                <div class="stat-value">{{ $stats['busy'] }}</div>
                <div class="stat-label">مشغول</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #e2e8f0;">⚫</div>
                <div class="stat-value">{{ $stats['offline'] }}</div>
                <div class="stat-label">غير متصل</div>
            </div>
        </div>

        <!-- Alert -->
        <div id="alert" class="alert"></div>

        <!-- Main Card -->
        <div class="main-card">
            <div class="card-header">
                <h2 class="card-title">قائمة السائقين</h2>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>
                    <span>إضافة سائق جديد</span>
                </button>
            </div>

            <div class="table-container">
                @if($drivers->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الهاتف</th>
                            <th>المركبة</th>
                            <th>اللوحة</th>
                            <th>الحالة</th>
                            <th>التقييم</th>
                            <th>التوصيلات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $driver)
                        <tr>
                            <td>{{ $driver->id }}</td>
                            <td><strong>{{ $driver->name }}</strong></td>
                            <td>{{ $driver->phone }}</td>
                            <td>{{ $driver->vehicle_type ?? '-' }}</td>
                            <td>{{ $driver->vehicle_plate ?? '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ $driver->status }}">
                                    @if($driver->status == 'available') متاح
                                    @elseif($driver->status == 'busy') مشغول
                                    @elseif($driver->status == 'on_break') استراحة
                                    @else غير متصل
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="rating">
                                    <i class="fas fa-star"></i> {{ number_format($driver->rating, 1) }}
                                </span>
                            </td>
                            <td>{{ $driver->total_deliveries }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editDriver(@json($driver))'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteDriver({{ $driver->id }}, '{{ $driver->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h3>لا يوجد سائقين</h3>
                    <p>ابدأ بإضافة سائق جديد</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="driverModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">إضافة سائق جديد</h3>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <form id="driverForm" onsubmit="saveDriver(event)">
                <div class="modal-body">
                    <input type="hidden" id="driverId" name="id">
                    
                    <div class="form-group">
                        <label>الاسم الكامل *</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>رقم الهاتف *</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" id="email" name="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>رقم الرخصة *</label>
                        <input type="text" id="license_number" name="license_number" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>نوع المركبة</label>
                            <input type="text" id="vehicle_type" name="vehicle_type" placeholder="سيارة صغيرة">
                        </div>
                        <div class="form-group">
                            <label>رقم اللوحة</label>
                            <input type="text" id="vehicle_plate" name="vehicle_plate" placeholder="أ ب ج 1234">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>الحالة</label>
                        <select id="status" name="status">
                            <option value="available">متاح</option>
                            <option value="busy">مشغول</option>
                            <option value="on_break">استراحة</option>
                            <option value="offline">غير متصل</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="is_active" name="is_active" checked>
                            نشط
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="closeModal()">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <span>حفظ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Open Add Modal
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'إضافة سائق جديد';
            document.getElementById('driverForm').reset();
            document.getElementById('driverId').value = '';
            document.getElementById('driverModal').classList.add('active');
        }

        // Edit Driver
        function editDriver(driver) {
            document.getElementById('modalTitle').textContent = 'تعديل بيانات السائق';
            document.getElementById('driverId').value = driver.id;
            document.getElementById('name').value = driver.name;
            document.getElementById('phone').value = driver.phone;
            document.getElementById('email').value = driver.email || '';
            document.getElementById('license_number').value = driver.license_number;
            document.getElementById('vehicle_type').value = driver.vehicle_type || '';
            document.getElementById('vehicle_plate').value = driver.vehicle_plate || '';
            document.getElementById('status').value = driver.status;
            document.getElementById('is_active').checked = driver.is_active;
            document.getElementById('driverModal').classList.add('active');
        }

        // Close Modal
        function closeModal() {
            document.getElementById('driverModal').classList.remove('active');
        }

        // Save Driver
        function saveDriver(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const driverId = formData.get('id');
            const url = driverId ? `/delivery/supervisor/drivers/${driverId}` : '/delivery/supervisor/drivers';
            const method = driverId ? 'PUT' : 'POST';

            const data = {
                name: formData.get('name'),
                phone: formData.get('phone'),
                email: formData.get('email'),
                license_number: formData.get('license_number'),
                vehicle_type: formData.get('vehicle_type'),
                vehicle_plate: formData.get('vehicle_plate'),
                status: formData.get('status'),
                is_active: formData.get('is_active') ? true : false
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showAlert(result.message, 'success');
                    closeModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(result.message || 'حدث خطأ', 'error');
                }
            })
            .catch(error => {
                showAlert('حدث خطأ في الاتصال', 'error');
            });
        }

        // Delete Driver
        function deleteDriver(id, name) {
            if (!confirm(`هل أنت متأكد من حذف السائق "${name}"؟`)) {
                return;
            }

            fetch(`/delivery/supervisor/drivers/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showAlert(result.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(result.message || 'حدث خطأ', 'error');
                }
            })
            .catch(error => {
                showAlert('حدث خطأ في الاتصال', 'error');
            });
        }

        // Show Alert
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.className = `alert alert-${type} show`;
            alert.textContent = message;
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        // Close modal on outside click
        document.getElementById('driverModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
