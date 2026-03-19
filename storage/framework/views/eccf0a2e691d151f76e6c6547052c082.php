

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex flex-wrap items-center gap-2">
    <a href="<?php echo e(route('dashboard.admin.gifts')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-right"></i> رجوع لإدارة الهدايا</a>
</div>

<div class="gc-skin">
<style>
  .gc-skin :root{
    --gc-primary:#8b6914;--gc-accent:#d4af37;--gc-bg-cream:#fdfbf7;--gc-bg-warm:#f9f5ed;--gc-text:#2c2416;
  }
  .gc-skin{background:var(--gc-bg-cream); border-radius:18px; padding:0.25rem}
  .gc-skin .panel-card{background:#fff;border:1px solid #ede8df;border-radius:18px;box-shadow:0 10px 40px rgba(139,105,20,.08)}
  .gc-skin .panel-head{display:flex;align-items:center;gap:.8rem;margin-bottom:1rem;padding-bottom:.8rem;border-bottom:2px solid var(--gc-bg-warm)}
  .gc-skin .panel-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;background:linear-gradient(135deg,#d4af37,#c9a227)}
  .gc-skin .panel-title{font-family:'El Messiri',sans-serif;font-weight:800;color:var(--gc-text);font-size:1.15rem}
  .gc-skin .panel-sub{font-size:.85rem;color:#8a7d6d}
  .gc-skin .lbl{font-size:.85rem;color:#6b6255;font-weight:700}
  .gc-skin .form-input,.gc-skin .form-textarea,.gc-skin .form-select{
      border:2px solid #f0ebe0; border-radius:14px; padding:.85rem 1rem; background:var(--gc-bg-warm);
  }
  .gc-skin .form-input:focus,.gc-skin .form-textarea:focus,.gc-skin .form-select:focus{
      outline:none; border-color:var(--gc-accent); background:#fff; box-shadow:0 0 0 3px rgba(212,175,55,.18);
  }
  .gc-skin .btn-primary{
      background:linear-gradient(135deg,#2c2416,#4a3c28); color:#fff; border:none; border-radius:14px; padding:1rem; font-weight:800;
  }
  .gc-skin .btn-primary:hover{transform:translateY(-2px)}
  .gc-skin .btn-secondary{background:linear-gradient(135deg,#d4af37,#c9a227); color:#2c2416; border:none; border-radius:12px}
  .gc-skin .grid>div{transition:transform .25s ease}
  .gc-skin .grid>div:hover{transform:translateY(-2px)}
  @media (max-width: 1024px){
      .gc-skin .grid{grid-template-columns:1fr!important}
  }
</style>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">Gifts Creation Stuff — أدوات إنشاء الهدايا</h2>
        <div class="text-sm text-gray-500">Ready Gifts • Boxes • Bouquet Sizes • Fillers • Wrappings • Ribbons • Cards</div>
    </div>
    <div class="mt-4 flex flex-wrap gap-2">
        <button class="btn btn-secondary" onclick="filterCreation('boxes')"><i class="fas fa-box"></i> أقسام إنشاء الصناديق</button>
        <button class="btn btn-secondary" onclick="filterCreation('flowers')"><i class="fas fa-seedling"></i> أقسام إنشاء الزهور</button>
        <button class="btn btn-ghost" onclick="filterCreation('all')">عرض الكل</button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <div id="section-ready-gift" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إضافة هدية جاهزة</h3></div>
        <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.store')); ?>" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input name="name" class="form-input w-full" placeholder="الاسم" required>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="price" type="number" step="0.01" min="0" class="form-input w-full" placeholder="السعر" required>
                <input name="stock_quantity" type="number" min="0" class="form-input w-full" placeholder="المخزون" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="category" class="form-input w-full" placeholder="التصنيف (مثال: birthday)">
                <input name="occasion" class="form-input w-full" placeholder="المناسبة (اختياري)">
            </div>
            <textarea name="description" class="form-textarea w-full" rows="3" placeholder="الوصف"></textarea>
            <input name="image" type="file" class="form-input w-full" accept="image/*" required>
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_featured" value="1"><span>مميز</span></label>
            </div>
            <button type="submit" class="btn btn-primary w-full">حفظ</button>
        </form>
    </div>

    <div id="section-box" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 panel-card">
        <div class="panel-head"><div class="panel-icon box"><i class="fas fa-box-open"></i></div><div><div class="panel-title">إضافة صندوق هدية</div><div class="panel-sub">متوافق مع صفحة Box Arrangement</div></div></div>
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إضافة صندوق (Box)</h3></div>
        <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.boxes.store')); ?>" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input name="name" class="form-input w-full" placeholder="الاسم" required>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="size" class="form-input w-full" placeholder="الحجم (small/medium/large/xl)" required>
                <input name="max_items" type="number" min="1" class="form-input w-full" placeholder="حد العناصر" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input name="price" type="number" step="0.01" min="0" class="form-input w-full" placeholder="السعر" required>
                <input name="stock" type="number" min="0" class="form-input w-full" placeholder="المخزون" required>
                <input name="sort_order" type="number" min="0" class="form-input w-full" placeholder="الترتيب">
            </div>
            <textarea name="description" class="form-textarea w-full" rows="2" placeholder="الوصف"></textarea>
            <input name="image" type="file" class="form-input w-full" accept="image/*" required>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
            <button type="submit" class="btn btn-primary w-full">حفظ</button>
        </form>
    </div>

    <div id="section-bouquet-size" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 panel-card">
        <div class="panel-head"><div class="panel-icon flower"><i class="fas fa-seedling"></i></div><div><div class="panel-title">إضافة حجم باقة</div><div class="panel-sub">متوافق مع صفحة Flower Bouquet</div></div></div>
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إضافة حجم باقة (Bouquet Size)</h3></div>
        <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.boxes.store')); ?>" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="context" value="bouquet">
            <input name="name" class="form-input w-full" placeholder="اسم الحجم (Small/Medium/Large)" required>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="size" class="form-input w-full" placeholder="الرمز (small/medium/large/xl)" required>
                <input name="max_items" type="number" min="1" class="form-input w-full" placeholder="عدد الزهور الموصى به" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input name="price" type="number" step="0.01" min="0" class="form-input w-full" placeholder="السعر" required>
                <input name="stock" type="number" min="0" class="form-input w-full" placeholder="المخزون" required>
                <input name="sort_order" type="number" min="0" class="form-input w-full" placeholder="الترتيب">
            </div>
            <textarea name="description" class="form-textarea w-full" rows="2" placeholder="الوصف (اختياري)"></textarea>
            <input name="image" type="file" class="form-input w-full" accept="image/*">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
            <button type="submit" class="btn btn-primary w-full">حفظ</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <div id="section-filler" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 panel-card">
        <div class="panel-head"><div class="panel-icon flower"><i class="fas fa-seedling"></i></div><div><div class="panel-title">إضافة عنصر</div><div class="panel-sub">لإظهاره كزهور اختر القسم Flower</div></div></div>
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إضافة عنصر (Flowers/Chocolate/..)</h3></div>
        <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.fillers.store')); ?>" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input name="name" class="form-input w-full" placeholder="الاسم" required>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <select name="category" class="form-select w-full" required>
                    <option value="" disabled selected>القسم</option>
                    <option value="flower">Flowers</option>
                    <option value="chocolate">Chocolate</option>
                    <option value="perfume">Perfume</option>
                    <option value="accessory">Accessory</option>
                    <option value="candy">Candy</option>
                    <option value="other">Other</option>
                </select>
                <input name="sort_order" type="number" min="0" class="form-input w-full" placeholder="الترتيب">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="price" type="number" step="0.01" min="0" class="form-input w-full" placeholder="السعر" required>
                <input name="stock" type="number" min="0" class="form-input w-full" placeholder="المخزون" required>
            </div>
            <textarea name="description" class="form-textarea w-full" rows="2" placeholder="الوصف"></textarea>
            <input name="image" type="file" class="form-input w-full" accept="image/*" required>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
            <button type="submit" class="btn btn-primary w-full">حفظ</button>
        </form>
    </div>

    <div id="section-wrapping" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إضافة تغليف</h3></div>
        <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.wrappings.store')); ?>" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input name="name" class="form-input w-full" placeholder="الاسم" required>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input name="price" type="number" step="0.01" min="0" class="form-input w_full" placeholder="السعر" required>
                <input name="color" class="form-input w-full" placeholder="اللون">
                <input name="pattern" class="form-input w-full" placeholder="النقشة">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="sort_order" type="number" min="0" class="form-input w-full" placeholder="الترتيب">
                <input name="image" type="file" class="form-input w-full" accept="image/*">
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
            <button type="submit" class="btn btn-primary w-full">حفظ</button>
        </form>
    </div>

    <div id="section-ribbon" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إضافة شريط</h3></div>
        <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.ribbons.store')); ?>" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input name="name" class="form-input w-full" placeholder="الاسم" required>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input name="price" type="number" step="0.01" min="0" class="form-input w-full" placeholder="السعر" required>
                <input name="color" class="form-input w-full" placeholder="اللون">
                <input name="sort_order" type="number" min="0" class="form-input w-full" placeholder="الترتيب">
            </div>
            <input name="image" type="file" class="form-input w-full" accept="image/*">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
            <button type="submit" class="btn btn-primary w-full">حفظ</button>
        </form>
    </div>

    <div id="section-card" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إضافة بطاقة</h3></div>
        <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.cards.store')); ?>" enctype="multipart/form-data" class="space-y-3">
            <?php echo csrf_field(); ?>
            <input name="name" class="form-input w-full" placeholder="الاسم" required>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input name="price" type="number" step="0.01" min="0" class="form-input w-full" placeholder="السعر" required>
                <input name="occasion" class="form-input w-full" placeholder="المناسبة">
                <input name="sort_order" type="number" min="0" class="form-input w-full" placeholder="الترتيب">
            </div>
            <input name="image" type="file" class="form-input w-full" accept="image/*">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
            <button type="submit" class="btn btn-primary w-full">حفظ</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
    <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-gray-900">إنشاء هدية جاهزة من العناصر</h3></div>
    <form method="POST" action="<?php echo e(route('dashboard.admin.gifts.assemble')); ?>" enctype="multipart/form-data" class="space-y-3">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input name="name" class="form-input w-full" placeholder="اسم الهدية" required>
            <select name="box_id" class="form-select w-full" required>
                <option value="" disabled selected>اختر الصندوق / الحجم</option>
                <?php $__currentLoopData = ($boxes ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?> — <?php echo e(number_format((float) $b->price, 2)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input name="image" type="file" class="form-input w-full" accept="image/*">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-2">اختر العناصر</label>
            <select name="filler_ids[]" class="form-select w-full" multiple size="8" required>
                <?php $__currentLoopData = ($fillers ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($f->id); ?>"><?php echo e($f->name); ?> — <?php echo e($f->category); ?> — <?php echo e(number_format((float) $f->price, 2)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input name="category" class="form-input w-full" placeholder="التصنيف (اختياري)">
            <input name="occasion" class="form-input w-full" placeholder="المناسبة (اختياري)">
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_active" value="1" checked><span>نشط</span></label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" name="is_featured" value="1"><span>مميز</span></label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">إنشاء</button>
    </form>
</div>
</div> <!-- /gc-skin -->
<?php $__env->startPush('scripts'); ?>
<script>
  function filterCreation(type) {
    const boxIds = ['section-box', 'section-wrapping', 'section-ribbon', 'section-card'];
    const flowerIds = ['section-bouquet-size', 'section-filler', 'section-card'];
    const allIds = ['section-ready-gift', 'section-box','section-wrapping','section-ribbon','section-card','section-bouquet-size','section-filler'];
    const showSet = new Set(type === 'boxes' ? boxIds : (type === 'flowers' ? flowerIds : allIds));
    allIds.forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;
      el.style.display = showSet.has(id) ? 'block' : 'none';
    });
    const firstId = [...showSet][0];
    const target = document.getElementById(firstId);
    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'Gifts Creation Stuff', 'subtitle' => 'إضافة الصناديق والزهور والتغليف والبطاقات والهدايا الجاهزة'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/dashboards/super-admin/gifts-creation.blade.php ENDPATH**/ ?>