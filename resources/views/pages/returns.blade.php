<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Returns & Refunds - Tulip Store</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">Returns & Refunds Policy</h1>
                <p style="font-size:1.1rem; color:#666;">Our commitment to your satisfaction</p>
            </div>

            <!-- Return Policy Sections -->
            <div style="space-y:2rem;">
                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-clock" style="color:#0D464C;"></i>
                        Return Timeframe
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem;">You have <strong>30 days</strong> from the date of delivery to return items for a full refund or exchange. The return period starts from the day you receive your order.</p>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-check-circle" style="color:#0D464C;"></i>
                        Eligible Items
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">Items must meet the following criteria to be eligible for return:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>Items must be unused, unwashed, and in original condition</li>
                        <li>All original tags and labels must be attached</li>
                        <li>Items must be in their original packaging</li>
                        <li>Proof of purchase (receipt or order number) must be provided</li>
                        <li>Items must not be damaged due to customer negligence</li>
                    </ul>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-times-circle" style="color:#0D464C;"></i>
                        Non-Returnable Items
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1rem;">The following items cannot be returned:</p>
                    <ul style="color:#666; line-height:2; margin-left:1.5rem; font-size:1.05rem;">
                        <li>Personalized or customized items</li>
                        <li>Items marked as "Final Sale"</li>
                        <li>Perishable goods</li>
                        <li>Intimate or sanitary goods (for hygiene reasons)</li>
                        <li>Items damaged by misuse or accidents</li>
                    </ul>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-redo" style="color:#0D464C;"></i>
                        How to Return
                    </h2>
                    <div style="color:#666; line-height:1.8; font-size:1.05rem;">
                        <p style="margin-bottom:1rem;">Follow these simple steps to return an item:</p>
                        <ol style="margin-left:1.5rem; line-height:2.5;">
                            <li><strong>Contact us:</strong> Email us at returns@tulipstore.com or contact customer service with your order number</li>
                            <li><strong>Get authorization:</strong> We'll provide you with a Return Authorization Number (RAN)</li>
                            <li><strong>Package securely:</strong> Pack the item in its original packaging with all tags attached</li>
                            <li><strong>Include documentation:</strong> Add the RAN and a copy of your order receipt</li>
                            <li><strong>Ship back:</strong> Send the package to the address we provide</li>
                            <li><strong>Wait for processing:</strong> We'll process your return within 5-7 business days after receiving it</li>
                        </ol>
                    </div>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-dollar-sign" style="color:#0D464C;"></i>
                        Refund Process
                    </h2>
                    <div style="color:#666; line-height:1.8; font-size:1.05rem;">
                        <p style="margin-bottom:1rem;">Once we receive and inspect your return:</p>
                        <ul style="margin-left:1.5rem; line-height:2;">
                            <li>We'll send you an email confirming receipt of your return</li>
                            <li>Refunds will be processed within 5-7 business days</li>
                            <li>The refund will be issued to your original payment method</li>
                            <li>Shipping costs are non-refundable unless the item was defective or incorrectly shipped</li>
                            <li>You'll receive a confirmation email once the refund is processed</li>
                        </ul>
                    </div>
                </section>

                <section style="background:#fff; padding:2rem; border-radius:12px; border:2px solid #e9ecef; margin-bottom:2rem;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-exchange-alt" style="color:#0D464C;"></i>
                        Exchanges
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem;">If you need a different size, color, or style, you can request an exchange. Simply indicate your preference when initiating the return. Exchanges are subject to item availability. If the desired item is not available, we'll issue a full refund instead.</p>
                </section>

                <section style="background:#f8f9fa; padding:2rem; border-radius:12px; border:2px solid #0D464C;">
                    <h2 style="font-size:1.8rem; font-weight:600; color:#0D464C; margin-bottom:1rem; display:flex; align-items:center; gap:1rem;">
                        <i class="fas fa-headset" style="color:#0D464C;"></i>
                        Need Help?
                    </h2>
                    <p style="color:#666; line-height:1.8; font-size:1.05rem; margin-bottom:1.5rem;">If you have any questions about returns or need assistance with your return request, please don't hesitate to contact us:</p>
                    <div style="display:flex; gap:1.5rem; flex-wrap:wrap;">
                        <a href="/contact" style="display:inline-block; background:#0D464C; color:#fff; padding:0.75rem 1.5rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                            Contact Support
                        </a>
                        <a href="mailto:returns@tulipstore.com" style="display:inline-block; background:#fff; color:#0D464C; padding:0.75rem 1.5rem; border-radius:8px; text-decoration:none; font-weight:600; border:2px solid #0D464C; transition:all 0.3s;" onmouseover="this.style.background='#0D464C'; this.style.color='#fff'" onmouseout="this.style.background='#fff'; this.style.color='#0D464C'">
                            Email Us
                        </a>
                    </div>
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

