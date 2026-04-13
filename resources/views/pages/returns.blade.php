<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سياسة الإرجاع والاسترداد - متجر توليب</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
        <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0; font-family:'El Messiri',sans-serif; background:#fff; min-height:100vh; display:flex; flex-direction:column;">

@if(View::exists('components.navbar'))
    @include('components.navbar')
@endif

<main style="flex:1;">
    <div class="container-custom" style="padding:3rem 1.5rem;">
        <div style="max-width:900px; margin:0 auto;">
            <div style="text-align:center; margin-bottom:3rem;">
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">سياسة الإرجاع واسترداد المبلغ</h1>
                <p style="font-size:1.1rem; color:#666;">نهدف دائماً إلى ضمان رضاك عن تجربتك في متجر توليب</p>
            </div>

            <!-- Return Policy Sections -->
            <div style="space-y:2rem;">
                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-clock" style="color:#0D464C;"></i>
                        مدة الإرجاع
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem;">يمكنك إرجاع المنتجات خلال مدة تصل إلى <strong>٧ أيام</strong> من تاريخ استلام الطلب، بشرط أن تكون المنتجات مطابقة لشروط الإرجاع الموضحة في هذه الصفحة.</p>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-check-circle" style="color:#0D464C;"></i>
                        الشروط العامة لقبول الإرجاع
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">يجب أن تتوفر الشروط التالية في المنتج ليكون مقبولاً للإرجاع:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>أن يكون المنتج غير مستخدم أو متضرر أو مغسول.</li>
                        <li>وجود جميع الملصقات والبطاقات الأصلية على المنتج.</li>
                        <li>أن يكون المنتج في عبوته الأصلية وبحالة جيدة.</li>
                        <li>توفير فاتورة الشراء أو رقم الطلب.</li>
                        <li>ألا يكون التلف ناتجاً عن سوء استخدام من قبل العميل.</li>
                    </ul>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-times-circle" style="color:#0D464C;"></i>
                        المنتجات غير القابلة للإرجاع
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">لا يمكن إرجاع المنتجات التالية:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>المنتجات المصنّعة حسب الطلب أو المخصصة بشكل شخصي.</li>
                        <li>المنتجات المندرجة ضمن عروض "تخفيض نهائي" أو "لا تُستبدل ولا تُرد".</li>
                        <li>المنتجات ذات الطبيعة الاستهلاكية السريعة أو القابلة للتلف.</li>
                        <li>المنتجات الشخصية أو الحساسة التي تتعلق بالنظافة.</li>
                        <li>المنتجات المتضررة نتيجة سوء الاستخدام أو الحوادث.</li>
                    </ul>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-redo" style="color:#0D464C;"></i>
                        طريقة طلب الإرجاع
                    </h2>
                    <div style="color:#666; line-height:1.8; font-size:1.05rem;">
                        <p style="margin-bottom:1rem;">لإتمام طلب إرجاع، يرجى اتباع الخطوات التالية:</p>
                        <ol style="margin-left:1.5rem; line-height:2.5;">
                            <li><strong>التواصل معنا:</strong> قم بالتواصل مع خدمة العملاء أو عبر البريد returns@tulip-os.com مع ذكر رقم الطلب.</li>
                            <li><strong>الحصول على الموافقة:</strong> سنزوّدك برقم لطلب الإرجاع وتعليمات الإرسال.</li>
                            <li><strong>تغليف المنتج:</strong> ضع المنتج في عبوته الأصلية مع جميع الملحقات والملصقات.</li>
                            <li><strong>إرفاق البيانات:</strong> أرفق رقم طلب الإرجاع ونسخة من فاتورة الشراء إن أمكن.</li>
                            <li><strong>إرسال الشحنة:</strong> يتم تسليم المنتج لمندوب الشحن أو للعنوان المحدد من قبلنا.</li>
                            <li><strong>انتظار المعالجة:</strong> تتم مراجعة المنتج وإتمام الإجراءات خلال ٣–٥ أيام عمل من تاريخ استلامه.</li>
                        </ol>
                    </div>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-dollar-sign" style="color:#0D464C;"></i>
                        آلية استرداد المبلغ
                    </h2>
                    <div style="color:#666; line-height:1.8; font-size:1.05rem;">
                        <p style="margin-bottom:1rem;">بعد استلام المنتج والتحقق من حالته:</p>
                        <ul style="margin-left:1.5rem; line-height:2;">
                            <li>سيتم إشعارك باستلام المنتج المطلوب إرجاعه.</li>
                            <li>يتم استرداد المبلغ خلال مدة تقريبية من ٣–٧ أيام عمل.</li>
                            <li>يتم تحويل المبلغ إلى نفس وسيلة الدفع المستخدمة في الطلب.</li>
                            <li>رسوم الشحن غير مستردة إلا في حال كان الخطأ من طرفنا أو وجود عيب مصنعي.</li>
                            <li>سيصلك إشعار عند إتمام عملية الاسترداد.</li>
                        </ul>
                    </div>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-exchange-alt" style="color:#0D464C;"></i>
                        الاستبدال
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem;">يمكنك طلب استبدال المنتج بمقاس أو لون أو موديل آخر في حال توفره في المخزون، وذلك من خلال توضيح رغبتك بالاستبدال عند تقديم طلب الإرجاع. في حال عدم توفر المنتج المطلوب، يتم استرداد المبلغ وفق سياسة الاسترداد.</p>
                </section>

                <section style="background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-headset" style="color:#0D464C;"></i>
                        تحتاج مساعدة؟
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1.5rem;">إذا كان لديك أي استفسار حول الإرجاع أو واجهت أي مشكلة في طلبك، لا تتردد في التواصل معنا:</p>
                    <div style="display:flex; gap:1.5rem; flex-wrap:wrap;">
                        <a href="/contact" style="display:inline-block; background:#0D464C; color:#fff; padding:0.75rem 1.5rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                            تواصل مع الدعم
                        </a>
                      
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

@if(View::exists('components.footer'))
    @include('components.footer')
@endif

</body>
</html>
