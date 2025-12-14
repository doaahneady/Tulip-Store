<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>آراء العملاء - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* {
    font-family: 'Cairo', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    color: #1e293b;
}

.page-header {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    padding: 2rem;
    margin-top: 80px;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
}

.header-content {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title h1 {
    color: white;
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.header-title p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
}

.btn-header {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

.coming-soon {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.coming-soon i {
    font-size: 4rem;
    color: #3b82f6;
    margin-bottom: 2rem;
}

.coming-soon h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
}

.coming-soon p {
    color: #64748b;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title">
<h1><i class="fas fa-comments"></i> آراء العملاء</h1>
<p>إدارة ومراجعة آراء وتقييمات العملاء</p>
</div>
<a href="{{ route('cs.dashboard') }}" class="btn-header">
<i class="fas fa-arrow-right"></i> العودة
</a>
</div>
</section>

<div class="container">
<div class="coming-soon">
<i class="fas fa-tools"></i>
<h2>قريباً</h2>
<p>صفحة إدارة آراء العملاء قيد التطوير وستكون متاحة قريباً</p>
<a href="{{ route('cs.dashboard') }}" class="btn-primary">
<i class="fas fa-arrow-right"></i> العودة للوحة التحكم
</a>
</div>
</div>

</body>
</html>