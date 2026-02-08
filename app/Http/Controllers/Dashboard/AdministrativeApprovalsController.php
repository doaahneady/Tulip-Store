<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdministrativeApprovalsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    public function index()
    {
        $employee = auth('employee')->user();

        $requests = null;
        if (Schema::hasTable('administrative_approvals')) {
            $requests = AdministrativeApproval::query()
                ->where('requester_employee_id', $employee->id)
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString();
        }

        $canManage = (bool) (($employee->is_admin ?? false) || ($employee->is_hr ?? false));

        return view('dashboards.administrative-approvals.index', compact('requests', 'canManage'));
    }

    public function store(Request $request)
    {
        abort_unless(Schema::hasTable('administrative_approvals'), 404);

        $validated = $request->validate([
            'category' => 'required|in:money,day_off,other',
            'amount' => 'nullable|numeric|min:0.01|required_if:category,money',
            'start_date' => 'nullable|date|required_if:category,day_off',
            'end_date' => 'nullable|date|after_or_equal:start_date|required_if:category,day_off',
            'details' => 'required|string|max:2000',
        ]);

        $employee = auth('employee')->user();

        AdministrativeApproval::create([
            'requester_employee_id' => $employee->id,
            'category' => $validated['category'],
            'amount' => $validated['amount'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'details' => $validated['details'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Request submitted');
    }

    public function manage(Request $request)
    {
        abort_unless(Schema::hasTable('administrative_approvals'), 404);

        $requests = AdministrativeApproval::query()
            ->with(['requester'])
            ->where('status', 'pending')
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('dashboards.administrative-approvals.manage', compact('requests'));
    }

    public function approve(AdministrativeApproval $approval)
    {
        abort_unless(Schema::hasTable('administrative_approvals'), 404);
        abort_unless($approval->status === 'pending', 409);

        $employee = auth('employee')->user();
        $role = ($employee->is_hr ?? false) ? 'hr' : 'admin';

        $approval->update([
            'status' => 'approved',
            'decided_by_employee_id' => $employee->id,
            'decided_by_role' => $role,
            'decided_at' => now(),
        ]);

        return back()->with('success', 'Request approved');
    }

    public function reject(AdministrativeApproval $approval, Request $request)
    {
        abort_unless(Schema::hasTable('administrative_approvals'), 404);
        abort_unless($approval->status === 'pending', 409);

        $employee = auth('employee')->user();
        $role = ($employee->is_hr ?? false) ? 'hr' : 'admin';

        $approval->update([
            'status' => 'rejected',
            'decided_by_employee_id' => $employee->id,
            'decided_by_role' => $role,
            'decided_at' => now(),
        ]);

        return back()->with('success', 'Request rejected');
    }
}
