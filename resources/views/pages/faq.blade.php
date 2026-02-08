<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Frequently Asked Questions - Tulip Store</title>
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
                <h1 style="font-size:2.5rem; font-weight:700; color:#0D464C; margin-bottom:1rem;">Frequently Asked Questions</h1>
                <p style="font-size:1.1rem; color:#666;">Find answers to common questions about Tulip Store</p>
            </div>

            <!-- FAQ Items -->
            <div style="space-y:1rem;">
                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>How do I place an order?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>To place an order, simply browse our products, add items to your cart, and proceed to checkout. You'll need to create an account or sign in, provide your shipping information, and complete the payment.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>What payment methods do you accept?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>We accept various payment methods including credit/debit cards, cash on delivery, and bank transfers. All transactions are secure and encrypted.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>How long does shipping take?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>Standard shipping typically takes 3-7 business days within the city and 7-14 business days for other areas. Express shipping options are available for faster delivery.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>Can I return or exchange items?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>Yes! We offer a 30-day return policy for unused items in their original packaging. Please visit our Returns page for detailed information about the return process.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>How can I track my order?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>Once your order is shipped, you'll receive a tracking number via email. You can use this number to track your order status on our website or through the shipping carrier's website.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>Do you offer discounts or promotions?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>Yes! We regularly offer promotions, seasonal sales, and special discounts. Sign up for our newsletter or follow us on social media to stay updated on the latest deals and offers.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>How do I create an account?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>Creating an account is easy! Click on "Sign Up" in the top navigation, fill in your information, and verify your email address. You can also sign up with your Google account for faster registration.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" style="background:#fff; border:2px solid #e9ecef; border-radius:12px; margin-bottom:1rem; overflow:hidden; transition:all 0.3s;">
                    <button class="faq-question" style="width:100%; padding:1.5rem; background:#f8f9fa; border:none; text-align:left; cursor:pointer; font-size:1.1rem; font-weight:600; color:#0D464C; display:flex; justify-content:space-between; align-items:center;" onclick="toggleFaq(this)">
                        <span>Is my personal information secure?</span>
                        <i class="fas fa-chevron-down" style="transition:transform 0.3s;"></i>
                    </button>
                    <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.3s;">
                        <div style="padding:1.5rem; color:#666; line-height:1.8;">
                            <p>Absolutely! We take your privacy seriously. All personal information is encrypted and stored securely. We never share your data with third parties. Please review our Privacy Policy for more details.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Still have questions -->
            <div style="text-align:center; margin-top:3rem; padding:2rem; background:#f8f9fa; border-radius:12px;">
                <h3 style="font-size:1.5rem; font-weight:600; color:#0D464C; margin-bottom:1rem;">Still have questions?</h3>
                <p style="color:#666; margin-bottom:1.5rem;">Can't find the answer you're looking for? Please contact our friendly team.</p>
                <a href="/contact" style="display:inline-block; background:#0D464C; color:#fff; padding:0.75rem 2rem; border-radius:8px; text-decoration:none; font-weight:600; transition:background 0.3s;" onmouseover="this.style.background='#0a3538'" onmouseout="this.style.background='#0D464C'">
                    Contact Us
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

