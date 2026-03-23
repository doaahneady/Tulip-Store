<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>كل التصنيفات - متجر توليب</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
        .container-custom { max-width: 1200px; margin: 0 auto; }
        @media (max-width: 768px) {
            .container-custom { padding: 2rem 1rem !important; }
        }
    </style>
</head>
<body style="margin:0; font-family:'El Messiri',sans-serif; background:#f9fafb; min-height:100vh; display:flex; flex-direction:column;">

@if(View::exists('components.navbar'))
    @include('components.navbar')
@endif

<main style="flex:1;">
    <div class="container-custom" style="padding:3rem 1.5rem;">
        <div style="max-width:1200px; margin:0 auto;">
            <div style="text-align:right; margin-bottom:2.5rem;">
                <h1 style="font-size:2.3rem; font-weight:700; color:#0D464C; margin-bottom:0.75rem;">
                    جميع التصنيفات
                </h1>
                <p style="font-size:1rem; color:#64748b;">
                    استعرض كل الأقسام المتاحة في متجر توليب واختر المناسب لك.
                </p>
            </div>

            <div id="categories-container" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:1.8rem;">
                <div style="background:#fff; border-radius:18px; padding:2rem 1.5rem; text-align:center; box-shadow:0 10px 30px rgba(15,23,42,0.06);">
                    <i class="fas fa-spinner" style="font-size:2.8rem; color:#0D464C; margin-bottom:0.75rem; animation:spin 1s linear infinite;"></i>
                    <p style="color:#94a3b8; font-size:0.95rem;">جاري تحميل التصنيفات...</p>
                </div>
            </div>
        </div>
    </div>
</main>

@if(View::exists('components.footer'))
    @include('components.footer')
@endif

<script>
    async function loadCategories() {
        try {
            const response = await fetch('/api/categories?market=store', { headers: { 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('Failed to load categories');

            const categories = await response.json();
            const container = document.getElementById('categories-container');

            if (categories.data && categories.data.length > 0) {
                container.innerHTML = categories.data.map(cat => `
                    <a href="/category/${encodeURIComponent(cat.slug)}" style="
                        background:#fff;
                        border-radius:18px;
                        overflow:hidden;
                        box-shadow:0 10px 30px rgba(15,23,42,0.06);
                        text-decoration:none;
                        color:inherit;
                        display:block;
                        transition:transform 0.25s ease, box-shadow 0.25s ease;
                    " onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 18px 40px rgba(15,23,42,0.12)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(15,23,42,0.06)';">
                        <div style="background:linear-gradient(135deg,#0D464C,#1a6b75); height:150px; display:flex; align-items:center; justify-content:center;">
                            <div style="font-size:3rem; color:#fff;">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div style="padding:1.4rem 1.5rem;">
                            <h2 style="font-size:1.2rem; font-weight:700; color:#0f172a; margin-bottom:0.5rem;">
                                ${cat.name}
                            </h2>
                            <p style="font-size:0.92rem; color:#64748b; margin-bottom:0.9rem;">
                                ${cat.description || 'تصفح المنتجات ضمن هذا التصنيف'}
                            </p>
                            <span style="font-size:0.95rem; font-weight:600; color:#0D464C;">
                                عرض المنتجات
                                <i class="fas fa-arrow-left" style="margin-right:0.3rem;"></i>
                            </span>
                        </div>
                    </a>
                `).join('');
            } else {
                container.innerHTML = '<p style="grid-column:1/-1; text-align:center; color:#94a3b8; font-size:0.98rem;">لا توجد تصنيفات متاحة حالياً.</p>';
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            document.getElementById('categories-container').innerHTML =
                '<p style="grid-column:1/-1; text-align:center; color:#ef4444; font-size:0.98rem;">حدث خطأ أثناء تحميل التصنيفات، يرجى المحاولة مرة أخرى.</p>';
        }
    }

    loadCategories();
</script>

</body>
</html>
