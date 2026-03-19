<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الأسئلة الشائعة - متجر توليب</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon.png">
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">الأسئلة الشائعة</h1>
                <p style="font-size:1.1rem; color:#666;">هنا تجد إجابات عن أكثر الأسئلة تكراراً حول متجر توليب</p>
            </div>

            <div style="space-y:1rem;">
                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>كيف يمكنني تقديم طلب شراء؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>يمكنك تصفح المنتجات وإضافة ما ترغب به إلى سلة التسوق، ثم المتابعة لإتمام الطلب عبر إدخال بيانات الشحن وتأكيد طريقة الدفع. قد تحتاج لإنشاء حساب جديد أو تسجيل الدخول لحفظ طلباتك.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>ما هي طرق الدفع المتاحة؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>نوفر عدة خيارات للدفع مثل الدفع الالكتروني visa و master  الدفع عند الاستلام. جميع المعاملات تتم عبر بوابات دفع آمنة ومشفرة.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>كم يستغرق توصيل الطلب؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>يختلف وقت التوصيل بحسب المدينة وطريقة الشحن، لكن غالباً ما يتم التوصيل خلال ٣–٧ أيام عمل داخل المدينة، و٧–١٤ يوم عمل للمناطق الأخرى. قد تتوفر أيضاً خيارات شحن أسرع.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>هل يمكنني إرجاع أو استبدال المنتجات؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>نعم، يمكنك طلب إرجاع أو استبدال المنتج وفق سياسة الإرجاع المعتمدة في متجر توليب، بشرط أن يكون المنتج بحالته الأصلية ولم يُستخدم. يمكنك زيارة صفحة سياسة الإرجاع لمزيد من التفاصيل.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>كيف يمكنني تتبع حالة طلبي؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
<p>
    بعد شحن الطلب يمكنك تتبع حالة الطلب من خلال طلباتي ضمن بروفايلك و معرفة متى يصل .
</p>                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>هل توجد عروض أو خصومات؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>نعم، نقدم بشكل مستمر عروضاً وخصومات موسمية وخاصة. يمكنك متابعة حساباتنا على وسائل التواصل الاجتماعي أو الاشتراك في النشرة البريدية للبقاء على اطلاع بآخر العروض.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>كيف يمكنني إنشاء حساب في متجر توليب؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>إنشاء حساب جديد سهل جداً، فقط اضغط على خيار التسجيل في أعلى الموقع، ثم أدخل بياناتك الأساسية وقم بتأكيد العملية و الانتظار حتى يتم الموافقة على انشاء حسابك و ثم يمكنك البدء بالتجارة . .</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:right; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>هل معلوماتي الشخصية آمنة؟</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>بالتأكيد، نهتم كثيراً بحماية خصوصيتك. يتم حفظ بياناتك بشكل آمن ولا تتم مشاركتها مع أي طرف ثالث دون موافقتك. يمكنك الاطلاع على صفحة سياسة الخصوصية لمعرفة المزيد.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align:center; margin-top:3rem; padding:2rem; background:#f8f9fa; border-radius:12px;">
                <h3 style="font-size:1.5rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">ما زلت تملك أسئلة أخرى؟</h3>
                <p style="color:#666; margin-bottom:1.5rem;">إذا لم تجد الإجابة التي تبحث عنها، يسعد فريق خدمة العملاء بمساعدتك.</p>
                <a href="/contact" style="display:inline-block; background:#0D464C; color:#fff; padding:0.75rem 2rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                    تواصل معنا
                </a>
            </div>
        </div>
    </div>
</main>

@if(View::exists('components.footer'))
    @include('components.footer')
@endif

<script>
function toggleFaq(button) {
    const item = button.closest('.faq-item');
    const answer = item.querySelector('.faq-answer');
    const icon = button.querySelector('i');
    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

    // Close all other items
    document.querySelectorAll('.faq-item').forEach(faqItem => {
        if (faqItem !== item) {
            faqItem.querySelector('.faq-answer').style.maxHeight = '0px';
            faqItem.querySelector('.faq-question i').style.transform = 'rotate(0deg)';
            faqItem.style.borderColor = '#e9ecef';
        }
    });

    // Toggle current item
    if (isOpen) {
        answer.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
        item.style.borderColor = '#e9ecef';
    } else {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
        item.style.borderColor = '#0D464C';
    }
}
</script>

</body>
</html>
