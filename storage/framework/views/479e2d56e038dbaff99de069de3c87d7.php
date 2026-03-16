<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title><?php echo e($metadata['title'] ?? 'Export Report'); ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "El Messiri", sans-serif; color: #111827; margin: 24px; }
        .header { margin-bottom: 16px; }
        .title { font-size: 20px; font-weight: 700; margin: 0 0 4px 0; }
        .subtitle { font-size: 12px; color: #6B7280; margin: 0 0 8px 0; }
        .meta { font-size: 11px; color: #6B7280; margin: 0 0 16px 0; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #F9FAFB; border-bottom: 1px solid #E5E7EB; padding: 8px 10px; font-size: 12px; text-align: right; color: #374151; }
        tbody td { border-bottom: 1px solid #F3F4F6; padding: 8px 10px; font-size: 12px; text-align: right; color: #111827; }
        tbody tr:nth-child(even) { background: #FAFAFA; }
        .count { font-size: 11px; color: #374151; margin-top: 4px; }
    </style>
</head>
<body>
    <?php
        $columnsKeys = array_keys($columns ?? []);
        $isKey = fn($name) => in_array($name, $columnsKeys, true);
        $sumNumeric = function($key) use ($data) {
            $total = 0;
            foreach ($data as $row) {
                $value = data_get($row, $key);
                if (is_numeric($value)) { $total += $value; }
            }
            return $total;
        };
        $countBy = function($key) use ($data) {
            $map = [];
            foreach ($data as $row) {
                $value = data_get($row, $key);
                $label = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value ?? 'غير محدد');
                $map[$label] = ($map[$label] ?? 0) + 1;
            }
            ksort($map);
            return $map;
        };
    ?>

    <div class="header">
        <h1 class="title"><?php echo e($metadata['title'] ?? 'Export Report'); ?></h1>
        <?php if(!empty($metadata['subtitle'])): ?>
            <p class="subtitle"><?php echo e($metadata['subtitle']); ?></p>
        <?php endif; ?>
        <p class="meta">
            <?php echo e($metadata['company_name'] ?? config('app.name', 'Tulip Store')); ?>

            • <?php echo e($metadata['generated_at'] ?? now()->format('Y-m-d H:i:s')); ?>

            <?php if(!empty($metadata['generated_by'])): ?> • بواسطة: <?php echo e($metadata['generated_by']); ?> <?php endif; ?>
        </p>
        <div class="count">عدد السجلات: <?php echo e($data->count()); ?></div>
        <?php if(!empty($metadata['date_from']) || !empty($metadata['date_to'])): ?>
            <div class="meta">
                نطاق التاريخ: <?php echo e($metadata['date_from'] ?? '—'); ?> إلى <?php echo e($metadata['date_to'] ?? '—'); ?>

            </div>
        <?php endif; ?>
        <?php if(!empty($metadata['filters']) && is_array($metadata['filters'])): ?>
            <div class="meta">
                عوامل التصفية:
                <?php $__currentLoopData = $metadata['filters']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($v !== null && $v !== ''): ?>
                        <span><?php echo e($k); ?>: <?php echo e(is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v); ?></span>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <div style="margin: 12px 0;">
        <?php if($isKey('amount')): ?>
            <div class="count">إجمالي المبالغ: $<?php echo e(number_format($sumNumeric('amount'), 2)); ?></div>
        <?php endif; ?>
        <?php if($isKey('price')): ?>
            <div class="count">إجمالي الأسعار: $<?php echo e(number_format($sumNumeric('price'), 2)); ?></div>
        <?php endif; ?>
        <?php if($isKey('salary')): ?>
            <div class="count">إجمالي الرواتب: $<?php echo e(number_format($sumNumeric('salary'), 2)); ?></div>
        <?php endif; ?>
    </div>

    <?php if($isKey('status')): ?>
        <?php $statusCounts = $countBy('status'); ?>
        <table style="margin-bottom: 16px;">
            <thead>
                <tr><th>الحالة</th><th>عدد السجلات</th></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $statusCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $cnt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($status); ?></td><td><?php echo e($cnt); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if($isKey('action')): ?>
        <?php $actionCounts = $countBy('action'); ?>
        <table style="margin-bottom: 16px;">
            <thead>
                <tr><th>الإجراء</th><th>عدد السجلات</th></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $actionCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action => $cnt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($action); ?></td><td><?php echo e($cnt); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if($isKey('type')): ?>
        <?php $typeCounts = $countBy('type'); ?>
        <table style="margin-bottom: 16px;">
            <thead>
                <tr><th>النوع</th><th>عدد السجلات</th></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $typeCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $cnt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($type); ?></td><td><?php echo e($cnt); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e($label); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $value = data_get($row, $key);
                            if (is_array($value)) { $value = json_encode($value, JSON_UNESCAPED_UNICODE); }
                        ?>
                        <td><?php echo e($value); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/dashboard/exports/pdf-template.blade.php ENDPATH**/ ?>