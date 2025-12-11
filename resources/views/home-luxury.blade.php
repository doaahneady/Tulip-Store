<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tulip Store - Luxury Fashion</title>
    <link rel="stylesheet" href="/css/store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Changa',sans-serif; background:#fff; color:#000; overflow-x:hidden; }
        
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(30px); }
            to { opacity:1; transform:translateY(0); }
        }
        
        @keyframes slideIn {
            from { opacity:0; transform:translateX(-50px); }
            to { opacity:1; transform:translateX(0); }
        }
        
        .fade-in { animation:fadeInUp 0.8s ease; }
        .slide-in { animation:slideIn 0.8s ease; }
        
        .hover-scale { transition:transform 0.5s ease; }
        .hover-scale:hover { transform:scale(1.05); }
        
        .btn-elegant {
            background:linear-gradient(135deg, #2a7080 0%, #1e5a6a 100%);
            color:#fff;
            border:none;
            padding:1.2rem 3.5rem;
            font-size:1rem;
            font-weight:600;
            letter-spacing:0.05em;
            cursor:pointer;
            transition:all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-family:'Changa',sans-serif;
            position:relative;
            overflow:hidden;
            box-shadow:0 8px 25px rgba(42,112,128,0.35);
        }
        
        .btn-elegant::before {
            content:'';
            position:absolute;
            top:50%;
            left:50%;
            width:0;
            height:0;
            border-radius:50%;
            background:rgba(255,255,255,0.2);
            transform:translate(-50%,-50%);
            transition:width 0.6s, height 0.6s;
        }
        
        .btn-elegant:hover {
            transform:translateY(-3px);
            box-shadow:0 12px 35px rgba(42,112,128,0.45);
            background:linear-gradient(135deg, #1e5a6a 0%, #2a7080 100%);
        }
        
        .btn-elegant:hover::before {
            width:300px;
            height:300px;
        }
        
        .btn-elegant span {
            position:relative;
            z-index:1;
        }
        
        .btn-orange {
            background:linear-gradient(135deg, #2a7080 0%, #ff6b35 100%);
            box-shadow:0 8px 25px rgba(42,112,128,0.35);
        }
        
        .btn-orange:hover {
            box-shadow:0 12px 35px rgba(42,112,128,0.45);
            background:linear-gradient(135deg, #ff6b35 0%, #2a7080 100%);
        }
        
        .accent-line {
            width:80px;
            height:3px;
            background:linear-gradient(90deg, #2a7080 0%, #4a9aaa 100%);
            margin:1.5rem auto;
        }
    </style>
</head>
<body>

@if(View::exists('components.navbar'))
    @include('components.navbar')
@endif

<!-- ELEGANT HERO BANNER -->
<section style="position:relative; min-height:100vh; background:#fff; overflow:hidden;">
    <!-- Animated Background -->
    <div style="position:absolute; inset:0; background:linear-gradient(135deg, #f8fafb 0%, #e8f2f5 100%);"></div>
    <div style="position:absolute; top:0; right:0; width:50%; height:100%; background:linear-gradient(135deg, rgba(42,112,128,0.03) 0%, transparent 100%);"></div>
    
    <!-- Floating Shapes -->
    <div style="position:absolute; top:10%; right:10%; width:300px; height:300px; border:2px solid rgba(42,112,128,0.1); border-radius:50%; animation:float 8s ease-in-out infinite;"></div>
    <div style="position:absolute; bottom:15%; left:8%; width:200px; height:200px; border:2px solid rgba(255,107,53,0.1); border-radius:50%; animation:float 6s ease-in-out infinite; animation-delay:1s;"></div>
    
    <div style="max-width:1400px; margin:0 auto; padding:0 3rem; min-height:100vh; display:flex; align-items:center; position:relative; z-index:1;">
        <div style="display:grid; grid-template-columns:1.2fr 1fr; gap:6rem; align-items:center; width:100%;">
            <!-- Left Content -->
            <div class="fade-in">
                <div style="display:inline-flex; align-items:center; gap:0.8rem; background:#fff; padding:0.7rem 1.8rem; border-radius:50px; font-size:0.85rem; font-weight:600; margin-bottom:2.5rem; box-shadow:0 4px 15px rgba(42,112,128,0.1); border:1px solid rgba(42,112,128,0.1);">
                    <div style="width:8px; height:8px; background:#ff6b35; border-radius:50%; animation:pulse 2s infinite;"></div>
                    <span style="color:#2a7080; letter-spacing:0.05em;">مجموعة 2024 الحصرية</span>
                </div>
                
                <h1 style="font-size:5.5rem; font-weight:900; line-height:1; margin-bottom:2rem; color:#1a1a1a;">
                    تسوق بأناقة
                    <span style="display:block; margin-top:0.5rem; background:linear-gradient(135deg, #2a7080 0%, #5ab4c8 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
                        وثقة
                    </span>
                </h1>
                
                <p style="font-size:1.2rem; color:#666; line-height:1.9; margin-bottom:3.5rem; max-width:480px;">
                    استمتع بتجربة تسوق فريدة مع أفضل المنتجات العالمية وخدمة عملاء استثنائية
                </p>
                
                <div style="display:flex; gap:1.5rem; align-items:center; margin-bottom:4rem;">
                    <button class="btn-elegant" onclick="window.location.href='#collection'">
                        <span>استكشف المجموعة</span>
                        <i class="fas fa-arrow-left" style="margin-right:0.8rem;"></i>
                    </button>
                    <a href="#features" style="color:#2a7080; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:0.5rem; font-size:1rem; transition:all 0.3s; padding:0.5rem;" onmouseover="this.style.gap='1rem'; this.style.color='#1e5a6a'" onmouseout="this.style.gap='0.5rem'; this.style.color='#2a7080'">
                        <span>شاهد الفيديو</span>
                        <i class="fas fa-play-circle" style="font-size:1.5rem;"></i>
                    </a>
                </div>
                
                <!-- Trust Badges -->
                <div style="display:flex; gap:2.5rem; padding-top:2rem; border-top:1px solid rgba(42,112,128,0.15);">
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <div style="width:50px; height:50px; background:linear-gradient(135deg, #2a7080 0%, #4a9aaa 100%); border-radius:12px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(42,112,128,0.2);">
                            <i class="fas fa-shield-check" style="color:#fff; font-size:1.5rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:1.1rem; font-weight:700; color:#2a7080;">100%</div>
                            <div style="font-size:0.85rem; color:#999;">منتجات أصلية</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <div style="width:50px; height:50px; background:linear-gradient(135deg, #ff6b35 0%, #ff8c61 100%); border-radius:12px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(255,107,53,0.2);">
                            <i class="fas fa-truck-fast" style="color:#fff; font-size:1.5rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:1.1rem; font-weight:700; color:#ff6b35;">24h</div>
                            <div style="font-size:0.85rem; color:#999;">توصيل سريع</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Visual -->
            <div class="slide-in" style="position:relative; animation-delay:0.3s;">
                <div style="position:relative; width:100%; aspect-ratio:4/5;">
                    <!-- Main Image -->
                    <div style="position:absolute; top:0; right:0; width:85%; height:85%; border-radius:30px; overflow:hidden; box-shadow:0 30px 80px rgba(42,112,128,0.25); z-index:2;">
                        <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&h=750&fit=crop" 
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    
                    <!-- Accent Card -->
                    <div style="position:absolute; bottom:0; left:0; width:70%; background:#fff; padding:2rem; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,0.15); z-index:3;">
                        <div style="display:flex; align-items:center; gap:1.5rem; margin-bottom:1rem;">
                            <div style="width:60px; height:60px; background:linear-gradient(135deg, #2a7080 0%, #4a9aaa 100%); border-radius:15px; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px rgba(42,112,128,0.3);">
                                <i class="fas fa-star" style="color:#fff; font-size:1.8rem;"></i>
                            </div>
                            <div>
                                <div style="font-size:2rem; font-weight:800; color:#2a7080; line-height:1;">4.9</div>
                                <div style="color:#ff6b35; font-size:0.9rem;">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p style="color:#666; font-size:0.9rem; margin:0;">من أكثر من 10,000 تقييم</p>
                    </div>
                    
                    <!-- Decorative Circle -->
                    <div style="position:absolute; top:10%; left:-5%; width:150px; height:150px; background:linear-gradient(135deg, #ff6b35 0%, #ff8c61 100%); border-radius:50%; opacity:0.15; z-index:1;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div style="position:absolute; bottom:3rem; left:50%; transform:translateX(-50%); text-align:center; animation:bounce 2s infinite;">
        <div style="width:30px; height:50px; border:2px solid #2a7080; border-radius:20px; position:relative; margin:0 auto 0.5rem;">
            <div style="width:4px; height:8px; background:#2a7080; border-radius:2px; position:absolute; top:8px; left:50%; transform:translateX(-50%); animation:scroll 2s infinite;"></div>
        </div>
        <div style="color:#2a7080; font-size:0.75rem; font-weight:600;">اسحب للأسفل</div>
    </div>
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(5deg); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

@keyframes scroll {
    0% { top: 8px; opacity: 1; }
    100% { top: 24px; opacity: 0; }
}
</style>

<!-- FEATURES BAR -->
<section style="background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); padding:3rem 2rem; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-50px; right:-50px; width:200px; height:200px; background:rgba(255,107,53,0.1); border-radius:50%;"></div>
    <div style="position:absolute; bottom:-30px; left:-30px; width:150px; height:150px; background:rgba(255,107,53,0.1); border-radius:50%;"></div>
    <div style="max-width:1400px; margin:0 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:3rem; text-align:center; position:relative; z-index:1;">
        <div class="fade-in">
            <div style="width:70px; height:70px; background:rgba(255,255,255,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; backdrop-filter:blur(10px); border:2px solid rgba(255,255,255,0.3);">
                <i class="fas fa-shipping-fast" style="font-size:2rem; color:#fff;"></i>
            </div>
            <h3 style="font-size:1.1rem; font-weight:600; margin-bottom:0.5rem; color:#fff;">شحن مجاني</h3>
            <p style="color:rgba(255,255,255,0.8); font-size:0.9rem;">للطلبات فوق 200 ريال</p>
        </div>
        <div class="fade-in" style="animation-delay:0.1s;">
            <div style="width:70px; height:70px; background:rgba(255,255,255,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; backdrop-filter:blur(10px); border:2px solid rgba(255,255,255,0.3);">
                <i class="fas fa-shield-alt" style="font-size:2rem; color:#fff;"></i>
            </div>
            <h3 style="font-size:1.1rem; font-weight:600; margin-bottom:0.5rem; color:#fff;">دفع آمن</h3>
            <p style="color:rgba(255,255,255,0.8); font-size:0.9rem;">حماية كاملة للمعاملات</p>
        </div>
        <div class="fade-in" style="animation-delay:0.2s;">
            <div style="width:70px; height:70px; background:rgba(255,255,255,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; backdrop-filter:blur(10px); border:2px solid rgba(255,255,255,0.3);">
                <i class="fas fa-undo" style="font-size:2rem; color:#fff;"></i>
            </div>
            <h3 style="font-size:1.1rem; font-weight:600; margin-bottom:0.5rem; color:#fff;">إرجاع سهل</h3>
            <p style="color:rgba(255,255,255,0.8); font-size:0.9rem;">خلال 30 يوم</p>
        </div>
        <div class="fade-in" style="animation-delay:0.3s;">
            <div style="width:70px; height:70px; background:rgba(255,255,255,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; backdrop-filter:blur(10px); border:2px solid rgba(255,255,255,0.3);">
                <i class="fas fa-headset" style="font-size:2rem; color:#fff;"></i>
            </div>
            <h3 style="font-size:1.1rem; font-weight:600; margin-bottom:0.5rem; color:#fff;">دعم 24/7</h3>
            <p style="color:rgba(255,255,255,0.8); font-size:0.9rem;">خدمة عملاء متميزة</p>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section style="padding:6rem 3rem; background:#fff;">
    <div style="max-width:1200px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:4rem;">
            <h2 style="font-size:2.5rem; font-weight:700; color:#2a7080; margin-bottom:1rem;">لماذا تختار توليب؟</h2>
            <p style="color:#666; font-size:1.1rem;">نقدم لك تجربة تسوق استثنائية</p>
        </div>
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:2.5rem;">
            <div style="text-align:center; padding:2rem 1.5rem; background:linear-gradient(135deg, #f8f9fa 0%, #fff 100%); border-radius:15px; border-top:4px solid #2a7080; transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(42,112,128,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="width:70px; height:70px; background:linear-gradient(135deg, #2a7080 0%, #4a9aaa 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; box-shadow:0 8px 20px rgba(42,112,128,0.3);">
                    <i class="fas fa-award" style="font-size:2rem; color:#fff;"></i>
                </div>
                <h3 style="font-size:1.2rem; font-weight:700; color:#2a7080; margin-bottom:0.8rem;">جودة عالية</h3>
                <p style="color:#666; font-size:0.95rem; line-height:1.6;">منتجات أصلية 100% من أفضل العلامات التجارية</p>
            </div>
            <div style="text-align:center; padding:2rem 1.5rem; background:linear-gradient(135deg, #f8f9fa 0%, #fff 100%); border-radius:15px; border-top:4px solid #ff6b35; transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(255,107,53,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="width:70px; height:70px; background:linear-gradient(135deg, #ff6b35 0%, #ff8c61 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; box-shadow:0 8px 20px rgba(255,107,53,0.3);">
                    <i class="fas fa-truck-fast" style="font-size:2rem; color:#fff;"></i>
                </div>
                <h3 style="font-size:1.2rem; font-weight:700; color:#ff6b35; margin-bottom:0.8rem;">توصيل سريع</h3>
                <p style="color:#666; font-size:0.95rem; line-height:1.6;">شحن مجاني وتوصيل خلال 24-48 ساعة</p>
            </div>
            <div style="text-align:center; padding:2rem 1.5rem; background:linear-gradient(135deg, #f8f9fa 0%, #fff 100%); border-radius:15px; border-top:4px solid #2a7080; transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(42,112,128,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="width:70px; height:70px; background:linear-gradient(135deg, #2a7080 0%, #4a9aaa 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; box-shadow:0 8px 20px rgba(42,112,128,0.3);">
                    <i class="fas fa-lock" style="font-size:2rem; color:#fff;"></i>
                </div>
                <h3 style="font-size:1.2rem; font-weight:700; color:#2a7080; margin-bottom:0.8rem;">دفع آمن</h3>
                <p style="color:#666; font-size:0.95rem; line-height:1.6;">حماية كاملة لمعلوماتك ومعاملاتك المالية</p>
            </div>
            <div style="text-align:center; padding:2rem 1.5rem; background:linear-gradient(135deg, #f8f9fa 0%, #fff 100%); border-radius:15px; border-top:4px solid #ff6b35; transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(255,107,53,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="width:70px; height:70px; background:linear-gradient(135deg, #ff6b35 0%, #ff8c61 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; box-shadow:0 8px 20px rgba(255,107,53,0.3);">
                    <i class="fas fa-smile" style="font-size:2rem; color:#fff;"></i>
                </div>
                <h3 style="font-size:1.2rem; font-weight:700; color:#ff6b35; margin-bottom:0.8rem;">رضا العملاء</h3>
                <p style="color:#666; font-size:0.95rem; line-height:1.6;">دعم فني متميز وضمان استرجاع المال</p>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORY SHOWCASE -->
<section style="padding:0; margin:0;">
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0;">
        <div onclick="window.location.href='/store'" style="position:relative; height:550px; overflow:hidden; cursor:pointer; group;">
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=700&h=900&fit=crop" 
                 class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.7), transparent); display:flex; align-items:flex-end; padding:3rem;">
                <div style="color:#fff;">
                    <h3 style="font-size:2rem; font-weight:500; margin-bottom:0.5rem; letter-spacing:0.1em;">نساء</h3>
                    <p style="font-size:1rem; opacity:0.9; margin-bottom:1.5rem;">أحدث صيحات الموضة</p>
                    <button style="background:#fff; color:#000; border:none; padding:0.8rem 2rem; font-weight:500; cursor:pointer; font-family:'Tajawal',sans-serif;">
                        تسوق الآن
                    </button>
                </div>
            </div>
        </div>
        <div onclick="window.location.href='/store'" style="position:relative; height:550px; overflow:hidden; cursor:pointer;">
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=700&h=900&fit=crop" 
                 class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.7), transparent); display:flex; align-items:flex-end; padding:3rem;">
                <div style="color:#fff;">
                    <h3 style="font-size:2rem; font-weight:500; margin-bottom:0.5rem; letter-spacing:0.1em;">رجال</h3>
                    <p style="font-size:1rem; opacity:0.9; margin-bottom:1.5rem;">أناقة عصرية</p>
                    <button style="background:#fff; color:#000; border:none; padding:0.8rem 2rem; font-weight:500; cursor:pointer; font-family:'Tajawal',sans-serif;">
                        تسوق الآن
                    </button>
                </div>
            </div>
        </div>
        <div onclick="window.location.href='/store'" style="position:relative; height:550px; overflow:hidden; cursor:pointer;">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=700&h=900&fit=crop" 
                 class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.7), transparent); display:flex; align-items:flex-end; padding:3rem;">
                <div style="color:#fff;">
                    <h3 style="font-size:2rem; font-weight:500; margin-bottom:0.5rem; letter-spacing:0.1em;">إكسسوارات</h3>
                    <p style="font-size:1rem; opacity:0.9; margin-bottom:1.5rem;">لمسة نهائية مثالية</p>
                    <button style="background:#fff; color:#000; border:none; padding:0.8rem 2rem; font-weight:500; cursor:pointer; font-family:'Tajawal',sans-serif;">
                        تسوق الآن
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
let currentSlide = 0;
function changeSlide(index) {
    currentSlide = index;
    // Slide logic here
}
</script>

</body>
</html>

<!-- NEW COLLECTION -->
<section id="collection" style="padding:7rem 3rem; background:#fff;">
    <div style="max-width:1400px; margin:0 auto;">
        <div class="fade-in" style="text-align:center; margin-bottom:5rem;">
            <span style="color:#999; font-size:0.9rem; letter-spacing:0.2em; text-transform:uppercase;">New Arrivals</span>
            <h2 style="font-size:3rem; font-weight:300; letter-spacing:0.15em; margin:1rem 0;">المجموعة الجديدة</h2>
            <div style="width:60px; height:2px; background:#000; margin:1.5rem auto;"></div>
        </div>
        <div id="newCollection" style="display:grid; grid-template-columns:repeat(4,1fr); gap:2.5rem;"></div>
    </div>
</section>

<!-- APP DOWNLOAD SECTION -->
<section style="background:linear-gradient(135deg, #2a7080 0%, #1e5a6a 100%); padding:5rem 3rem; position:relative; overflow:hidden;">
    <div style="position:absolute; top:0; left:0; right:0; bottom:0; background:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity:0.3;"></div>
    
    <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center; position:relative; z-index:1;">
        <div>
            <div style="display:inline-flex; align-items:center; gap:0.8rem; background:rgba(255,107,53,0.15); padding:0.6rem 1.5rem; border-radius:30px; margin-bottom:2rem; border:1px solid rgba(255,107,53,0.3);">
                <i class="fas fa-mobile-alt" style="color:#ff6b35; font-size:1.2rem;"></i>
                <span style="color:#ff6b35; font-weight:700; font-size:0.9rem;">تطبيق توليب</span>
            </div>
            <h2 style="font-size:3rem; font-weight:800; color:#fff; margin-bottom:1.5rem; line-height:1.2;">
                تسوق بسهولة<br>من هاتفك
            </h2>
            <p style="font-size:1.1rem; color:rgba(255,255,255,0.9); margin-bottom:3rem; line-height:1.8;">
                حمّل تطبيق توليب واستمتع بتجربة تسوق سلسة مع عروض حصرية وإشعارات فورية
            </p>
            <div style="display:flex; gap:1.5rem;">
                <div style="background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); padding:1rem 2rem; border-radius:12px; display:flex; align-items:center; gap:1rem; cursor:pointer; transition:all 0.3s; border:1px solid rgba(255,255,255,0.2);" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <i class="fab fa-apple" style="font-size:2.5rem; color:#fff;"></i>
                    <div>
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.8);">Download on the</div>
                        <div style="font-size:1.1rem; font-weight:700; color:#fff;">App Store</div>
                    </div>
                </div>
                <div style="background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); padding:1rem 2rem; border-radius:12px; display:flex; align-items:center; gap:1rem; cursor:pointer; transition:all 0.3s; border:1px solid rgba(255,255,255,0.2);" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <i class="fab fa-google-play" style="font-size:2.5rem; color:#fff;"></i>
                    <div>
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.8);">GET IT ON</div>
                        <div style="font-size:1.1rem; font-weight:700; color:#fff;">Google Play</div>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align:center;">
            <div style="position:relative; display:inline-block;">
                <div style="width:300px; height:600px; background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); border-radius:40px; border:8px solid rgba(255,255,255,0.2); box-shadow:0 30px 80px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-mobile-screen-button" style="font-size:8rem; color:rgba(255,255,255,0.3);"></i>
                </div>
                <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:#ff6b35; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 10px 30px rgba(255,107,53,0.4);">
                    <i class="fas fa-download" style="font-size:2rem; color:#fff;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SUCCESS STATS -->
<section style="padding:5rem 3rem; background:linear-gradient(135deg, #f8f9fa 0%, #fff 100%);">
    <div style="max-width:1200px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:4rem;">
            <h2 style="font-size:2.5rem; font-weight:700; color:#2a7080; margin-bottom:1rem;">إنجازاتنا بالأرقام</h2>
            <p style="color:#666; font-size:1.1rem;">نفتخر بثقة عملائنا ونجاحنا المستمر</p>
        </div>
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:3rem;">
            <div style="text-align:center; padding:2.5rem 2rem; background:#fff; border-radius:20px; box-shadow:0 5px 20px rgba(0,0,0,0.08); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(42,112,128,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.08)'">
                <div style="font-size:3.5rem; font-weight:900; background:linear-gradient(135deg, #2a7080 0%, #4a9aaa 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:0.5rem;">15K+</div>
                <div style="color:#666; font-size:1.1rem; font-weight:600;">عميل سعيد</div>
            </div>
            <div style="text-align:center; padding:2.5rem 2rem; background:#fff; border-radius:20px; box-shadow:0 5px 20px rgba(0,0,0,0.08); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(255,107,53,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.08)'">
                <div style="font-size:3.5rem; font-weight:900; background:linear-gradient(135deg, #ff6b35 0%, #ff8c61 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:0.5rem;">8K+</div>
                <div style="color:#666; font-size:1.1rem; font-weight:600;">منتج متنوع</div>
            </div>
            <div style="text-align:center; padding:2.5rem 2rem; background:#fff; border-radius:20px; box-shadow:0 5px 20px rgba(0,0,0,0.08); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(42,112,128,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.08)'">
                <div style="font-size:3.5rem; font-weight:900; background:linear-gradient(135deg, #2a7080 0%, #4a9aaa 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:0.5rem;">100+</div>
                <div style="color:#666; font-size:1.1rem; font-weight:600;">علامة تجارية</div>
            </div>
            <div style="text-align:center; padding:2.5rem 2rem; background:#fff; border-radius:20px; box-shadow:0 5px 20px rgba(0,0,0,0.08); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 15px 40px rgba(255,107,53,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.08)'">
                <div style="font-size:3.5rem; font-weight:900; background:linear-gradient(135deg, #ff6b35 0%, #ff8c61 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-bottom:0.5rem;">99%</div>
                <div style="color:#666; font-size:1.1rem; font-weight:600;">رضا العملاء</div>
            </div>
        </div>
    </div>
</section>

<!-- TRENDING -->
<section style="padding:7rem 3rem; background:#f8f8f8;">
    <div style="max-width:1400px; margin:0 auto;">
        <div class="fade-in" style="text-align:center; margin-bottom:5rem;">
            <span style="color:#999; font-size:0.9rem; letter-spacing:0.2em;">TRENDING NOW</span>
            <h2 style="font-size:3rem; font-weight:300; letter-spacing:0.15em; margin:1rem 0;">الأكثر رواجاً</h2>
            <div style="width:60px; height:2px; background:#000; margin:1.5rem auto;"></div>
        </div>
        <div id="trending" style="display:grid; grid-template-columns:repeat(4,1fr); gap:2.5rem;"></div>
    </div>
</section>

<!-- INSTAGRAM FEED -->
<section style="padding:7rem 3rem;">
    <div style="max-width:1400px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:4rem;">
            <h2 style="font-size:2.5rem; font-weight:300; letter-spacing:0.15em; margin-bottom:1rem;">
                <i class="fab fa-instagram" style="margin-left:1rem;"></i>
                تابعنا على إنستغرام
            </h2>
            <p style="color:#666; font-size:1.1rem;">@tulipstore</p>
        </div>
        <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1rem;">
            <div style="aspect-ratio:1; overflow:hidden; cursor:pointer;">
                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop" 
                     class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div style="aspect-ratio:1; overflow:hidden; cursor:pointer;">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop" 
                     class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div style="aspect-ratio:1; overflow:hidden; cursor:pointer;">
                <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop" 
                     class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div style="aspect-ratio:1; overflow:hidden; cursor:pointer;">
                <img src="https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=400&h=400&fit=crop" 
                     class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div style="aspect-ratio:1; overflow:hidden; cursor:pointer;">
                <img src="https://images.unsplash.com/photo-1511556820780-d912e42b4980?w=400&h=400&fit=crop" 
                     class="hover-scale" style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER -->
<section style="background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); color:#fff; padding:6rem 3rem; text-align:center; position:relative; overflow:hidden;">
    <div style="position:absolute; top:-150px; right:-150px; width:400px; height:400px; background:rgba(255,107,53,0.1); border-radius:50%;"></div>
    <div style="position:absolute; bottom:-100px; left:-100px; width:300px; height:300px; background:rgba(255,107,53,0.1); border-radius:50%;"></div>
    <div style="max-width:700px; margin:0 auto; position:relative; z-index:1;">
        <div style="width:80px; height:80px; background:rgba(255,107,53,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 2rem; backdrop-filter:blur(10px); border:3px solid rgba(255,107,53,0.3);">
            <i class="fas fa-envelope" style="font-size:2rem; color:#ff6b35;"></i>
        </div>
        <h2 style="font-size:2.5rem; font-weight:300; letter-spacing:0.2em; margin-bottom:1rem;">
            انضم إلى نادي VIP
        </h2>
        <p style="color:rgba(255,255,255,0.9); font-size:1.1rem; margin-bottom:3rem; line-height:1.8;">
            احصل على خصم 10% على أول طلب + وصول حصري للعروض والمنتجات الجديدة
        </p>
        <div style="display:flex; gap:0; max-width:550px; margin:0 auto; background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); border:2px solid rgba(255,255,255,0.2);">
            <input type="email" placeholder="أدخل بريدك الإلكتروني" 
                   style="flex:1; padding:1.3rem 1.5rem; border:none; background:transparent; color:#fff; font-size:1rem; outline:none;">
            <button class="btn-orange" style="border-radius:0; padding:1.3rem 3rem;">
                <span>اشترك الآن</span>
            </button>
        </div>
        <p style="color:rgba(255,255,255,0.6); font-size:0.85rem; margin-top:1.5rem;">
            بالاشتراك، أنت توافق على سياسة الخصوصية الخاصة بنا
        </p>
    </div>
</section>

<!-- SELLER CTA SECTION -->
<section style="background:#d8e8ed; padding:5rem 3rem; position:relative; overflow:hidden;">
    <!-- Decorative Icons -->
    <div style="position:absolute; top:20px; left:50px; opacity:0.3;">
        <i class="fas fa-store" style="font-size:4rem; color:#2a7080;"></i>
    </div>
    <div style="position:absolute; top:40px; right:100px; opacity:0.3;">
        <i class="fas fa-money-bill-wave" style="font-size:3rem; color:#2a7080;"></i>
    </div>
    <div style="position:absolute; bottom:30px; left:150px; opacity:0.3;">
        <i class="fas fa-coins" style="font-size:3.5rem; color:#2a7080;"></i>
    </div>
    <div style="position:absolute; bottom:50px; right:80px; opacity:0.3;">
        <i class="fas fa-store-alt" style="font-size:3rem; color:#2a7080;"></i>
    </div>
    
    <div style="max-width:800px; margin:0 auto; text-align:center; position:relative; z-index:1;">
        <h2 style="font-size:2.5rem; font-weight:700; color:#2a7080; margin-bottom:1rem;">
            أعرض منتجاتك لدينا
        </h2>
        <p style="font-size:1.2rem; color:#5a8a9a; margin-bottom:2.5rem;">
            انشئ حسابك التجاري، و ابدأ التجارة
        </p>
        <button class="btn-orange" style="padding:1.2rem 4rem; font-size:1.1rem;" onclick="window.location.href='/register'">
            <span>أنشئ حسابك الآن</span>
        </button>
    </div>
</section>

<!-- FOOTER -->
<footer style="background:#1a4a54; color:#fff; padding:3rem 3rem 1.5rem;">
    <div style="max-width:1400px; margin:0 auto;">
        <div style="display:grid; grid-template-columns:1.5fr 1fr 1fr 1fr; gap:3rem; margin-bottom:2rem;">
            <!-- Logo & Social -->
            <div>
                <div style="margin-bottom:1.5rem; background:#000; padding:1rem; border-radius:10px; display:inline-block;">
                    <img src="/images/photo_2025-11-17_11-18-40.jpg" alt="Tulip Store" style="height:50px;">
                </div>
                <h3 style="font-size:1rem; font-weight:600; margin-bottom:1rem; color:#ff6b35;">تواصل معنا</h3>
                <div style="display:flex; gap:0.7rem; margin-bottom:1rem;">
                    <a href="#" style="width:45px; height:45px; background:rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; transition:all 0.3s; border:1px solid rgba(255,255,255,0.2);" onmouseover="this.style.background='#ff6b35'; this.style.borderColor='#ff6b35'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.2)'">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" style="width:45px; height:45px; background:rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; transition:all 0.3s; border:1px solid rgba(255,255,255,0.2);" onmouseover="this.style.background='#ff6b35'; this.style.borderColor='#ff6b35'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.2)'">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" style="width:45px; height:45px; background:rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; transition:all 0.3s; border:1px solid rgba(255,255,255,0.2);" onmouseover="this.style.background='#ff6b35'; this.style.borderColor='#ff6b35'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.2)'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" style="width:45px; height:45px; background:rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; transition:all 0.3s; border:1px solid rgba(255,255,255,0.2);" onmouseover="this.style.background='#ff6b35'; this.style.borderColor='#ff6b35'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.2)'">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- هل أنت جديد هنا؟ -->
            <div>
                <h4 style="font-size:0.95rem; font-weight:600; margin-bottom:0.8rem; color:#ff6b35;">هل أنت جديد هنا؟</h4>
                <div style="display:flex; flex-direction:column; gap:0.5rem; color:rgba(255,255,255,0.8); font-size:0.85rem;">
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">انشئ حسابك الآن لترى</a>
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">كل ما هو جديد</a>
                </div>
            </div>
            
            <!-- روابط سريعة الوصول -->
            <div>
                <h4 style="font-size:1.2rem; font-weight:600; margin-bottom:1.5rem; color:#ff6b35;">روابط سريعة الوصول</h4>
                <div style="display:flex; flex-direction:column; gap:0.8rem; color:rgba(255,255,255,0.8); font-size:0.95rem;">
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">الصفحة الرئيسية</a>
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">من نحن؟</a>
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">تواصل معنا</a>
                </div>
            </div>
            
            <!-- الدعم و التواصل التقني -->
            <div>
                <h4 style="font-size:1.2rem; font-weight:600; margin-bottom:1.5rem; color:#ff6b35;">الدعم و التواصل التقني</h4>
                <div style="display:flex; flex-direction:column; gap:0.8rem; color:rgba(255,255,255,0.8); font-size:0.95rem;">
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">الأسئلة الشائعة(FAQ)</a>
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">سياسة الشحن و التوصيل</a>
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">سياسة الإرجاع و الاستبدال</a>
                    <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">سياسة الضمان</a>
                </div>
            </div>
        </div>
        
        <!-- الأقسام الخاصة بشركتنا -->
        <div style="padding:2rem 0; border-top:1px solid rgba(255,255,255,0.1); border-bottom:1px solid rgba(255,255,255,0.1); margin-bottom:2rem;">
            <h4 style="font-size:1.2rem; font-weight:600; margin-bottom:1.5rem; color:#ff6b35; text-align:center;">الأقسام الخاصة بشركتنا</h4>
            <div style="display:flex; justify-content:center; gap:3rem; flex-wrap:wrap; color:rgba(255,255,255,0.8); font-size:0.95rem;">
                <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">توليب مارت</a>
                <a href="#" style="color:rgba(255,255,255,0.8); text-decoration:none; transition:color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">توليب للتسويق العقاري</a>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:2rem;">
            <div style="display:flex; gap:1rem; align-items:center;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" style="height:30px; opacity:0.7;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" style="height:30px; opacity:0.7;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" style="height:30px; opacity:0.7;">
            </div>
            <p style="color:rgba(255,255,255,0.6); font-size:0.9rem; margin:0;">© 2025 جميع الحقوق محفوظة</p>
        </div>
    </div>
