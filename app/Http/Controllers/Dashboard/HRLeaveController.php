<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HRLeaveController extends Controller
{
    // ============================================
    // LEAVE MANAGEMENT (Flows 11-16)
    // ============================================

    public function index()
    {
        $leaves = LeaveRequest::with('employee')->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'this_month' => LeaveRequest::whereMonth('created_at', now()->month)->count(),
        ];

        return view('dashboards.hr.leaves.index', compact('leaves', 'stats'));
    }

    // Flow 11: Submit Leave Request
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();

        return view('dashboards.hr.leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:annual,sick,emergency,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $validated['days_count'] = $startDate->diffInDays($endDate) + 1;
        $validated['status'] = 'pending';

        LeaveRequest::create($validated);

        return redirect()->route('dashboard.hr.leaves')->with('success', 'تم تقديم طلب الإجازة بنجاح');
    }

    // Flow 12: Modify Leave Request
    public function edit(LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه أو رفضه');
        }

        $employees = Employee::where('status', 'active')->get();

        return view('dashboards.hr.leaves.edit', compact('leave', 'employees'));
    }

    public function update(Request $request, LeaveRequest $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'لا يمكن تعديل طلب تمت الموافقة عليه أو رفضه');
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:annual,sick,emergency,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $validated['days_count'] = $startDate->diffInDays($endDate) + 1;

        $leave->update($validated);

        return redirect()->route('dashboard.hr.leaves')->with('success', 'تم تعديل طلب الإجازة بنجاح');
    }

    // Flow 13: Approve Leave
    public function approve(LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth('employee')->id(),
            'approved_at' => now(),
        ]);

        // Flow 16: Update Availability - sync with scheduling
        $this->updateAvailability($leave);

        return back()->with('success', 'تم قبول طلب الإجازة بنجاح');
    }

    // Flow 14: Reject Leave
    public function reject(Request $request, LeaveRequest $leave)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth('employee')->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'تم رفض طلب الإجازة');
    }

    // Flow 15: Cancel Leave
    public function cancel(LeaveRequest $leave)
    {
        if ($leave->status !== 'approved') {
            return back()->with('error', 'يمكن إلغاء الإجازات الموافق عليها فقط');
        }

        $leave->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء الإجازة بنجاح');
    }

    // Flow 16: Update Availability
    private function updateAvailability(LeaveRequest $leave)
    {
        // Mark employee as unavailable during leave period
        // This would integrate with shift scheduling system
        // For now, we'll just log it
        \Log::info("Employee {$leave->employee_id} unavailable from {$leave->start_date} to {$leave->end_date}");
    }
}
