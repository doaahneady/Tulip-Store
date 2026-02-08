@extends('layouts.dashboard')

@section('title', 'إدارة الوظائف')

@section('content')

<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <h3 class="text-lg font-bold text-gray-800">قائمة الوظائف</h3>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="document.getElementById('addPositionModal').showModal()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                إضافة وظيفة
            </button>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">المسمى الوظيفي</th>
                    <th class="px-4 py-2 text-left">القسم</th>
                    <th class="px-4 py-2 text-left">الحالة</th>
                    <th class="px-4 py-2 text-left">عدد الشواغر</th>
                    <th class="px-4 py-2 text-left">تاريخ الإنشاء</th>
                    <th class="px-4 py-2 text-left">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $position)
                    <tr class="border-t">
                        <td class="px-4 py-2 font-medium">{{ $position->title }}</td>
                        <td class="px-4 py-2">{{ $position->department }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $position->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $position->status == 'open' ? 'مفتوح' : 'مغلق' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $position->vacancies ?? 0 }}</td>
                        <td class="px-4 py-2">{{ $position->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-briefcase text-4xl text-gray-300"></i>
                                <p>لا توجد وظائف مضافة حالياً</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $positions->links() }}
    </div>
</div>

<!-- Add Position Modal -->
<dialog id="addPositionModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">إضافة وظيفة جديدة</h3>
        <form action="{{ route('dashboard.hr.job-positions.create') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="label">المسمى الوظيفي</label>
                    <input type="text" name="title" class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label">القسم</label>
                    <select name="department" class="select select-bordered w-full" required>
                        <option value="">اختر القسم</option>
                        @foreach($departments as $dep)
                            <option value="{{ $dep }}">{{ $dep }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">عدد الشواغر</label>
                        <input type="number" name="vacancies" class="input input-bordered w-full" min="1" value="1">
                    </div>
                    <div>
                        <label class="label">الحالة</label>
                        <select name="status" class="select select-bordered w-full">
                            <option value="open">مفتوح</option>
                            <option value="closed">مغلق</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="label">الوصف الوظيفي</label>
                    <textarea name="description" class="textarea textarea-bordered w-full h-24"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('addPositionModal').close()">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
