<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>معلومات الشحن والتوصيل - متجر توليب</title>
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0; font-family:'El Messiri',sans-serif; background:#fff; min-height:100vh; display:flex; flex-direction:column;">

<?php if(View::exists('components.navbar')): ?>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<main style="flex:1;">
    <div class="container-custom" style="padding:3rem 1.5rem;">
        <div style="max-width:900px; margin:0 auto;">
            <div style="text-align:center; margin-bottom:3rem;">
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">الشحن والتوصيل</h1>
                <p style="font-size:1.1rem; color:#666;">خيارات شحن سريعة وموثوقة لباب منزلك</p>
            </div>

            <!-- Shipping Options -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem; margin-bottom:3rem;">
                <div style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; text-align:center;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-truck" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">شحن عادي</h3>
                    <p style="color:#666; margin-bottom:1rem;">من ٣ إلى ٧ أيام عمل</p>
                    <p style="color:#0D464C; font-weight:600; font-size:1.1rem;">مجاني للطلبات المؤهلة</p>
                </div>

                <div style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #0D464C; text-align:center; position:relative;">
                    <div style="position:absolute; top:-10px; right:20px; background:#0D464C; color:#fff; padding:0.25rem 0.75rem; border-radius:12px; font-size:0.85rem; font-weight:600;">الأكثر طلباً</div>
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-shipping-fast" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">شحن سريع</h3>
                    <p style="color:#666; margin-bottom:1rem;">من ١ إلى ٣ أيام عمل</p>
                    <p style="color:#0D464C; font-weight:600; font-size:1.1rem;">رسوم إضافية حسب المدينة</p>
                </div>

                <div style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; text-align:center;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-rocket" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">توصيل في نفس اليوم</h3>
                    <p style="color:#666; margin-bottom:1rem;">في نفس اليوم داخل المدينة المتاحة</p>
                    <p style="color:#0D464C; font-weight:600; font-size:1.1rem;">رسوم خاصة</p>
                </div>
            </div>

            <!-- Shipping Information Sections -->
            <div style="space-y:2rem;">
                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-globe" style="color:#0D464C;"></i>
                        مناطق الشحن
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">نقوم حالياً بالشحن إلى المناطق التالية:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li><strong>داخل المدينة:</strong> من ٣ إلى ٧ أيام عمل (مع عروض شحن مجاني لبعض الطلبات)</li>
                        <li><strong>المدن الأخرى:</strong> من ٧ إلى ١٤ يوم عمل حسب شركة الشحن</li>
                        <li><strong>دولي:</strong> متوفر لبعض الدول، والمدة تعتمد على شركة الشحن والجمارك</li>
                    </ul>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-box" style="color:#0D464C;"></i>
                        وقت تجهيز الطلب
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem;">يتم عادةً تجهيز الطلبات خلال <strong>١–٢ يوم عمل</strong> (باستثناء العطل الرسمية). بعد شحن الطلب، سيصلك رقم التتبع عبر البريد الإلكتروني أو الرسائل النصية.</p>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-map-marked-alt" style="color:#0D464C;"></i>
                        تتبع الطلب
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">بعد شحن طلبك، ستصلك المعلومات التالية:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>رسالة تأكيد تحتوي على رقم تتبع الشحنة</li>
                        <li>تحديثات لحالة الشحن وموقعها</li>
                        <li>تقدير مبدئي لتاريخ الوصول</li>
                        <li>إشعارات عبر الرسائل النصية (في حال تفعيلها)</li>
                    </ul>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-top:1rem;">يمكنك تتبع طلبك من خلال حسابك في الموقع أو باستخدام رقم التتبع المرسل لك.</p>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-dollar-sign" style="color:#0D464C;"></i>
                        رسوم الشحن
                    </h2>
                    <div style="color:#666; line-height:1.8; font-size:1.05rem;">
                        <p style="margin-bottom:1rem;"><strong>الشحن العادي:</strong> قد يكون مجانياً أو برسوم رمزية حسب قيمة الطلب والعروض المتاحة.</p>
                        <p style="margin-bottom:1rem;"><strong>الشحن السريع / نفس اليوم:</strong> برسوم إضافية تُحدد عند إتمام الطلب.</p>
                        <p><strong>الشحن الدولي:</strong> تُحتسب الرسوم عند إنهاء الطلب حسب الدولة والوزن.</p>
                    </div>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-exclamation-triangle" style="color:#0D464C;"></i>
                        ملاحظات مهمة
                    </h2>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>أوقات التوصيل تقديرية وقد تتأثر بالظروف الجوية أو ضغط العمل أو سياسات شركة الشحن.</li>
                        <li>من مسؤولية العميل التأكد من صحة عنوان الشحن وبيانات التواصل.</li>
                        <li>الطلبات تُشحن عادةً من الأحد إلى الخميس، باستثناء العطل الرسمية.</li>
                        <li>للشحن الدولي، يتحمل العميل أي رسوم جمركية أو ضرائب محلية.</li>
                    </ul>
                </section>

                <section style="background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-question-circle" style="color:#0D464C;"></i>
                        أسئلة حول الشحن؟
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1.5rem;">إذا كان لديك أي استفسار بخصوص الشحن أو تعديل على طلبك، تواصل معنا في أسرع وقت ممكن:</p>
                    <a href="/contact" style="display:inline-block; background:#0D464C; color:#fff; padding:0.75rem 1.5rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                        تواصل مع الدعم
                    </a>
                </section>
            </div>
        </div>
    </div>
</main>

<?php if(View::exists('components.footer')): ?>
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/pages/shipping.blade.php ENDPATH**/ ?>