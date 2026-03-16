
<?php $__env->startSection('content'); ?>
<?php $title = 'تتبع السائقين المباشر'; $subtitle = 'مواقع السائقين والمهام النشطة'; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">الخريطة المباشرة</h3>
            <button id="refresh-btn" class="text-sm text-indigo-600">تحديث</button>
        </div>
        <div id="map" class="w-full h-96 rounded-xl border border-gray-200"></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">السائقون</h3>
        <div id="driver-list" class="space-y-3">
            <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 border rounded-xl">
                    <div>
                        <div class="font-semibold text-gray-900"><?php echo e($driver['name']); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($driver['availability']); ?></div>
                    </div>
                    <div class="text-xs text-gray-500">
                        <?php if($driver['current_assignment']): ?>
                            #<?php echo e(optional($driver['current_assignment'])->order_id); ?>

                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    if (typeof window.L === 'undefined') {
        const el = document.getElementById('map');
        if (el) {
            el.innerHTML = '<div class="h-full w-full flex items-center justify-center text-sm text-gray-500">فشل تحميل الخريطة (Leaflet). تحقق من الاتصال بالإنترنت.</div>';
        }
    } else {
        const map = L.map('map').setView([33.5138, 36.2765], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '' }).addTo(map);
        let markers = {};
        setTimeout(() => map.invalidateSize(), 200);

        function loadLocations() {
            fetch('<?php echo e(route('dashboard.supervisor.api.driver-locations')); ?>')
                .then(r => r.json())
                .then(data => {
                    Object.values(markers).forEach(m => map.removeLayer(m));
                    markers = {};
                    data.forEach(d => {
                        if (d.lat !== null && d.lat !== '' && d.lng !== null && d.lng !== '') {
                            const icon = L.divIcon({
                                className: 'driver-marker',
                                html: '<div class="w-6 h-6 rounded-full bg-indigo-600 border-2 border-white shadow"></div>',
                                iconSize: [24, 24],
                                iconAnchor: [12, 12]
                            });
                            const marker = L.marker([d.lat, d.lng], { icon }).addTo(map);
                            marker.bindPopup('<div class="text-sm font-semibold">'+d.name+'</div><div class="text-xs text-gray-500">'+(d.availability || '')+'</div>');
                            markers[d.id] = marker;
                        }
                    });
                    const group = new L.featureGroup(Object.values(markers));
                    if (Object.values(markers).length) map.fitBounds(group.getBounds().pad(0.2));
                    setTimeout(() => map.invalidateSize(), 0);
                });
        }
        document.getElementById('refresh-btn').addEventListener('click', loadLocations);
        loadLocations();
        setInterval(loadLocations, 30000);
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/supervisor/live-tracking.blade.php ENDPATH**/ ?>