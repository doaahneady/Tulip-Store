

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="<?php echo e(route('dashboard.admin.mart.daily-prices.save')); ?>" class="space-y-6" id="dailyForm">
        <?php echo csrf_field(); ?>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <input type="text" id="searchInput" class="form-input w-64" placeholder="بحث باسم المنتج">
                <select id="categoryFilter" class="form-select w-48">
                    <option value="">كل التصنيفات</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('mart.daily-prices')); ?>" class="btn btn-secondary" target="_blank">عرض صفحة الأسعار</a>
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 overflow-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="p-3 text-right">الصورة</th>
                        <th class="p-3 text-right">الاسم</th>
                        <th class="p-3 text-right">التصنيف</th>
                        <th class="p-3 text-right">الوحدة</th>
                        <th class="p-3 text-right">المنشأ</th>
                        <th class="p-3 text-right">السعر</th>
                        <th class="p-3 text-right">سعر مخفض</th>
                        <th class="p-3 text-right">نشط</th>
                    </tr>
                </thead>
                <tbody id="itemsBody" class="text-sm"></tbody>
            </table>
        </div>
    </form>
</div>

<script>
const products = <?php echo json_encode($productsPayload, 15, 512) ?>;

function renderRows() {
    const tbody = document.getElementById('itemsBody');
    const q = document.getElementById('searchInput').value.trim().toLowerCase();
    const cat = document.getElementById('categoryFilter').value;
    const rows = products.filter(p => (!cat || String(p.category_id) === cat) && (p.name.toLowerCase().includes(q)));
    tbody.innerHTML = rows.map(p => `
        <tr class="border-t">
            <td class="p-3">
                ${p.image ? `<img src="${location.origin + '/storage/' + p.image}" alt="${p.name}" style="width:44px;height:44px;border-radius:10px;object-fit:cover;border:1px solid #eee;">` : `<div style="width:44px;height:44px;border-radius:10px;background:#f1f5f9;border:1px solid #e5e7eb"></div>`}
            </td>
            <td class="p-3 font-semibold">${p.name}</td>
            <td class="p-3">${p.category_name || ''}</td>
            <td class="p-3"><input type="text" class="form-input w-28" value="${p.unit || ''}" data-id="${p.id}" data-field="unit"></td>
            <td class="p-3"><input type="text" class="form-input w-36" value="${p.origin || ''}" data-id="${p.id}" data-field="origin"></td>
            <td class="p-3"><input type="number" step="0.01" min="0" class="form-input w-28" value="${p.price ?? ''}" data-id="${p.id}" data-field="price"></td>
            <td class="p-3"><input type="number" step="0.01" min="0" class="form-input w-28" value="${p.discount_price ?? ''}" data-id="${p.id}" data-field="discount_price"></td>
            <td class="p-3">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" ${p.is_active ? 'checked' : ''} data-id="${p.id}" data-field="is_active">
                    <span>${p.is_active ? 'نشط' : 'معطل'}</span>
                </label>
            </td>
        </tr>
    `).join('');
}

document.getElementById('searchInput').addEventListener('input', renderRows);
document.getElementById('categoryFilter').addEventListener('change', renderRows);
renderRows();

document.getElementById('dailyForm').addEventListener('submit', function (e) {
    const inputs = Array.from(document.querySelectorAll('#itemsBody input'));
    const byId = {};
    inputs.forEach(inp => {
        const id = parseInt(inp.getAttribute('data-id'));
        const field = inp.getAttribute('data-field');
        if (!byId[id]) byId[id] = { id };
        if (field === 'is_active') {
            byId[id][field] = inp.checked ? 1 : 0;
        } else {
            byId[id][field] = inp.value;
        }
    });
    const items = Object.values(byId);
    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'items';
    hidden.value = JSON.stringify(items);
    this.appendChild(hidden);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'أسعار يومية', 'subtitle' => 'إدارة أسعار كل منتجات المارت'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/super-admin/mart-daily-prices.blade.php ENDPATH**/ ?>