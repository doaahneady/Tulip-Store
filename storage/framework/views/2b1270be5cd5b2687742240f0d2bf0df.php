<?php $__env->startSection('content'); ?>
<?php $title = 'مراجعة منتجات التجار'; $subtitle = 'اعتماد أو رفض المنتجات والصور المرفوعة من التجار'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="<?php echo e(route('dashboard.cs.trader-products')); ?>" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <input name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث بالاسم أو SKU" class="w-72 px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
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
                            <?php if($firstImg): ?>
                                <img src="<?php echo e(\Illuminate\Support\Str::startsWith($firstImg, ['http://','https://','/']) ? $firstImg : asset($firstImg)); ?>" class="w-full h-full object-cover" alt="">
                            <?php else: ?>
                                <i class="fas fa-image"></i>
                            <?php endif; ?>
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
                                            <img src="<?php echo e(\Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset($img)); ?>" class="w-full h-full object-cover" alt="">
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(count($imgs) > 6): ?>
                                        <span class="text-xs text-gray-500">+<?php echo e(count($imgs) - 6); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 lg:items-end">
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


<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tulip-Store\resources\views/dashboards/cs/trader-products.blade.php ENDPATH**/ ?>