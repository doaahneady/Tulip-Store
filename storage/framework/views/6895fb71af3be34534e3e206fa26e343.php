
<?php $__env->startSection('content'); ?>
<?php $title = 'مراجعة منتجات التجار'; $subtitle = 'اعتماد أو رفض المنتجات والصور المرفوعة من التجار'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="<?php echo e(route('dashboard.cs.trader-products')); ?>" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <input name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث بالاسم أو SKU أو الخصائص" class="w-72 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <select name="attr_type" class="px-4 py-2 rounded-xl border border-gray-200">
                <option value="">نوع الخاصية (الكل)</option>
                <?php $__currentLoopData = ['text','textarea','select','multiselect','checkbox','radio','date','number','color']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php if((string) request('attr_type') === (string) $t): echo 'selected'; endif; ?>><?php echo e($t); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition">بحث</button>
        </div>
        <a href="<?php echo e(route('dashboard.cs.trader-products')); ?>" class="text-sm text-indigo-600 hover:underline">إعادة ضبط</a>
    </form>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">منتجات بانتظار الاعتماد</h3>
        <span class="text-sm text-gray-500"><?php echo e(number_format($products->total())); ?></span>
    </div>

    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $imgs = $p->images ?? [];
                if (is_string($imgs)) {
                    $decoded = json_decode($imgs, true);
                    $imgs = is_array($decoded) ? $decoded : [];
                }
                $firstImg = is_array($imgs) && count($imgs) > 0 ? $imgs[0] : null;
            ?>
            <div class="border border-gray-200 rounded-2xl p-4">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center text-gray-400">
                            <?php
                                $coverUrl = $p->primary_image_url ?? null;
                            ?>
                            <img src="<?php echo e($coverUrl ?: '/images/tulip_store.jpg'); ?>" srcset="<?php echo e($p->primary_image_srcset ?? ''); ?>" loading="lazy" class="w-full h-full object-cover" alt="صورة المنتج" onerror="this.onerror=null;this.src='/images/tulip_store.jpg';">
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 truncate"><?php echo e($p->name); ?></h4>
                                <span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-800">pending</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                Trader: <?php echo e($p->trader?->name ?? ('#'.$p->trader_id)); ?> • Store: <?php echo e($p->store?->name ?? '-'); ?> • SKU: <?php echo e($p->sku ?? '-'); ?>

                            </div>
                            <?php if(is_array($imgs) && count($imgs) > 0): ?>
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <?php $__currentLoopData = array_slice($imgs, 0, 6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden">
                                            <?php
                                                $u = $img;
                                                if (!\Illuminate\Support\Str::startsWith($u, ['http://','https://','/'])) {
                                                    $u = \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($u,'/'));
                                                }
                                            ?>
                                            <img src="<?php echo e($u); ?>" class="w-full h-full object-cover" alt="صورة" onerror="this.onerror=null;this.src='/images/tulip_store.jpg'">
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(count($imgs) > 6): ?>
                                        <span class="text-xs text-gray-500">+<?php echo e(count($imgs) - 6); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                                $attrs = collect();
                                if (isset($p->attributes) && $p->attributes instanceof \Illuminate\Support\Collection) {
                                    $attrs = $p->attributes->where('is_custom', true)->values();
                                }
                            ?>
                            <?php if($attrs->count()): ?>
                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full text-xs">
                                        <thead>
                                            <tr class="text-gray-500">
                                                <th class="text-right py-2">الاسم</th>
                                                <th class="text-right py-2">النوع</th>
                                                <th class="text-right py-2">القيمة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $attrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="border-t border-gray-100">
                                                    <td class="py-2 text-gray-900 font-semibold"><?php echo e($a->name); ?></td>
                                                    <td class="py-2 text-gray-600"><?php echo e($a->type); ?></td>
                                                    <td class="py-2 text-gray-900"><?php echo e($a->value); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 lg:items-end">
                        <details class="w-full lg:w-96 bg-white border border-gray-200 rounded-xl p-2">
                            <summary class="cursor-pointer text-sm font-semibold text-indigo-700">تعديل بيانات المنتج</summary>
                            <form method="POST" action="<?php echo e(route('dashboard.cs.trader-products.update', $p)); ?>" enctype="multipart/form-data" class="mt-3 space-y-2">
                                <?php echo csrf_field(); ?>
                                <input name="name" value="<?php echo e($p->name); ?>" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm" placeholder="اسم المنتج">
                                <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm" placeholder="الوصف"><?php echo e($p->description); ?></textarea>
                                <div class="grid grid-cols-2 gap-2">
                                    <input name="price" type="number" step="0.01" min="0" value="<?php echo e($p->price); ?>" required class="px-3 py-2 rounded-lg border border-gray-200 text-sm" placeholder="السعر">
                                    <input name="stock_quantity" type="number" min="0" value="<?php echo e($p->stock_quantity ?? 0); ?>" class="px-3 py-2 rounded-lg border border-gray-200 text-sm" placeholder="الكمية">
                                </div>
                                <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm">
                                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition text-sm">
                                    <i class="fas fa-pen"></i>
                                    <span>حفظ التعديل</span>
                                </button>
                            </form>
                        </details>
                        <form method="POST" action="<?php echo e(route('dashboard.cs.trader-products.approve', $p)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700 transition">
                                <i class="fas fa-check"></i>
                                <span>اعتماد</span>
                            </button>
                        </form>
                        <form method="POST" action="<?php echo e(route('dashboard.cs.trader-products.reject', $p)); ?>" class="w-full lg:w-80">
                            <?php echo csrf_field(); ?>
                            <div class="flex items-center gap-2">
                                <input name="reason" required placeholder="سبب الرفض" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-200">
                                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition">
                                    <i class="fas fa-times"></i>
                                    <span>رفض</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-10 text-center text-gray-500">لا توجد منتجات بانتظار الاعتماد</div>
        <?php endif; ?>
    </div>

    <div class="mt-6">
        <?php echo e($products->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/cs/trader-products.blade.php ENDPATH**/ ?>