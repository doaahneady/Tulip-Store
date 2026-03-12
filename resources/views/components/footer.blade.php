<footer style="background:#0D464C; padding:1.8rem 3rem 2rem; position:relative; z-index: 1000;">
    <style>
        /* Responsive overrides (use !important to override inline styles) */
        footer { padding:1.4rem 1rem 1.6rem !important; box-sizing:border-box; }
        footer > div { max-width:1400px; margin:0 auto; padding:0 1rem; box-sizing:border-box; }
        footer > img { /* background image subtle */ width:100%; height:100%; object-fit:cover; opacity:0.03; pointer-events:none; }

        /* Grid container (first inner div) - center everything */
        footer > div > div:first-of-type {
            display:grid !important;
            grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)) !important;
            gap:2.5rem !important;
            margin-bottom:2rem !important;
            align-items:start;
            justify-items:center !important; /* center columns content */
            text-align:center !important;     /* center text inside columns */
        }

        footer h2 { margin-top:0.6rem !important; margin-bottom:0.8rem !important; font-size:1rem !important; text-align:center !important; }
        footer p { font-size:0.95rem !important; line-height:1.6 !important; text-align:center !important; }

        /* Logo & social icons */
        footer > div > div:first-of-type > div:first-of-type img { height:110px !important; margin-bottom:0.6rem !important; display:block; margin-left:auto; margin-right:auto; }
        footer > div > div:first-of-type > div:first-of-type .social-wrap { display:flex; gap:0.9rem; flex-wrap:wrap; margin-top:0.6rem; justify-content:center; }

        /* Make links inline-block to allow centered spacing and hover padding */
        footer > div > div:first-of-type a { display:inline-block; text-align:center; }

        /* Bottom row centered */
        footer > div > div:last-of-type {
            padding-top:1.4rem !important;
            border-top:1px solid rgba(255,255,255,0.1) !important;
            display:flex !important;
            justify-content:center !important;
            align-items:center !important;
            gap:1rem !important;
            flex-wrap:wrap;
            text-align:center;
        }
        footer > div > div:last-of-type p { margin:0 !important; font-size:0.9rem !important; color:rgba(255,255,255,0.55) !important; text-align:center !important; }

        footer > div > div:last-of-type .payments { display:flex; gap:1.2rem; align-items:center; flex-wrap:wrap; justify-content:center; }

        footer img.payment-icon { height:30px !important; opacity:1 !important; }

        /* Responsive breakpoints */
        @media (max-width:1200px) {
            footer > div > div:first-of-type { grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)) !important; gap:1.4rem !important; }
            footer > div > div:first-of-type > div:first-of-type img { height:95px !important; }
        }

        @media (max-width:800px) {
            footer { padding:1rem 0.8rem 1rem !important; }
            footer > div > div:first-of-type { grid-template-columns:1fr !important; gap:1rem !important; }
            footer > div > div:first-of-type > div { text-align:center !important; }
            footer > div > div:first-of-type > div:not(:first-of-type) a { display:inline-block !important; }
            /* center social icons */
            footer > div > div:first-of-type > div:first-of-type .social-wrap { justify-content:center; margin:0.6rem auto 0; }
            /* bottom row stack */
            footer > div > div:last-of-type { flex-direction:column !important; align-items:center !important; text-align:center !important; gap:0.8rem !important; }
            footer > div > div:last-of-type .payments { justify-content:center; }
        }

        @media (max-width:420px) {
            footer h2 { font-size:0.95rem !important; }
            footer p { font-size:0.9rem !important; }
            footer > div > div:first-of-type > div:first-of-type img { height:78px !important; }
            footer img.payment-icon { height:26px !important; }
        }
    </style>

    <img src="/images/footer.jpg" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.03;">
    
    <div style="max-width:1400px; margin:0 auto; position:relative;">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:2.5rem; margin-bottom:2rem; justify-items:center; text-align:center;">
            <div>
                <img src="/images/white_orange_logo.png" style="height:130px;margin-bottom:0.8rem; display:block; margin-left:auto; margin-right:auto;">
                <p style="color:rgba(255,255,255,0.7); line-height:1.8; font-size:1rem; margin-bottom:1rem; max-width:480px; margin-left:auto; margin-right:auto;">
                    متجر فاخر للمنتجات المميزة. نساعدك في إرسال ابتسامتك لأحبائك أينما كانوا.
                </p>
                <div class="social-wrap" style="display:flex; gap:0.9rem; justify-content:center; margin-top:0.6rem;">
                    <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                        <i class="fab fa-facebook"></i>
                    </a>
                </div>
            </div>
            
            <div>
                <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">روابط سريعة</h2>
                <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                    <a href="/store" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">المتجر</a>
                    <a href="/about" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">من نحن؟</a>
                    <a href="/contact" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">تواصل معنا</a>
                </div>
            </div>
            
            <div>
                <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">الدعم التقني</h2>
                <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                    <a href="/faq" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">الأسئلة الشائعة</a>
                    <a href="/shipping" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الشحن</a>
                    <a href="/returns" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الإرجاع</a>
                    <a href="/privacy" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الخصوصية</a>
                </div>
            </div>
            
            <div>
                <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">الأقسام الخاصة</h2>
                <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                    <a href="/" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب ستور</a>
                    <a href="/mart" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب مارت</a>
                    <a href="/net" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب نت</a>
                    <a href="/gifts" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب لتنسيق الهدايا</a>
                  </div>
            </div>

          
        </div>
          <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
              <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">
                يمكك زيارة موقع الشركة و التعرف على خدماتنا
                <a href="/company" style="color:rgba(255,255,255,0.7); font-weight:800; margin-bottom:1rem;margin-top:1rem "> من هنا </a>
               </h2>
            </div>
        <div style="padding-top:2rem; border-top:1px solid rgba(255,255,255,0.1); display:flex; justify-content:center; align-items:center; gap:1.2rem; flex-wrap:wrap;">
            <p style="color:rgba(255,255,255,0.5); margin:0; font-size:0.95rem;">© 2026 Tulip Store. جميع الحقوق محفوظة</p>
            <div class="payments" style="display:flex; gap:1.2rem; align-items:center; justify-content:center;">
                <i class="fab fa-cc-visa" style="font-size:28px; color:#fff;"></i>
                <i class="fab fa-cc-mastercard" style="font-size:28px; color:#fff;"></i>
                <i class="fas fa-hand-holding-dollar" style="font-size:26px; color:#fff;"></i>
            </div>
        </div>
    </div>
    
</footer>
