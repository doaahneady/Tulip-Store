
<?php $__env->startSection('content'); ?>
<?php $title = 'تذاكر الدعم'; $subtitle = 'البحث والتصفية وإدارة التذاكر'; ?>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">تذكرة جديدة</h3>
    </div>
    <form method="POST" action="<?php echo e(route('dashboard.cs.tickets.create')); ?>" class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <?php echo csrf_field(); ?>
        <div>
            <label class="block text-sm text-gray-700 mb-1">بريد العميل</label>
            <input name="user_email" value="<?php echo e(old('user_email')); ?>" placeholder="customer@example.com" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">User ID (اختياري)</label>
            <input name="user_id" value="<?php echo e(old('user_id')); ?>" placeholder="123" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm text-gray-700 mb-1">الموضوع</label>
            <input name="subject" value="<?php echo e(old('subject')); ?>" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm text-gray-700 mb-1">الوصف</label>
            <textarea name="description" rows="3" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200"><?php echo e(old('description')); ?></textarea>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">الأولوية</label>
            <select name="priority" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <?php $__currentLoopData = ['low','medium','high','urgent']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p); ?>" <?php if(old('priority','medium') === $p): echo 'selected'; endif; ?>><?php echo e($p); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">التصنيف (اختياري)</label>
            <input name="category" value="<?php echo e(old('category')); ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-plus"></i>
                <span>إنشاء تذكرة</span>
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="<?php echo e(route('dashboard.cs.tickets')); ?>" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <input name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="بحث: رقم التذكرة / الموضوع / العميل" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">

        <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">كل الحالات</option>
            <?php $__currentLoopData = ['open','pending','in_progress','waiting_customer','resolved','closed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($st); ?>" <?php if(($filters['status'] ?? '') === $st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="priority" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">كل الأولويات</option>
            <?php $__currentLoopData = ['urgent','high','medium','low']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pr); ?>" <?php if(($filters['priority'] ?? '') === $pr): echo 'selected'; endif; ?>><?php echo e($pr); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="assigned_to" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الكل</option>
            <option value="<?php echo e(auth('employee')->id()); ?>" <?php if(request('assigned_to') == auth('employee')->id()): echo 'selected'; endif; ?>>تذاكري</option>
            <option value="0" <?php if(request('assigned_to') === '0'): echo 'selected'; endif; ?>>غير مخصصة</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-filter"></i>
                <span>تصفية</span>
            </button>
            <a href="<?php echo e(route('dashboard.cs.tickets')); ?>" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة التذاكر</h3>
        <span class="text-sm text-gray-500"><?php echo e($tickets->total()); ?> تذكرة</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">رقم</th>
                    <th class="text-right py-2">الموضوع</th>
                    <th class="text-right py-2">العميل</th>
                    <th class="text-right py-2">الأولوية</th>
                    <th class="text-right py-2">الحالة</th>
                    <th class="text-right py-2">المسؤول</th>
                    <th class="text-right py-2">تاريخ</th>
                    <th class="text-right py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $ticketSubject = $t->subject ?? '';
                        if (is_array($ticketSubject)) {
                            $ticketSubject = $ticketSubject['ar'] ?? ($ticketSubject['en'] ?? '');
                        }
                        $customerName = optional($t->user)->name ?? null;
                        if (is_array($customerName)) {
                            $customerName = $customerName['ar'] ?? ($customerName['en'] ?? '');
                        }
                    ?>
                    <tr class="border-t border-gray-100 align-top">
                        <td class="py-3 text-gray-900 font-semibold"><?php echo e($t->ticket_number); ?></td>
                        <td class="py-3 text-gray-800">
                            <div class="font-semibold"><?php echo e($ticketSubject); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e(\Illuminate\Support\Str::limit($t->description, 70)); ?></div>
                        </td>
                        <td class="py-3 text-gray-700">
                            <div class="font-semibold"><?php echo e($customerName ?: ('User #'.$t->user_id)); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e(optional($t->user)->email); ?></div>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700"><?php echo e($t->priority); ?></span>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs bg-indigo-100 text-indigo-700"><?php echo e($t->status); ?></span>
                        </td>
                        <td class="py-3 text-gray-700">
                            <?php echo e(optional($t->assignedTo)->full_name ?? ($t->assigned_to ? ('#'.$t->assigned_to) : 'غير مخصص')); ?>

                        </td>
                        <td class="py-3 text-gray-600">
                            <div><?php echo e($t->created_at?->format('Y-m-d')); ?></div>
                            <div class="text-xs text-gray-400"><?php echo e($t->created_at?->diffForHumans()); ?></div>
                        </td>
                        <td class="py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="<?php echo e(route('dashboard.cs.tickets.show', $t->id)); ?>" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs text-gray-700 hover:bg-gray-50">فتح</a>

                                <?php if(empty($t->assigned_to)): ?>
                                    <form method="POST" action="<?php echo e(route('dashboard.cs.tickets.assign-to-me', $t->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">تخصيص لي</button>
                                    </form>
                                <?php endif; ?>

                                <?php if(in_array($t->status, ['open','pending','in_progress','waiting_customer'], true)): ?>
                                    <form method="POST" action="<?php echo e(route('dashboard.cs.tickets.resolve', $t->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700">حل</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="py-10 text-center text-gray-500">لا توجد نتائج</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <?php echo e($tickets->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/cs/tickets.blade.php ENDPATH**/ ?>