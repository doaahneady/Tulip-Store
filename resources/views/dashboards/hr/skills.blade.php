@extends('dashboards.layouts.app')
@section('content')
@php $title = 'مهارات الموظفين'; $subtitle = 'إدارة الدورات ونقاط القوة وربطها بالموظفين'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-semibold text-gray-900">قائمة المهارات</h3>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                <form method="GET" action="{{ route('dashboard.hr.skills') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم" class="form-input w-56">
                    <select name="type" class="form-select w-40">
                        <option value="">النوع</option>
                        <option value="course" @selected(request('type') === 'course')>دورات</option>
                        <option value="strength" @selected(request('type') === 'strength')>نقاط قوة</option>
                    </select>
                    <button class="px-4 py-2 rounded-xl bg-gray-900 text-white">بحث</button>
                </form>
                <button type="button" onclick="document.getElementById('createSkillModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 text-white">
                    إضافة مهارة
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($skills as $skill)
                    <tr>
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ $skill->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $skill->type === 'course' ? 'دورة' : 'نقطة قوة' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $skill->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $skill->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" onclick="document.getElementById('editSkillModal-{{ $skill->id }}').classList.remove('hidden')" class="px-3 py-1 rounded-lg bg-blue-600 text-white text-sm">تعديل</button>
                            <form method="POST" action="{{ route('dashboard.hr.skills.delete', $skill) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 rounded-lg bg-red-600 text-white text-sm" onclick="return confirm('حذف المهارة؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        @if(method_exists(($skills ?? null),'links'))
            {{ $skills->links() }}
        @endif
    </div>
</div>

<div id="createSkillModal" class="fixed inset-0 bg-black/40 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900">إضافة مهارة</h4>
            <button type="button" onclick="document.getElementById('createSkillModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ route('dashboard.hr.skills.create') }}">
            @csrf
            <div class="p-4 grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                    <input type="text" name="name" class="form-input w-full" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">النوع</label>
                    <select name="type" class="form-select w-full" required>
                        <option value="strength">نقطة قوة</option>
                        <option value="course">دورة</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                    <select name="is_active" class="form-select w-full">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-xl border" onclick="document.getElementById('createSkillModal').classList.add('hidden')">إلغاء</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white">حفظ</button>
            </div>
        </form>
    </div>
</div>

@foreach($skills as $skill)
<div id="editSkillModal-{{ $skill->id }}" class="fixed inset-0 bg-black/40 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl mx-auto mt-16">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-semibold text-gray-900">تعديل مهارة</h4>
            <button type="button" onclick="document.getElementById('editSkillModal-{{ $skill->id }}').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ route('dashboard.hr.skills.update', $skill) }}">
            @csrf
            @method('PUT')
            <div class="p-4 grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الاسم</label>
                    <input type="text" name="name" class="form-input w-full" value="{{ $skill->name }}" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">النوع</label>
                    <select name="type" class="form-select w-full" required>
                        <option value="strength" @selected($skill->type === 'strength')>نقطة قوة</option>
                        <option value="course" @selected($skill->type === 'course')>دورة</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                    <select name="is_active" class="form-select w-full">
                        <option value="1" @selected($skill->is_active)>نشط</option>
                        <option value="0" @selected(! $skill->is_active)>غير نشط</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-xl border" onclick="document.getElementById('editSkillModal-{{ $skill->id }}').classList.add('hidden')">إلغاء</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white">تحديث</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection

