
<?php $__env->startSection('content'); ?>
<?php $title = 'Cross-Department Activity Logs'; $subtitle = 'HR, Finance, Support, Drivers'; ?>
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-clipboard-list text-indigo-600"></i>
            <span class="text-sm text-gray-700">Domains</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('dashboard.admin.activity-logs')); ?>" class="btn btn-secondary btn-sm <?php echo e(!$domain ? 'opacity-100' : 'opacity-70'); ?>">All</a>
            <a href="<?php echo e(route('dashboard.admin.activity-logs', ['domain' => 'hr'])); ?>" class="btn btn-secondary btn-sm <?php echo e($domain==='hr' ? 'opacity-100' : 'opacity-70'); ?>">HR</a>
            <a href="<?php echo e(route('dashboard.admin.activity-logs', ['domain' => 'finance'])); ?>" class="btn btn-secondary btn-sm <?php echo e($domain==='finance' ? 'opacity-100' : 'opacity-70'); ?>">Finance</a>
            <a href="<?php echo e(route('dashboard.admin.activity-logs', ['domain' => 'support'])); ?>" class="btn btn-secondary btn-sm <?php echo e($domain==='support' ? 'opacity-100' : 'opacity-70'); ?>">Support</a>
            <a href="<?php echo e(route('dashboard.admin.activity-logs', ['domain' => 'drivers'])); ?>" class="btn btn-secondary btn-sm <?php echo e($domain==='drivers' ? 'opacity-100' : 'opacity-70'); ?>">Drivers</a>
        </div>
    </div>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">Activity Logs</h3>
        <a href="<?php echo e(route('dashboard.admin.audit-logs')); ?>" class="text-sm text-indigo-600">Open full audit logs</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">Action</th>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Model</th>
                    <th class="px-4 py-2 text-left">Created</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo e($log->action); ?></td>
                        <td class="px-4 py-2"><?php echo e($log->actor_display_name); ?></td>
                        <td class="px-4 py-2"><?php echo e($log->model_type ?? '-'); ?> #<?php echo e($log->model_id ?? '-'); ?></td>
                        <td class="px-4 py-2"><?php echo e($log->created_at); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">No logs</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-4">
        <?php if(method_exists(($logs ?? null),'links')): ?>
            <?php echo e($logs->links()); ?>

        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/activity-logs.blade.php ENDPATH**/ ?>