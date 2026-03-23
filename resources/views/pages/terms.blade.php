<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الشروط والأحكام - متجر توليب</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">الشروط والأحكام</h1>
                <p style="font-size:1rem; color:#666;">آخر تحديث: {{ date('Y-m-d') }}</p>
            </div>

            <div style="color:#333; line-height:1.8;">
                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">١. قبول الشروط</h2>
                    <p style="margin-bottom:1rem;">
                        باستخدامك لموقع متجر توليب أو أي من خدماته، فأنت توافق على الالتزام بهذه الشروط والأحكام وجميع
                        الأنظمة واللوائح المعمول بها. في حال عدم موافقتك على أي من هذه الشروط، يرجى التوقف عن استخدام
                        الموقع.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٢. حساب المستخدم</h2>
                    <p style="margin-bottom:1rem;">
                        يتحمل المستخدم مسؤولية صحة البيانات المدخلة في حسابه والمحافظة على سرية معلومات الدخول، كما
                        يتحمل المسؤولية الكاملة عن جميع الأنشطة التي تتم من خلال حسابه.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٣. الطلبات والمنتجات</h2>
                    <p style="margin-bottom:1rem;">
                        نبذل جهدنا لعرض المنتجات والمعلومات والأسعار بدقة قدر الإمكان، ومع ذلك قد تحدث بعض الأخطاء
                        المطبعية أو التقنية. يحق لمتجر توليب إلغاء أو تعديل أي طلب في حال وجود خطأ واضح في السعر أو
                        توفر المنتج مع إخطار العميل.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٤. الدفع</h2>
                    <p style="margin-bottom:1rem;">
                        يدعم متجر توليب طرق دفع مختلفة مثل البطاقات البنكية أو الدفع النقدي عند الاستلام في المناطق
                        المتاحة. عند تقديم معلومات الدفع، فإنك تقر بأن لديك الحق في استخدام وسيلة الدفع هذه، وأن
                        المعلومات المقدمة صحيحة.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٥. سياسة الإرجاع والاستبدال</h2>
                    <p style="margin-bottom:1rem;">
                        تخضع عمليات الإرجاع والاستبدال لسياسة واضحة ومعلنة يمكنك الاطلاع عليها من خلال صفحة
                        <a href="/returns" style="color:#0D464C; text-decoration:underline;">سياسة الإرجاع</a>.
                        قد تختلف الشروط حسب نوع المنتج وحالته ومدة الطلب.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٦. حدود المسؤولية</h2>
                    <p style="margin-bottom:1rem;">
                        لا يتحمل متجر توليب أي مسؤولية عن أي خسائر غير مباشرة أو عرضية أو تبعية تنشأ عن استخدام أو عدم
                        القدرة على استخدام الموقع أو الخدمات، ويقتصر أقصى حد للمسؤولية – إن وجد – على قيمة الطلب
                        المعني فقط.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٧. التعديلات على الشروط</h2>
                    <p style="margin-bottom:1rem;">
                        يحتفظ متجر توليب بالحق في تعديل هذه الشروط والأحكام في أي وقت. سيتم نشر أي تعديلات على هذه
                        الصفحة، ويُعتبر استمرارك في استخدام الموقع بعد التعديل موافقة صريحة على الشروط المحدثة.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem; background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٨. معلومات التواصل</h2>
                    <p style="margin-bottom:1rem;">
                        في حال وجود أي استفسارات بخصوص هذه الشروط والأحكام، يمكنك التواصل معنا عبر:
                    </p>
                    <ul style="margin-left:1.5rem; line-height:2;">
                        <!-- <li><strong>البريد الإلكتروني:</strong> legal@tulip-os.com</li> -->
                        <li><strong>العنوان:</strong> السويداء ساحة تشرين  </li>
                        <li><strong>الهاتف:</strong> ‎+963 968 355 553</li>
                    </ul>
                    <a href="/contact" style="display:inline-block; margin-top:1rem; background:#0D464C; color:#fff; padding:0.75rem 1.5rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                        تواصل مع الدعم
                    </a>
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
