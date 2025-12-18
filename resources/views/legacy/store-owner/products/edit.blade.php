@extends('layouts.dashboard')

@section('title', 'تعديل منتج')
@section('page-title', 'تعديل المنتج')
@section('role-gradient', 'from-purple-500 to-purple-700')
@section('role-icon', 'fas fa-store')
@section('role-label', 'صاحب المتجر')

@section('sidebar-menu')
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.dashboard'), 'icon' => 'fas fa-tachometer-alt', 'label' => 'لوحة التحكم'])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.products'), 'icon' => 'fas fa-box', 'label' => 'منتجاتي', 'active' => true])
    @include('components.dashboard.sidebar-item', ['href' => route('store-owner.orders'), 'icon' => 'fas fa-shopping-bag', 'label' => 'الطلبات'])
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-edit text-purple-500"></i> تعديل المنتج
        </h3>
        
        <form action="{{ route('store-owner.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسم المنتج</label>
                <input type="text" name="name" value="{{ $product->name }}" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الوصف</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">{{ $product->description }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">السعر</label>
                    <input type="number" name="price" step="0.01" value="{{ $product->price }}" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المخزون</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الفئة</label>
                <select name="category_id" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">صورة المنتج</label>
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" class="w-24 h-24 rounded-lg object-cover mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                    <i class="fas fa-save ml-1"></i> حفظ التغييرات
                </button>
                <a href="{{ route('store-owner.products') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
