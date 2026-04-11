

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">إجمالي المستخدمين</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($users->total() ?? 0); ?></h3>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">نشطون</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($users->where('status','active')->count() ?? 0); ?></h3>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-user-check text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">مديرون</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($users->where('is_admin', true)->count() ?? 0); ?></h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-user-shield text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs">جدد هذا الشهر</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($users->where('created_at','>=',now()->startOfMonth())->count() ?? 0); ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-user-plus text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-semibold text-gray-900">جميع المستخدمين</h3>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                <form method="GET" action="<?php echo e(route('dashboard.admin.users')); ?>" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث بالاسم أو البريد" class="form-input w-48 md:w-64">
                    <select name="role" class="form-select w-40">
                        <option value="">كل الأدوار</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->name); ?>" <?php if(request('role') === $role->name): echo 'selected'; endif; ?>><?php echo e($role->display_name ?? $role->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select name="status" class="form-select w-40">
                        <option value="">كل الحالات</option>
                        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>نشط</option>
                        <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>غير نشط</option>
                        <option value="suspended" <?php if(request('status') === 'suspended'): echo 'selected'; endif; ?>>معلق</option>
                    </select>
                    <button type="submit" class="btn btn-ghost btn-sm">
                        <i class="fas fa-filter"></i>
                        تصفية
                    </button>
                </form>
                <div class="flex items-center gap-2">
                    <a class="btn btn-secondary btn-sm" href="<?php echo e(route('dashboard.admin.export.users', array_merge(request()->query(), ['format' => 'csv']))); ?>">
                        <i class="fas fa-download"></i>
                        تصدير
                    </a>
                    <button class="btn btn-primary btn-sm" onclick="openAddModal()">
                        <i class="fas fa-plus"></i>
                        إضافة مستخدم
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>الحالة</th>
                        <th>إجمالي الإنفاق</th>
                        <th>تاريخ الانضمام</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <?php
                                    $displayName = is_array(data_get($user, 'name'))
                                        ? json_encode(data_get($user, 'name'))
                                        : (data_get($user, 'name') ?? 'Unknown');
                                    $initial = strtoupper(substr($displayName ?? 'U', 0, 1));
                                ?>
                                <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 text-sm font-semibold">
                                    <?php echo e($initial); ?>

                                </div>
                                <div>
                                    <div class="font-medium text-gray-900"><?php echo e($displayName); ?></div>
                                    <div class="text-gray-500 text-xs">ID: <?php echo e($user->id); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($user->email ?? 'لا يوجد'); ?></td>
                        <td>
                            <?php
                                $roleName = $user->is_admin ? 'Admin' : (optional($user->roles->first())->display_name ?? optional($user->roles->first())->name ?? 'User');
                                $roleClass = $user->is_admin ? 'badge-warning' : 'badge-gray';
                            ?>
                            <span class="badge <?php echo e($roleClass); ?>"><?php echo e($roleName); ?></span>
                        </td>
                        <td>
                            <span class="badge <?php echo e($user->status === 'active' ? 'badge-success' : ($user->status === 'suspended' ? 'badge-warning' : 'badge-error')); ?>">
                                <?php echo e($user->status === 'active' ? 'نشط' : ($user->status === 'suspended' ? 'معلق' : 'غير نشط')); ?>

                            </span>
                        </td>
                        <td>
                            $<?php echo e(number_format(($userSpendingMap[$user->id] ?? 0), 2)); ?>

                        </td>
                        <td><?php echo e($user->created_at ? $user->created_at->format('Y-m-d') : 'غير معروف'); ?></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button 
                                    class="btn btn-ghost btn-sm" 
                                    onclick="openEditModal(<?php echo e($user->id); ?>, <?php echo e(json_encode($user->name ?? '')); ?>, <?php echo e(json_encode($user->email ?? '')); ?>, <?php echo e(json_encode($user->phone ?? '')); ?>, <?php echo e(json_encode($user->status ?? 'active')); ?>)"
                                    title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form 
                                    action="<?php echo e(route('dashboard.admin.users.delete', $user->id)); ?>" 
                                    method="POST" 
                                    class="inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذه العملية.');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button 
                                        type="submit" 
                                        class="btn btn-ghost btn-sm text-error-600 hover:text-error-700"
                                        title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500">
                            لا توجد بيانات مستخدمين
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    $updateBaseUrl = route('dashboard.admin.users.update', ['user' => 0]);
?>
<script>
function openAddModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeAddModal() {
    const modal = document.getElementById('addUserModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
function openEditModal(id, name, email, phone, status) {
    const modal = document.getElementById('editUserModal');
    const form = document.getElementById('editUserForm');
    if (modal && form) {
        const action = "<?php echo e($updateBaseUrl); ?>".replace(/\/0$/, '/' + id);
        form.setAttribute('action', action);
        form.querySelector('input[name=\"name\"]').value = name || '';
        form.querySelector('input[name=\"email\"]').value = email || '';
        form.querySelector('input[name=\"phone\"]').value = phone || '';
        form.querySelector('select[name=\"status\"]').value = status || 'active';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeEditModal() {
    const modal = document.getElementById('editUserModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeAddModal()">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation();">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">إضافة مستخدم</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <form id="addUserForm" method="POST" action="<?php echo e(route('dashboard.admin.users.create')); ?>" class="p-6">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                    <input type="text" name="name" required class="form-input w-full" placeholder="الاسم الكامل">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" required class="form-input w-full" placeholder="user@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الهاتف</label>
                    <input type="text" name="phone" class="form-input w-full" placeholder="+1234567890">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور</label>
                        <input type="password" name="password" required class="form-input w-full" placeholder="٨ أحرف على الأقل">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" required class="form-input w-full" placeholder="أعد إدخال كلمة المرور">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الأدوار</label>
                    <div class="grid grid-cols-2 gap-2">
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="roles[]" value="<?php echo e($role->id); ?>" class="form-checkbox">
                                <span class="text-sm text-gray-700"><?php echo e($role->display_name ?? $role->name); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">إلغاء</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>
                    إنشاء مستخدم
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeEditModal()">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation();">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">تعديل المستخدم</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <form id="editUserForm" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit_user_name" 
                        required
                        class="form-input w-full"
                        placeholder="الاسم الكامل">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="edit_user_email" 
                        required
                        class="form-input w-full"
                        placeholder="user@example.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الهاتف</label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="edit_user_phone"
                        class="form-input w-full"
                        placeholder="+1234567890">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status" id="edit_user_status" required class="form-select w-full">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="suspended">معلق</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button 
                    type="button" 
                    onclick="closeEditModal()" 
                    class="btn btn-secondary">
                    إلغاء
                </button>
                <button 
                    type="submit" 
                    class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addUserModal').classList.remove('hidden');
    document.getElementById('addUserModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('addUserModal').classList.add('hidden');
    document.getElementById('addUserModal').classList.remove('flex');
    document.body.style.overflow = '';
}

function openEditModal(userId, name, email, phone, status) {
    document.getElementById('editUserForm').action = '<?php echo e(url("/dashboard/admin/users")); ?>/' + userId;
    document.getElementById('edit_user_name').value = name || '';
    document.getElementById('edit_user_email').value = email || '';
    document.getElementById('edit_user_phone').value = phone || '';
    document.getElementById('edit_user_status').value = status || 'active';
    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editUserModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editUserModal').classList.add('hidden');
    document.getElementById('editUserModal').classList.remove('flex');
    document.body.style.overflow = '';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.app', ['title' => 'إدارة المستخدمين', 'subtitle' => 'إدارة مستخدمي المنصة والصلاحيات'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/dashboards/super-admin/users.blade.php ENDPATH**/ ?>