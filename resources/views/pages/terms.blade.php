<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terms of Service - Tulip Store</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">Terms of Service</h1>
                <p style="font-size:1rem; color:#666;">Last updated: {{ date('F d, Y') }}</p>
            </div>

            <!-- Terms Content -->
            <div style="color:#333; line-height:1.8;">
                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">1. Agreement to Terms</h2>
                    <p style="margin-bottom:1rem;">By accessing or using Tulip Store's website and services, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site.</p>
                    <p>The materials contained on this website are protected by applicable copyright and trademark law.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">2. Use License</h2>
                    <p style="margin-bottom:1rem;">Permission is granted to temporarily access the materials on Tulip Store's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>Modify or copy the materials</li>
                        <li>Use the materials for any commercial purpose or for any public display</li>
                        <li>Attempt to reverse engineer any software contained on the website</li>
                        <li>Remove any copyright or other proprietary notations from the materials</li>
                        <li>Transfer the materials to another person or "mirror" the materials on any other server</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">3. Account Registration</h2>
                    <p style="margin-bottom:1rem;">To access certain features of our website, you may be required to create an account. When registering, you agree to:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>Provide accurate, current, and complete information</li>
                        <li>Maintain and promptly update your account information</li>
                        <li>Maintain the security of your password and identification</li>
                        <li>Accept all responsibility for activities that occur under your account</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">4. Products and Pricing</h2>
                    <p style="margin-bottom:1rem;">We strive to provide accurate product descriptions and pricing. However, we reserve the right to:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>Correct any errors, inaccuracies, or omissions</li>
                        <li>Change or update information at any time without prior notice</li>
                        <li>Refuse or cancel orders placed for products listed at incorrect prices</li>
                        <li>Limit the quantity of items purchased per person or per order</li>
                    </ul>
                    <p style="margin-top:1rem;">All prices are in the currency specified and are subject to change without notice.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">5. Payment Terms</h2>
                    <p style="margin-bottom:1rem;">By providing payment information, you represent and warrant that:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>The payment information is accurate and complete</li>
                        <li>You are authorized to use the payment method</li>
                        <li>You will be charged the total amount for your order</li>
                    </ul>
                    <p style="margin-top:1rem;">All payments are processed securely through our payment processors. We accept various payment methods as indicated during checkout.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">6. Shipping and Delivery</h2>
                    <p style="margin-bottom:1rem;">Shipping terms and delivery times are estimates only. We are not responsible for:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>Delays caused by carriers or shipping companies</li>
                        <li>Loss or damage during shipping (unless caused by our negligence)</li>
                        <li>Delivery delays due to incorrect or incomplete addresses</li>
                    </ul>
                    <p style="margin-top:1rem;">Please refer to our <a href="/shipping" style="color:#0D464C; text-decoration:underline;">Shipping Information</a> page for detailed shipping policies.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">7. Returns and Refunds</h2>
                    <p style="margin-bottom:1rem;">Our return and refund policy is detailed on our <a href="/returns" style="color:#0D464C; text-decoration:underline;">Returns & Refunds</a> page. By making a purchase, you agree to our return policy. Refunds will be processed according to the method of original payment.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">8. Prohibited Uses</h2>
                    <p style="margin-bottom:1rem;">You agree not to use our website:</p>
                    <ul style="margin-left:1.5rem;">
                        <li>In any way that violates any applicable law or regulation</li>
                        <li>To transmit, or procure the sending of, any advertising or promotional material</li>
                        <li>To impersonate or attempt to impersonate the company or any employee</li>
                        <li>In any way that infringes upon the rights of others</li>
                        <li>To engage in any other conduct that restricts or inhibits anyone's use of the website</li>
                    </ul>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">9. Intellectual Property</h2>
                    <p>The website and its original content, features, and functionality are owned by Tulip Store and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws. You may not reproduce, distribute, modify, or create derivative works without our express written permission.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">10. Limitation of Liability</h2>
                    <p style="margin-bottom:1rem;">In no event shall Tulip Store or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on our website, even if we or an authorized representative has been notified orally or in writing of the possibility of such damage.</p>
                    <p>Some jurisdictions do not allow limitations on implied warranties or limitations of liability for incidental or consequential damages, so these limitations may not apply to you.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">11. Indemnification</h2>
                    <p>You agree to indemnify, defend, and hold harmless Tulip Store, its officers, directors, employees, and agents from and against any claims, liabilities, damages, losses, and expenses, including reasonable attorney's fees, arising out of or in any way connected with your access to or use of our website or violation of these Terms of Service.</p>
                </section>

                <section style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">12. Changes to Terms</h2>
                    <p>We reserve the right to modify these Terms of Service at any time. We will notify users of any changes by updating the "Last updated" date of this page. Your continued use of the website after any changes constitutes acceptance of those changes.</p>
                </section>

                <section style="margin-bottom:2.5rem; background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">13. Contact Information</h2>
                    <p style="margin-bottom:1rem;">If you have any questions about these Terms of Service, please contact us:</p>
                    <ul style="margin-left:1.5rem; line-height:2;">
                        <li><strong>Email:</strong> legal@tulipstore.com</li>
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

