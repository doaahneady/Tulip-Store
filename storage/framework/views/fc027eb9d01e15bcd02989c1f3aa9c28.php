<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>تواصل معنا - متجر توليب</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">تواصل معنا</h1>
                <p style="font-size:1.1rem; color:#666; max-width:600px; margin:0 auto;">يسعدنا تواصلك معنا في أي وقت للاستفسارات أو الملاحظات أو الاقتراحات.</p>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem; margin-bottom:3rem;">
                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-map-marker-alt" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">العنوان</h3>
                    <p style="color:#666; line-height:1.6;">دمشق، سوريا<br>منطقة الأعمال</p>
                </div>

                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-phone" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">الهاتف</h3>
                    <p style="color:#666; line-height:1.6;">+963 11 123 4567<br>السبت – الخميس: ٩:٠٠ ص – ٦:٠٠ م</p>
                </div>

                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-envelope" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">البريد الإلكتروني</h3>
                    <p style="color:#666; line-height:1.6;">info@tulipstore.com<br>support@tulipstore.com</p>
                </div>
            </div>

            <div style="background:#fff; padding:2.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); border:1px solid #e9ecef;">
                <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1.5rem; text-align:center;">راسلنا برسالتك</h2>
                
                <form id="contactForm" style="max-width:600px; margin:0 auto;">
                    <?php echo csrf_field(); ?>
                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">الاسم الكامل *</label>
                        <input type="text" name="name" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">البريد الإلكتروني *</label>
                        <input type="email" name="email" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">عنوان الرسالة *</label>
                        <input type="text" name="subject" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">نص الرسالة *</label>
                        <textarea name="message" rows="6" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; resize:vertical; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'"></textarea>
                    </div>

                    <button type="submit" style="width:100%; background:#0D464C; color:#fff; padding:1rem; border:none; border-radius:8px; font-size:1.1rem; font-weight:600; cursor:pointer; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                        <i class="fas fa-paper-plane" style="margin-left:0.5rem;"></i>
                        إرسال الرسالة
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php if(View::exists('components.footer')): ?>
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('شكرًا لتواصلك معنا! سنقوم بالرد عليك في أقرب وقت ممكن.');
    this.reset();
});
</script>

</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/pages/contact.blade.php ENDPATH**/ ?>