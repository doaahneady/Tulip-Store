<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cookie Policy - Tulip Store</title>
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
            <!-- Header -->
            <div style="text-align:center; margin-bottom:3rem;">
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">Cookie Policy</h1>
                <p style="font-size:1rem; color:#666;">Last updated: {{ date('F d, Y') }}</p>
            </div>

            <!-- Cookie Policy Content -->
            <div style="color:#333; line-height:1.8;">
                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">1. What Are Cookies?</h2>
                    <p style="margin-bottom:1rem;">Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and provide information to the website owners.</p>
                    <p>Cookies allow websites to recognize your device and store some information about your preferences or past actions. This helps us provide you with a better experience when you browse our website and also allows us to improve our site.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">2. How We Use Cookies</h2>
                    <p style="margin-bottom:1rem;">Tulip Store uses cookies for various purposes, including:</p>
                    <ul style="margin-left:1.5rem;">
                        <li><strong>Essential Cookies:</strong> Required for the website to function properly</li>
                        <li><strong>Performance Cookies:</strong> Help us understand how visitors interact with our website</li>
                        <li><strong>Functionality Cookies:</strong> Remember your preferences and choices</li>
                        <li><strong>Targeting/Advertising Cookies:</strong> Used to deliver relevant advertisements</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">3. Types of Cookies We Use</h2>
                    
                    <div style="background:#f8f9fa; padding:1.5rem; border-radius:12px; margin-bottom:1.5rem; border-left:4px solid #0D464C;">
                        <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.75rem;">Essential Cookies</h3>
                        <p style="margin-bottom:0.5rem;">These cookies are necessary for the website to function and cannot be switched off. They are usually set in response to actions made by you, such as setting your privacy preferences or filling in forms.</p>
                        <p><strong>Examples:</strong> Session cookies, authentication cookies, security cookies</p>
                    </div>

                    <div style="background:#f8f9fa; padding:1.5rem; border-radius:12px; margin-bottom:1.5rem; border-left:4px solid #0D464C;">
                        <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.75rem;">Performance and Analytics Cookies</h3>
                        <p style="margin-bottom:0.5rem;">These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously. This helps us improve the way our website works.</p>
                        <p><strong>Examples:</strong> Google Analytics cookies, page view tracking</p>
                    </div>

                    <div style="background:#f8f9fa; padding:1.5rem; border-radius:12px; margin-bottom:1.5rem; border-left:4px solid #0D464C;">
                        <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.75rem;">Functionality Cookies</h3>
                        <p style="margin-bottom:0.5rem;">These cookies allow the website to remember choices you make and provide enhanced, more personal features. They may also be used to provide services you have requested.</p>
                        <p><strong>Examples:</strong> Language preferences, region selection, shopping cart contents</p>
                    </div>

                    <div style="background:#f8f9fa; padding:1.5rem; border-radius:12px; margin-bottom:1.5rem; border-left:4px solid #0D464C;">
                        <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.75rem;">Targeting and Advertising Cookies</h3>
                        <p style="margin-bottom:0.5rem;">These cookies may be set through our site by advertising partners. They may be used to build a profile of your interests and show you relevant content on other sites.</p>
                        <p><strong>Examples:</strong> Retargeting cookies, social media cookies</p>
                    </div>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">4. Third-Party Cookies</h2>
                    <p style="margin-bottom:1rem;">In addition to our own cookies, we may also use various third-party cookies to report usage statistics and deliver advertisements. These third parties may include:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>Google Analytics for website analytics</li>
                        <li>Social media platforms for social sharing features</li>
                        <li>Payment processors for secure payment processing</li>
                        <li>Advertising networks for personalized advertisements</li>
                    </ul>
                    <p style="margin-top:1rem;">These third-party cookies are governed by the respective privacy policies of these third parties, not our Privacy Policy.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">5. Managing Cookies</h2>
                    <p style="margin-bottom:1rem;">You have the right to decide whether to accept or reject cookies. You can set or amend your web browser controls to accept or refuse cookies. However, if you choose to refuse cookies, you may not be able to use the full functionality of our website.</p>
                    
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-top:1.5rem; margin-bottom:0.75rem;">How to Manage Cookies in Your Browser</h3>
                    <p style="margin-bottom:1rem;">Most web browsers allow you to control cookies through their settings preferences. Here are links to instructions for popular browsers:</p>
                    <ul style="margin-left:1.5rem; line-height:2;">
                        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" style="color:#0D464C; text-decoration:underline;">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" style="color:#0D464C; text-decoration:underline;">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" target="_blank" style="color:#0D464C; text-decoration:underline;">Safari</a></li>
                        <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" style="color:#0D464C; text-decoration:underline;">Microsoft Edge</a></li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">6. Cookie Consent</h2>
                    <p>When you first visit our website, we may display a cookie consent banner. By clicking "Accept" or continuing to use our website, you consent to our use of cookies as described in this Cookie Policy. You can change your cookie preferences at any time through your browser settings.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">7. Updates to This Cookie Policy</h2>
                    <p>We may update this Cookie Policy from time to time to reflect changes in technology, legislation, or our data practices. We will notify you of any significant changes by posting the updated policy on this page and updating the "Last updated" date.</p>
                </section>

                <section style="margin-bottom:2.5rem; background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">8. Contact Us</h2>
                    <p style="margin-bottom:1rem;">If you have any questions about our use of cookies or this Cookie Policy, please contact us:</p>
                    <ul style="margin-left:1.5rem; line-height:2;">
                        <li><strong>Email:</strong> privacy@tulipstore.com</li>
                        <li><strong>Address:</strong> Damascus, Syria, Business District</li>
                        <li><strong>Phone:</strong> +963 11 123 4567</li>
                    </ul>
                    <p style="margin-top:1rem;">For more information about how we handle your personal data, please review our <a href="/privacy" style="color:#0D464C; text-decoration:underline;">Privacy Policy</a>.</p>
                    <a href="/contact" style="display:inline-block; margin-top:1rem; background:#0D464C; color:#fff; padding:0.75rem 1.5rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                        Contact Support
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

