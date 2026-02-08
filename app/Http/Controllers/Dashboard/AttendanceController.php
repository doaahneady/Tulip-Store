<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $employee = auth('employee')->user();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(20);

        $todayAttendances = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->orderBy('check_in', 'desc')
            ->get();

        $activeShift = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->orderBy('check_in', 'desc')
            ->first();

        $todaySummary = $todayAttendances->first();

        return view('dashboards.attendance.index', compact('attendance', 'todayAttendances', 'activeShift', 'todaySummary'));
    }

    public function checkIn(Request $request)
    {
        $employee = auth('employee')->user();
        $today = today()->toDateString();

        return DB::transaction(function () use ($employee, $today) {
            $activeShift = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->lockForUpdate()
                ->orderBy('check_in', 'desc')
                ->first();

            if ($activeShift) {
                return redirect()->back()->with('error', 'You already have an active shift. Please check out first.');
            }

            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'check_in' => now()->format('H:i:s'),
                'status' => 'present',
            ]);

            return redirect()->back()->with('success', 'Checked in successfully.');
        });
    }

    public function checkOut(Request $request)
    {
        $employee = auth('employee')->user();

        $today = today()->toDateString();

        return DB::transaction(function () use ($employee, $today) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->lockForUpdate()
                ->orderBy('check_in', 'desc')
                ->first();

            if (! $todayAttendance) {
                return redirect()->back()->with('error', 'You have not checked in yet.');
            }

            $checkOutTime = now();
            $checkInTime = Carbon::parse($todayAttendance->date->toDateString().' '.$todayAttendance->check_in);
            $workMinutes = $checkInTime->diffInMinutes($checkOutTime);

            $todayAttendance->update([
                'check_out' => $checkOutTime->format('H:i:s'),
                'work_hours' => $workMinutes,
            ]);

            return redirect()->back()->with('success', 'Checked out successfully.');
        });
    }
}
