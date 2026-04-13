<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سياسة الخصوصية - متجر توليب</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">سياسة الخصوصية</h1>
                <p style="font-size:1rem; color:#666;">آخر تحديث: {{ date('Y-m-d') }}</p>
            </div>

            <div style="color:#333; line-height:1.8;">
                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">١. مقدمة</h2>
                    <p style="margin-bottom:1rem;">
                        نرحب بك في متجر توليب. نحن نحرص على حماية خصوصيتك وحماية بياناتك الشخصية، وتوضح هذه السياسة
                        كيف نقوم بجمع بياناتك واستخدامها وحمايتها عند زيارتك لموقعنا أو استخدامك لخدماتنا.
                    </p>
                    <p>
                        باستخدامك لموقعنا، فإنك توافق على بنود سياسة الخصوصية هذه. إذا كنت لا توافق على أي جزء منها،
                        يرجى التوقف عن استخدام الموقع.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٢. البيانات التي نقوم بجمعها</h2>

                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-top:1.5rem; margin-bottom:0.75rem;">أولاً: البيانات التي تزودنا بها</h3>
                    <p style="margin-bottom:1rem;">
                        نقوم بجمع البيانات التي تقوم بتزويدنا بها مباشرة، مثلًا عند:
                    </p>
                    <ul style="margin-left:1.5rem; margin-bottom:1rem;">
                        <li>إنشاء حساب جديد في الموقع</li>
                        <li>إتمام عملية شراء أو إضافة طلب جديد</li>
                        <li>التواصل معنا عبر نموذج الاتصال أو خدمة الدعم</li>
                        <!-- <li>الاشتراك في النشرة البريدية أو العروض الترويجية</li> -->
                    </ul>
                    <p style="margin-bottom:1rem;">وقد تشمل هذه البيانات على سبيل المثال لا الحصر:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>الاسم الكامل، رقم الجوال، عنوان البريد الإلكتروني، عنوان التوصيل</li>
                        <li>بيانات الدفع (مثل آخر أرقام البطاقة وعنوان الفوترة)</li>
                        <li>بيانات الدخول إلى الحساب</li>
                    </ul>

                    <!-- <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-top:1.5rem; margin-bottom:0.75rem;">ثانياً: البيانات التي تُجمع تلقائياً</h3>
                    <p style="margin-bottom:0.75rem;">
                        عند زيارتك لموقعنا، قد نقوم تلقائياً بجمع بعض المعلومات التقنية مثل:
                    </p>
                    <ul style="margin-left:1.5rem;">
                        <li>عنوان بروتوكول الإنترنت (IP) والمنطقة الجغرافية التقريبية</li>
                        <li>نوع المتصفح ونظام التشغيل</li>
                        <li>الصفحات التي تزورها ومدة بقائك فيها</li>
                        <li>ملفات تعريف الارتباط (الكوكيز) ومعرّفات الجلسات</li>
                    </ul> -->
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٣. كيف نستخدم بياناتك</h2>
                    <p style="margin-bottom:1rem;">نستخدم البيانات التي نجمعها للأغراض التالية:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>إنشاء حسابك وإدارة طلباتك وسلة المشتريات</li>
                        <li>معالجة المدفوعات وترتيب عمليات الشحن والتوصيل</li>
                        <li>التواصل معك بخصوص حالة الطلبات أو الاستفسارات</li>
                        <li>إرسال عروض خاصة وتحديثات (في حال موافقتك على ذلك)</li>
                        <li>تحسين تجربة الاستخدام وتطوير المنتجات والخدمات</li>
                        <li>الامتثال للأنظمة والقوانين المعمول بها وحماية حقوقنا القانونية</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٤. مشاركة البيانات مع أطراف أخرى</h2>
                    <p style="margin-bottom:1rem;">
                        لا نقوم ببيع بياناتك الشخصية لأي طرف ثالث. قد نشارك جزءاً من بياناتك فقط في الحالات التالية:
                    </p>
                    <ul style="margin-left:1.5rem;">
                        <li><strong>شركات الشحن والدفع:</strong> لمساعدتنا في إتمام الطلبات واستقبال المدفوعات بأمان.</li>
                        <li><strong>مزودو الخدمات التقنية:</strong> مثل استضافة الموقع وأنظمة التحليل.</li>
                        <li><strong>المتطلبات النظامية:</strong> عند طلب الجهات الرسمية أو القضائية وفقاً للأنظمة.</li>
                        <li><strong>بموافقتك الصريحة:</strong> في حال وافقت أنت على مشاركة بيانات محددة لغرض معين.</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٥. حماية البيانات</h2>
                    <p>
                        نعتمد عدداً من الإجراءات الفنية والتنظيمية لحماية بياناتك الشخصية من الوصول غير المصرح به أو
                        الفقدان أو التعديل. ومع ذلك، لا يمكن ضمان أمان كامل بنسبة ١٠٠٪ لأي نظام على الإنترنت، لذا
                        فإنك تتحمل جزءاً من مسؤولية حماية بيانات تسجيل الدخول الخاصة بك وعدم مشاركتها مع أي طرف آخر.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٦. حقوقك على بياناتك</h2>
                    <p style="margin-bottom:1rem;">
                        يحق لك، في حدود ما تسمح به القوانين المطبقة، ما يلي:
                    </p>
                    <ul style="margin-left:1.5rem;">
                        <li>طلب الاطلاع على البيانات الشخصية التي نحتفظ بها عنك</li>
                        <li>طلب تصحيح أي بيانات غير دقيقة أو غير مكتملة</li>
                        <li>طلب حذف بعض البيانات عندما لا يكون هناك سبب نظامي للاحتفاظ بها</li>
                        <li>إلغاء الاشتراك في الرسائل التسويقية في أي وقت</li>
                    </ul>
                    <p style="margin-top:1rem;">
                        لممارسة أي من هذه الحقوق، يمكنك التواصل معنا من خلال صفحة
                        <a href="/contact" style="color:#0D464C; text-decoration:underline;">اتصل بنا</a>.
                    </p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٧. ملفات تعريف الارتباط (الكوكيز)</h2>
                    <p style="margin-bottom:1rem;">
                        نستخدم الكوكيز وتقنيات مشابهة لتحسين تجربتك في تصفح الموقع، مثل تذكر تفضيلاتك وحفظ محتوى
                        سلة المشتريات وتحليل استخدام الموقع.
                    </p>
                    <p>
                        يمكنك التحكم في إعدادات الكوكيز من خلال متصفحك، وقد يؤدي تعطيلها إلى عدم عمل بعض أجزاء الموقع
                        بالشكل الأمثل. لمزيد من التفاصيل، يمكنك مراجعة صفحة
                        <a href="/cookies" style="color:#0D464C; text-decoration:underline;">سياسة الكوكيز</a>.
                    </p>
                </section>

                <!-- <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٨. خصوصية الأطفال</h2>
                    <p>
                        خدماتنا موجهة للأفراد البالغين. لا نقوم عمداً بجمع بيانات شخصية عن الأطفال دون سن ١٨ عاماً،
                        وفي حال اكتشفنا ذلك فسنقوم بحذف هذه البيانات فوراً.
                    </p>
                </section> -->

                <section style="margin-bottom:2.5rem; background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">٩. التواصل معنا</h2>
                    <p style="margin-bottom:1rem;">
                        في حال كان لديك أي استفسار بخصوص سياسة الخصوصية أو كيفية استخدام بياناتك، يمكنك التواصل معنا عبر:
                    </p>
                    <ul style="margin-left:1.5rem; line-height:2;">
                        <!-- <li><strong>البريد الإلكتروني:</strong> privacy@tulip-os.com</li> -->
                        <li><strong>العنوان:</strong> السويداء- ساحة تشرين   </li>
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
