@extends('dashboards.layouts.app', ['title' => 'التصنيفات', 'subtitle' => 'إضافة وتعديل وحذف التصنيفات'])

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-semibold text-gray-900">جميع التصنيفات</h3>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                <form method="GET" action="{{ route('dashboard.admin.categories') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الرابط" class="form-input w-56">
                    <button class="px-4 py-2 rounded-xl bg-gray-900 text-white">بحث</button>
                </form>
                <button type="button" onclick="document.getElementById('createCategoryModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 text-white">
                    إضافة تصنيف
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الترتيب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ \Illuminate\Support\Facades\Schema::hasColumn('categories', 'display_order') ? ($category->display_order ?? 0) : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php $active = \Illuminate\Support\Facades\Schema::hasColumn('categories', 'is_active') ? (bool) $category->is_active : true; @endphp
                            <span class="px-2 py-1 text-xs rounded {{ $active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" onclick="document.getElementById('editCategoryModal-{{ $category->id }}').classList.remove('hidden')" class="px-3 py-1 rounded-lg bg-blue-600 text-white text-sm">تعديل</button>
                            <form method="POST" action="{{ route('dashboard.admin.categories.delete', $category) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 rounded-lg bg-red-600 text-white text-sm" onclick="return confirm('حذف التصنيف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ $categories->links() }}
    </div>
</div>

<div id="createCategoryModal" class="fixed inset-0 bg-black/40 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900">إضافة تصنيف</h4>
            <button type="button" onclick="document.getElementById('createCategoryModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ route('dashboard.admin.categories.create') }}">
            @csrf
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                    <input type="text" name="name" class="form-input w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Slug (اختياري)</label>
                    <input type="text" name="slug" class="form-input w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">الوصف (اختياري)</label>
                    <textarea name="description" class="form-input w-full" rows="3"></textarea>
                </div>
                @if(\Illuminate\Support\Facades\Schema::hasColumn('categories', 'display_order'))
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الترتيب</label>
                        <input type="number" name="display_order" class="form-input w-full" value="0" min="0">
                    </div>
                @endif
                @if(\Illuminate\Support\Facades\Schema::hasColumn('categories', 'is_active'))
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                        <select name="is_active" class="form-select w-full">
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>
                @endif
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-xl border" onclick="document.getElementById('createCategoryModal').classList.add('hidden')">إلغاء</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white">حفظ</button>
            </div>
        </form>
    </div>
</div>

@foreach($categories as $category)
<div id="editCategoryModal-{{ $category->id }}" class="fixed inset-0 bg-black/40 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900">تعديل تصنيف</h4>
            <button type="button" onclick="document.getElementById('editCategoryModal-{{ $category->id }}').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ route('dashboard.admin.categories.update', $category) }}">
            @csrf
            @method('PUT')
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                    <input type="text" name="name" class="form-input w-full" value="{{ $category->name }}" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Slug</label>
                    <input type="text" name="slug" class="form-input w-full" value="{{ $category->slug }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">الوصف (اختياري)</label>
                    <textarea name="description" class="form-input w-full" rows="3">{{ $category->description }}</textarea>
                </div>
                @if(\Illuminate\Support\Facades\Schema::hasColumn('categories', 'display_order'))
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الترتيب</label>
                        <input type="number" name="display_order" class="form-input w-full" value="{{ $category->display_order ?? 0 }}" min="0">
                    </div>
                @endif
                @if(\Illuminate\Support\Facades\Schema::hasColumn('categories', 'is_active'))
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                        <select name="is_active" class="form-select w-full">
                            <option value="1" @selected((bool) $category->is_active)>نشط</option>
                            <option value="0" @selected(!(bool) $category->is_active)>غير نشط</option>
                        </select>
                    </div>
                @endif
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-xl border" onclick="document.getElementById('editCategoryModal-{{ $category->id }}').classList.add('hidden')">إلغاء</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white">تحديث</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
