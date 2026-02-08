<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us - Tulip Store</title>
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
            <!-- Header -->
            <div style="text-align:center; margin-bottom:3rem;">
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">Contact Us</h1>
                <p style="font-size:1.1rem; color:#666; max-width:600px; margin:0 auto;">Get in touch with us. We'd love to hear from you!</p>
            </div>

            <!-- Contact Cards -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem; margin-bottom:3rem;">
                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-map-marker-alt" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">Address</h3>
                    <p style="color:#666; line-height:1.6;">Damascus, Syria<br>Business District</p>
                </div>

                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-phone" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">Phone</h3>
                    <p style="color:#666; line-height:1.6;">+963 11 123 4567<br>Mon-Fri: 9am-6pm</p>
                </div>

                <div style="background:#f8f9fa; padding:2rem; border-radius:12px; text-align:center; border:2px solid #e9ecef;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-envelope" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.2rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">Email</h3>
                    <p style="color:#666; line-height:1.6;">info@tulipstore.com<br>support@tulipstore.com</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div style="background:#fff; padding:2.5rem; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); border:1px solid #e9ecef;">
                <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1.5rem; text-align:center;">Send Us a Message</h2>
                
                <form id="contactForm" style="max-width:600px; margin:0 auto;">
                    @csrf
                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">Name *</label>
                        <input type="text" name="name" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">Email *</label>
                        <input type="email" name="email" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">Subject *</label>
                        <input type="text" name="subject" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; color:#333; margin-bottom:0.5rem;">Message *</label>
                        <textarea name="message" rows="6" required style="width:100%; padding:0.75rem; border:2px solid #e9ecef; border-radius:8px; font-size:1rem; resize:vertical; transition:border-color 0.3s;" onfocus="this.style.borderColor='#0D464C'" onblur="this.style.borderColor='#e9ecef'"></textarea>
                    </div>

                    <button type="submit" style="width:100%; background:#0D464C; color:#fff; padding:1rem; border:none; border-radius:8px; font-size:1.1rem; font-weight:600; cursor:pointer; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                        <i class="fas fa-paper-plane" style="margin-right:0.5rem;"></i>
                        Send Message
                    </button>
                </form>
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
    alert('Thank you for your message! We will get back to you soon.');
    this.reset();
});
</script>

</body>
</html>

