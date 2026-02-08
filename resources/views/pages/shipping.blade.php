<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shipping Information - Tulip Store</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">Shipping & Delivery</h1>
                <p style="font-size:1.1rem; color:#666;">Fast and reliable shipping to your doorstep</p>
            </div>

            <!-- Shipping Options -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem; margin-bottom:3rem;">
                <div style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; text-align:center;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-truck" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">Standard Shipping</h3>
                    <p style="color:#666; margin-bottom:1rem;">3-7 business days</p>
                    <p style="color:#0D464C; font-weight:600; font-size:1.1rem;">Free</p>
                </div>

                <div style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #0D464C; text-align:center; position:relative;">
                    <div style="position:absolute; top:-10px; right:20px; background:#0D464C; color:#fff; padding:0.25rem 0.75rem; border-radius:12px; font-size:0.85rem; font-weight:600;">POPULAR</div>
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-shipping-fast" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">Express Shipping</h3>
                    <p style="color:#666; margin-bottom:1rem;">1-3 business days</p>
                    <p style="color:#0D464C; font-weight:600; font-size:1.1rem;">$15.00</p>
                </div>

                <div style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; text-align:center;">
                    <div style="width:60px; height:60px; background:#0D464C; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                        <i class="fas fa-rocket" style="color:#fff; font-size:1.5rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem; font-weight:600; color:#0D464C; margin-bottom:0.5rem;">Same Day Delivery</h3>
                    <p style="color:#666; margin-bottom:1rem;">Same day (within city)</p>
                    <p style="color:#0D464C; font-weight:600; font-size:1.1rem;">$25.00</p>
                </div>
            </div>

            <!-- Shipping Information Sections -->
            <div style="space-y:2rem;">
                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-globe" style="color:#0D464C;"></i>
                        Shipping Locations
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">We currently ship to the following locations:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li><strong>Within City:</strong> 3-7 business days (Free shipping on orders over $50)</li>
                        <li><strong>Other Cities:</strong> 7-14 business days</li>
                        <li><strong>International:</strong> 14-21 business days (subject to customs)</li>
                    </ul>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-box" style="color:#0D464C;"></i>
                        Processing Time
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem;">All orders are processed within <strong>1-2 business days</strong> (excluding weekends and holidays) after we receive your order confirmation and payment. Once your order has shipped, you will receive a tracking number via email.</p>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-map-marked-alt" style="color:#0D464C;"></i>
                        Tracking Your Order
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">Once your order ships, you'll receive:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>An email confirmation with your tracking number</li>
                        <li>Real-time updates on your package location</li>
                        <li>Estimated delivery date</li>
                        <li>SMS notifications (if opted in)</li>
                    </ul>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-top:1rem;">You can track your order by logging into your account or using the tracking number provided in your shipping confirmation email.</p>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-dollar-sign" style="color:#0D464C;"></i>
                        Shipping Costs
                    </h2>
                    <div style="color:#666; line-height:1.8; font-size:1.05rem;">
                        <p style="margin-bottom:1rem;"><strong>Free Standard Shipping:</strong> Available on all orders over $50</p>
                        <p style="margin-bottom:1rem;"><strong>Standard Shipping:</strong> $5.00 for orders under $50</p>
                        <p style="margin-bottom:1rem;"><strong>Express Shipping:</strong> $15.00 (1-3 business days)</p>
                        <p style="margin-bottom:1rem;"><strong>Same Day Delivery:</strong> $25.00 (available within city limits)</p>
                        <p><strong>International Shipping:</strong> Calculated at checkout based on destination and weight</p>
                    </div>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-exclamation-triangle" style="color:#0D464C;"></i>
                        Important Notes
                    </h2>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>Delivery times are estimates and may vary due to weather, holidays, or carrier delays</li>
                        <li>We are not responsible for delays caused by incorrect shipping addresses</li>
                        <li>Please ensure someone is available to receive the package during delivery hours</li>
                        <li>For international orders, customers are responsible for any customs duties or taxes</li>
                        <li>Orders are shipped Monday through Friday, excluding holidays</li>
                    </ul>
                </section>

                <section style="background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-question-circle" style="color:#0D464C;"></i>
                        Questions About Shipping?
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1.5rem;">If you have any questions about shipping or need to make changes to your order, please contact us as soon as possible:</p>
                    <a href="/contact" style="display:inline-block; background:#0D464C; color:#fff; padding:0.75rem 1.5rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
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

