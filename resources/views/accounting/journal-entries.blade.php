<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>القيود اليومية - نظام المحاسبة</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
:root { --primary: #059669; --primary-dark: #047857; --danger: #ef4444; --success: #22c55e; --warning: #f59e0b; --dark: #1e293b; --gray: #64748b; }
* { font-family: 'Cairo', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
body { background: #f0fdf4; min-height: 100vh; }
.container { max-width: 1400px; margin: 0 auto; padding: 2rem; margin-top: 80px; }
.page-header { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 2rem; border-radius: 16px; margin-bottom: 2rem; color: #fff; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
.page-header h1 { font-size: 1.8rem; font-weight: 800; display: flex; align-items: center; gap: 1rem; }
.card { background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 1.5rem; }
.card-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
.card-header h3 { font-size: 1.1rem; font-weight: 700; color: var(--dark); }
.card-body { padding: 0; }
.btn { padding: 0.7rem 1.2rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem; border: none; text-decoration: none; font-size: 0.9rem; }
.btn-primary { background: var(--primary); color: #fff; }
.btn-white { background: #fff; color: var(--primary); }
.btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
.btn-success { background: var(--success); color: #fff; }
.btn-warning { background: var(--warning); color: #fff; }
.btn-danger { background: var(--danger); color: #fff; }
.filters { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
.filters select, .filters input { padding: 0.6rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; }
</style>
</head>
<body>
@include('components.navbar')
<div class="container">
<div class="page-header">
    <div>
        <h1><i class="fas fa-book"></i> القيود اليومية</h1>
        <p style="margin-top: 0.5rem; opacity: 0.9;">إدارة وعرض جميع القيود المحاسبية</p>
    </div>
    <a href="{{ route('accounting.journal-entries.create') }}" class="btn btn-white">
        <i class="fas fa-plus"></i> قيد جديد
    </a>
</div>
