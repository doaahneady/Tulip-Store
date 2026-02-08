<?php

namespace App\Http\Controllers\Legacy\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $query = User::with('role')->withCount('orders');

        // Filter by type (workers/customers)
        if ($request->type == 'workers') {
            $query->whereNotNull('role_id');
        } elseif ($request->type == 'customers') {
            $query->whereNull('role_id');
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%')
                    ->orWhere('mobile', 'like', '%'.$request->search.'%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            if ($request->role === 'customer') {
                $query->whereNull('role_id');
            } else {
                $query->where('role_id', $request->role);
            }
        }

        $users = $query->latest()->paginate(20);

        // Get all roles for the filter dropdown
        $roles = Role::all();

        // Calculate stats
        $sumCol = \Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : (\Schema::hasColumn('orders', 'total') ? 'total' : 'subtotal');
        $stats = [
            'total' => User::count(),
            'workers' => User::whereNotNull('role_id')->count(),
            'customers' => User::whereNull('role_id')->count(),
            'new_this_month' => User::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats'));
    }

    public function show(User $user)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $user->load([
            'orders' => function ($query) {
                $query->latest()->take(10);
            },
            'role',
        ]);

        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->where('status', 'delivered')->sum($sumCol),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'completed_orders' => $user->orders()->where('status', 'delivered')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function toggleAdmin(User $user)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            return back()->with('error', 'غير مصرح');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك تعديل حسابك الخاص');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        return back()->with('success', 'تم تحديث صلاحيات المستخدم بنجاح');
    }

    public function updateRole(Request $request, User $user)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            return back()->with('error', 'غير مصرح');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك تعديل دورك الخاص');
        }

        $request->validate([
            'role_id' => 'nullable|exists:roles,id',
        ]);

        // Update role and admin status
        $user->update([
            'role_id' => $request->role_id,
            'is_admin' => $request->role_id ? true : false,
        ]);

        $roleName = $request->role_id ? Role::find($request->role_id)->display_name : 'عميل';

        return back()->with('success', "تم تحديث دور المستخدم إلى: {$roleName}");
    }

    public function destroy(User $user)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            return back()->with('error', 'غير مصرح');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        if ($user->orders()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف مستخدم لديه طلبات');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
