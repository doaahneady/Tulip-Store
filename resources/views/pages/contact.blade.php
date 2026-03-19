<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تواصل معنا - متجر توليب</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">تواصل معنا</h1>
                <p style="font-size:1.1rem; color:#666; max-width:600px; margin:0 auto;">يسعدنا تواصلك معنا في أي وقت للاستفسارات أو الملاحظات أو الاقتراحات.</p>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem; margin-bottom:3rem;">
                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-map-marker-alt" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">العنوان</h3>
                    <p style="color:#666; line-height:1.6;"> السويداء<br> ساحة تشرين</p>
                </div>

                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-phone" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">الهاتف</h3>
                    <p style="color:#666; line-height:1.6;">+963 968 355 553</p>
                </div>

                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-envelope" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <!-- <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">البريد الإلكتروني</h3>
                    <p style="color:#666; line-height:1.6;"></p> -->
                </div>
            </div>

            <div style="background:#fff; padding:2.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); border:1px solid #e9ecef;">
                <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1.5rem; text-align:center;">راسلنا برسالتك</h2>
                
         <a href="https://wa.me/9639xxxxxxxx" target="_blank" style="display: inline-flex; align-items: center; gap: 0.8rem; background: #25D366; color: white; padding: 1rem 2rem; border-radius: 12px; font-family: 'El Messiri', sans-serif; font-weight: 700; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);">
                        <i class="fab fa-whatsapp" style="font-size: 1.5rem;"></i>
                        تواصل معنا عبر واتساب
                    </a>
            </div>
        </div>
    </div>
</main>

@if(View::exists('components.footer'))
    @include('components.footer')
@endif

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('شكرًا لتواصلك معنا! سنقوم بالرد عليك في أقرب وقت ممكن.');
    this.reset();
});
</script>

</body>
</html>
