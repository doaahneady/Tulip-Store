<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo e($order->order_number); ?></title>
      <!-- fav icon -->
        <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
            <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">

    <style>
        body { font-family: "DejaVu Sans", sans-serif; margin: 0; padding: 24px; color: #111827; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; gap: 16px; margin-bottom: 18px; }
        .brand { font-weight: 600; letter-spacing: 0.6px; color: #0D464C; font-size: 18px; font-family: "DejaVu Sans", sans-serif; }
        .muted { color:#6b7280; font-size: 12px; }
        .pill { display:inline-block; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .pill--paid { background:#dcfce7; color:#166534; }
        .pill--pending { background:#fff7ed; color:#9a3412; }
        .pill--failed { background:#fee2e2; color:#b91c1c; }
        .grid { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 14px 0 18px; }
        .card { border: 1px solid #e5e7eb; border-radius: 14px; padding: 14px; }
        .card h3 { margin: 0 0 10px; font-size: 13px; color:#111827; }
        .row { display:flex; justify-content:space-between; gap: 10px; margin: 6px 0; font-size: 12px; }
        table { width:100%; border-collapse: collapse; border:1px solid #e5e7eb; border-radius: 14px; overflow:hidden; }
        th { text-align:left; background:#f9fafb; padding: 10px 12px; font-size: 12px; border-bottom:1px solid #e5e7eb; }
        td { padding: 10px 12px; font-size: 12px; border-bottom:1px solid #f1f5f9; }
        .totals { width: 100%; margin-top: 12px; }
        .totals .row { font-size: 13px; }
        .totals .row strong { font-size: 14px; }
        .right { text-align:right; }
        .bidi { unicode-bidi: isolate; }
        .rtl { direction: rtl; text-align: right; unicode-bidi: isolate; }
        .arabic { direction: rtl; text-align: right; unicode-bidi: isolate; }
        .footer { margin-top: 18px; padding-top: 12px; border-top:1px dashed #e5e7eb; font-size: 11px; color:#6b7280; }
    </style>
</head>
<body>
    <?php
        $total = (float) ($order->total ?? $order->total_amount ?? 0);
        $subtotal = (float) ($order->subtotal ?? 0);
        $delivery = (float) ($order->delivery_cost ?? $order->shipping_cost ?? 0);
        $paymentStatus = (string) ($order->payment_status ?? 'pending');
        $payMethod = (string) ($order->payment_method ?? '-');
        $customer = $order->user?->name ?? $order->recipient_name ?? 'Customer';
        $currencySymbol = '$';
        $invoiceLogoPath = public_path('images/tulip_logo.png');
        $invoiceLogoData = is_file($invoiceLogoPath) ? 'data:image/png;base64,'.base64_encode((string) file_get_contents($invoiceLogoPath)) : null;
        $logoGirlPath = public_path('images/logo-girl.jpg');
        $logoGirlData = is_file($logoGirlPath) ? 'data:image/jpeg;base64,'.base64_encode((string) file_get_contents($logoGirlPath)) : null;
    ?>

    <div class="header">
        <div>
            <?php if($logoGirlData): ?>
                <div class="brand" style="font-size:22px; display:flex; align-items:center; gap:2px;">   
                    <span style="color:#F05928;">T</span><img src="<?php echo e($logoGirlData); ?>" alt="" style="height:1.1em; width:auto; vertical-align:middle; display:inline-block;"><span>LIP</span>
                </div>
            <?php elseif($invoiceLogoData): ?>
                <img src="<?php echo e($invoiceLogoData); ?>" alt="Tulip" style="height:56px; width:auto; max-width:260px; display:block;">
            <?php else: ?>
                <div class="brand" style="font-size:22px;">TULIP</div>
            <?php endif; ?>
            <div class="muted" style="margin-top:4px;">Invoice</div>
        </div>
        <div class="right">
            <div style="font-weight:800; font-size: 16px;"><?php echo e($order->order_number); ?></div>
            <div class="muted"><?php echo e(optional($order->created_at)->format('Y-m-d H:i')); ?></div>
            <div style="margin-top:8px;">
                <span class="pill <?php echo e($paymentStatus === 'paid' ? 'pill--paid' : ($paymentStatus === 'failed' ? 'pill--failed' : 'pill--pending')); ?>">
                    Payment: <?php echo e(ucfirst($paymentStatus)); ?>

                </span>
            </div>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Customer</h3>
            <div class="row"><span>Name :</span><span class="bidi" style="direction:rtl;unicode-bidi:embed;"><bdi lang="ar"><?php echo e($customer); ?></bdi></span></div>
            <div class="row"><span>Phone :</span><span class="bidi"><?php echo e($order->phone ?? '-'); ?></span></div>
            <div class="row"><span>Email :</span><span class="bidi"><?php echo e($order->user?->email ?? '-'); ?></span></div>
        </div>
        <div class="card">
            <h3>Delivery</h3>
            <div class="row"><span>Delivery type :</span><span class="bidi"><?php echo e($order->delivery_method ?? '-'); ?></span></div>
            <div class="row"><span>Estimated delivery :</span><span class="bidi"><?php echo e($order->estimated_delivery ? \Carbon\Carbon::parse($order->estimated_delivery)->format('Y-m-d') : '-'); ?></span></div>
            <div class="row"><span>Address :</span><span class="bidi" style="direction:rtl;unicode-bidi:embed;"><bdi lang="ar"><?php echo e($order->village ?? '-'); ?><?php echo e($order->address_note ? ' - '.$order->address_note : ''); ?></bdi></span></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:55%;">Item</th>
                <th style="width:15%;">Qty</th>
                <th style="width:15%;" class="right">Unit</th>
                <th style="width:15%;" class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $unit = (float) ($item->unit_price ?? $item->price ?? 0);
                    $line = (float) ($item->total_price ?? $item->subtotal ?? ($unit * (int) ($item->quantity ?? 0)));
                ?>
                <tr>
                    <td class="bidi" style="direction:rtl;unicode-bidi:embed;"><bdi lang="ar"><?php echo e($item->product->name ?? $item->product_name ?? ('Product #'.$item->product_id)); ?></bdi></td>
                    <td><?php echo e((int) ($item->quantity ?? 0)); ?></td>
                    <td class="right"><?php echo e(number_format($unit, 2)); ?> <?php echo e($currencySymbol); ?></td>
                    <td class="right"><?php echo e(number_format($line, 2)); ?> <?php echo e($currencySymbol); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="totals">
        <div class="row"><span>Subtotal :</span><span><?php echo e(number_format($subtotal, 2)); ?> <?php echo e($currencySymbol); ?></span></div>
        <?php if($order->discount_amount > 0 && $order->couponUsage): ?>
        <div class="row" style="background:#f0f9ff; padding:8px; border-radius:6px; margin:4px 0;">
            <span style="color:#2a7080;">
                <strong>
                    🏷️ Coupon discount 
                    <?php if($order->couponUsage->coupon): ?>
                        (<?php echo e($order->couponUsage->coupon->code); ?>)
                    <?php endif; ?>:
                </strong>
            </span>
            <span style="color:#16a34a; font-weight:bold;">-<?php echo e(number_format($order->discount_amount, 2)); ?> <?php echo e($currencySymbol); ?></span>
        </div>
        <?php endif; ?>
        <div class="row"><span>Delivery fee :</span><span><?php echo e(number_format($delivery, 2)); ?> <?php echo e($currencySymbol); ?></span></div>
        <div class="row"><span>Payment method :</span><span class="bidi"><?php echo e($payMethod === 'payroll' ? 'Payroll' : ucfirst($payMethod)); ?></span></div>
        <div class="row" style="border-top:1px solid #e5e7eb; padding-top:10px; margin-top:10px;">
            <span><strong>Grand total :</strong></span>
            <span><strong><?php echo e(number_format($total, 2)); ?> <?php echo e($currencySymbol); ?></strong></span>
        </div>
    </div>

    <div class="grid" style="margin-top:16px;">
        <div class="card">
            <h3>Delivery representative signature</h3>
            <div style="border:1px solid #e5e7eb; border-radius:8px; padding:12px; background:#fafafa; min-height:80px;">
                <?php if(!empty($order->driver_delivery_signature)): ?>
                    <img src="<?php echo e($order->driver_delivery_signature); ?>" alt="Driver signature" style="max-width:100%; max-height:80px;">
                <?php else: ?>
                    <div style="border-bottom:2px dashed #d1d5db; height:50px;"></div>
                    <span class="muted" style="font-size:10px;">Sign on driver dashboard when order delivered</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <h3>Customer signature</h3>
            <div style="border:1px solid #e5e7eb; border-radius:8px; padding:12px; background:#fafafa; min-height:80px;">
                <?php if(!empty($order->customer_signature)): ?>
                    <img src="<?php echo e($order->customer_signature); ?>" alt="Customer signature" style="max-width:100%; max-height:80px;">
                <?php else: ?>
                    <div style="border-bottom:2px dashed #d1d5db; height:50px;"></div>
                    <span class="muted" style="font-size:10px;">Sign on driver dashboard when order delivered</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer">
        This invoice is generated electronically by Tulip Store.
    </div>
</body>
</html>

<?php /**PATH E:\Tulip-Store\resources\views/invoices/template-en.blade.php ENDPATH**/ ?>