<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Privacy Policy - Tulip Store</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">Privacy Policy</h1>
                <p style="font-size:1rem; color:#666;">Last updated: {{ date('F d, Y') }}</p>
            </div>

            <!-- Privacy Policy Content -->
            <div style="color:#333; line-height:1.8;">
                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">1. Introduction</h2>
                    <p style="margin-bottom:1rem;">Welcome to Tulip Store ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our services.</p>
                    <p>By using our website, you consent to the data practices described in this policy. If you do not agree with the data practices described in this Privacy Policy, please do not use our website.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">2. Information We Collect</h2>
                    
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-top:1.5rem; margin-bottom:0.75rem;">Personal Information</h3>
                    <p style="margin-bottom:1rem;">We may collect personal information that you voluntarily provide to us when you:</p>
                    <ul style="margin-left:1.5rem; margin-bottom:1rem;">
                        <li>Register for an account</li>
                        <li>Make a purchase</li>
                        <li>Subscribe to our newsletter</li>
                        <li>Contact us through our website</li>
                        <li>Participate in surveys or promotions</li>
                    </ul>
                    <p style="margin-bottom:1rem;">This information may include:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>Name, email address, phone number, and mailing address</li>
                        <li>Payment information (credit card details, billing address)</li>
                        <li>Account credentials (username, password)</li>
                        <li>Profile information and preferences</li>
                    </ul>

                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-top:1.5rem; margin-bottom:0.75rem;">Automatically Collected Information</h3>
                    <p>When you visit our website, we automatically collect certain information about your device, including:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>IP address and location data</li>
                        <li>Browser type and version</li>
                        <li>Operating system</li>
                        <li>Pages visited and time spent on pages</li>
                        <li>Referring website addresses</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">3. How We Use Your Information</h2>
                    <p style="margin-bottom:1rem;">We use the information we collect to:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>Process and fulfill your orders</li>
                        <li>Manage your account and provide customer support</li>
                        <li>Send you order confirmations, updates, and shipping notifications</li>
                        <li>Communicate with you about products, services, and promotions</li>
                        <li>Improve our website, products, and services</li>
                        <li>Detect and prevent fraud or unauthorized transactions</li>
                        <li>Comply with legal obligations</li>
                        <li>Analyze usage patterns and trends</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">4. Information Sharing and Disclosure</h2>
                    <p style="margin-bottom:1rem;">We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                    <ul style="margin-left:1.5rem;">
                        <li><strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our website and conducting business</li>
                        <li><strong>Legal Requirements:</strong> We may disclose information when required by law or to protect our rights and safety</li>
                        <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, your information may be transferred</li>
                        <li><strong>With Your Consent:</strong> We may share your information with your explicit consent</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">5. Data Security</h2>
                    <p>We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">6. Your Rights and Choices</h2>
                    <p style="margin-bottom:1rem;">You have the following rights regarding your personal information:</p>
                    <ul style="margin-left:1.5rem;">
                        <li><strong>Access:</strong> Request access to your personal information</li>
                        <li><strong>Correction:</strong> Request correction of inaccurate information</li>
                        <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                        <li><strong>Opt-Out:</strong> Unsubscribe from marketing communications</li>
                        <li><strong>Data Portability:</strong> Request a copy of your data in a portable format</li>
                    </ul>
                    <p style="margin-top:1rem;">To exercise these rights, please contact us using the information provided in the "Contact Us" section below.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">7. Cookies and Tracking Technologies</h2>
                    <p>We use cookies and similar tracking technologies to track activity on our website and store certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our website.</p>
                    <p style="margin-top:1rem;">For more information, please review our <a href="/cookies" style="color:#0D464C; text-decoration:underline;">Cookie Policy</a>.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">8. Children's Privacy</h2>
                    <p>Our website is not intended for children under the age of 18. We do not knowingly collect personal information from children. If we become aware that we have collected information from a child, we will take steps to delete that information.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">9. Changes to This Privacy Policy</h2>
                    <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this Privacy Policy periodically for any changes.</p>
                </section>

                <section style="margin-bottom:2.5rem; background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">10. Contact Us</h2>
                    <p style="margin-bottom:1rem;">If you have any questions or concerns about this Privacy Policy or our data practices, please contact us:</p>
                    <ul style="margin-left:1.5rem; line-height:2;">
                        <li><strong>Email:</strong> privacy@tulipstore.com</li>
                        <li><strong>Address:</strong> Damascus, Syria, Business District</li>
                        <li><strong>Phone:</strong> +963 11 123 4567</li>
                    </ul>
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