</footer>

<script>
async function loadProducts() {
    const response = await fetch('/api/products');
    const data = await response.json();
    const products = data.data || [];
    
    const card = p => `
        <div onclick="window.location.href='/products/${p.id}'" style="cursor:pointer; background:#fff; transition:all 0.3s;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="position:relative; overflow:hidden; margin-bottom:1.5rem; aspect-ratio:3/4; background:#f5f5f5;">
                <img src="${p.image || 'https://via.placeholder.com/400x500'}" 
                     style="width:100%; height:100%; object-fit:cover; transition:transform 0.6s;" 
                     onmouseover="this.style.transform='scale(1.1)'" 
                     onmouseout="this.style.transform='scale(1)'">
                <button onclick="event.stopPropagation(); addToCart(${p.id})" 
                        style="position:absolute; bottom:1.5rem; left:1.5rem; right:1.5rem; background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); color:#fff; border:none; padding:1rem; font-weight:600; opacity:0; transition:all 0.3s; cursor:pointer; font-family:'Tajawal',sans-serif; letter-spacing:0.05em; box-shadow:0 4px 15px rgba(42,112,128,0.4);" 
                        onmouseover="this.style.opacity='1'; this.style.background='linear-gradient(135deg, #ff6b35 0%, #ff5722 100%)'; this.style.boxShadow='0 6px 20px rgba(255,107,53,0.4)'" 
                        onmouseout="this.style.opacity='0'">
                    أضف للسلة
                </button>
            </div>
            <div style="text-align:center; padding:0 1rem 1.5rem;">
                <h3 style="font-size:1rem; font-weight:500; margin-bottom:0.8rem; color:#000; min-height:48px;">${p.name}</h3>
                <p style="font-size:1.3rem; font-weight:700; color:#2a7080;">$${p.price}</p>
            </div>
        </div>
    `;
    
    document.getElementById('newCollection').innerHTML = products.slice(0, 8).map(card).join('');
    document.getElementById('trending').innerHTML = products.slice(8, 16).map(card).join('');
}

async function addToCart(productId) {
    const response = await fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    });
    const data = await response.json();
    if (data.success && window.updateCartCount) {
        window.updateCartCount(data.cart_count || data.count || 0);
    }
    
    // Show elegant notification
    const notif = document.createElement('div');
    notif.style.cssText = 'position:fixed; top:100px; right:30px; background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); color:#fff; padding:1.5rem 2.5rem; border-radius:0; z-index:10000; animation:slideIn 0.5s ease; box-shadow:0 10px 40px rgba(42,112,128,0.4); font-size:1rem; letter-spacing:0.05em; border-right:4px solid #ff6b35;';
    notif.innerHTML = '<i class="fas fa-check-circle" style="color:#ff6b35; margin-left:0.5rem;"></i> تم إضافة المنتج للسلة';
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
}

window.addEventListener('DOMContentLoaded', loadProducts);

// Scroll animations
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

setTimeout(() => {
    document.querySelectorAll('.fade-in, .slide-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.8s ease';
        observer.observe(el);
    });
}, 100);
</script>
</body>
</html>
