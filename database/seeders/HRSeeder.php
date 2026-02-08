<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\PerformanceReview;
use App\Models\TrainingProgram;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HRSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample employees
        $employees = [
            [
                'employee_code' => 'EMP00001',
                'first_name' => 'أحمد',
                'last_name' => 'محمد',
                'email' => 'ahmed.mohamed@company.com',
                'phone' => '0501234567',
                'department' => 'تقنية المعلومات',
                'position' => 'مطور برمجيات',
                'hire_date' => Carbon::now()->subYears(2),
                'salary' => 8000.00,
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP00002',
                'first_name' => 'فاطمة',
                'last_name' => 'علي',
                'email' => 'fatima.ali@company.com',
                'phone' => '0507654321',
                'department' => 'الموارد البشرية',
                'position' => 'مدير موارد بشرية',
                'hire_date' => Carbon::now()->subYears(3),
                'salary' => 10000.00,
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP00003',
                'first_name' => 'خالد',
                'last_name' => 'عبدالله',
                'email' => 'khaled.abdullah@company.com',
                'phone' => '0509876543',
                'department' => 'المبيعات',
                'position' => 'مدير مبيعات',
                'hire_date' => Carbon::now()->subYear(),
                'salary' => 9000.00,
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP00004',
                'first_name' => 'نورة',
                'last_name' => 'سعيد',
                'email' => 'noura.saeed@company.com',
                'phone' => '0503456789',
                'department' => 'المحاسبة',
                'position' => 'محاسب',
                'hire_date' => Carbon::now()->subMonths(6),
                'salary' => 7000.00,
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP00005',
                'first_name' => 'عمر',
                'last_name' => 'حسن',
                'email' => 'omar.hassan@company.com',
                'phone' => '0506543210',
                'department' => 'التسويق',
                'position' => 'مسوق رقمي',
                'hire_date' => Carbon::now()->subMonths(8),
                'salary' => 6500.00,
                'status' => 'on_leave',
            ],
        ];

        foreach ($employees as $employeeData) {
            $employee = Employee::create($employeeData);

            // Create attendance records for the last 5 days
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subDays($i);
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date,
                    'check_in' => '08:00:00',
                    'check_out' => '17:00:00',
                    'work_hours' => 540, // 9 hours in minutes
                    'overtime_hours' => 0,
                    'status' => 'present',
                ]);
            }

            // Create payroll for current month
            Payroll::create([
                'employee_id' => $employee->id,
                'month' => Carbon::now()->format('Y-m'),
                'basic_salary' => $employee->salary,
                'allowances' => 500.00,
                'bonuses' => 0,
                'overtime_pay' => 0,
                'deductions' => 0,
                'tax' => $employee->salary * 0.05,
                'insurance' => 400.00,
                'net_salary' => $employee->salary + 500 - ($employee->salary * 0.05) - 400,
                'status' => 'draft',
            ]);
        }

        // Create some leave requests
        $employee1 = Employee::first();
        $employee5 = Employee::find(5);

        LeaveRequest::create([
            'employee_id' => $employee1->id,
            'leave_type' => 'annual',
            'start_date' => Carbon::now()->addDays(10),
            'end_date' => Carbon::now()->addDays(15),
            'days_count' => 5,
            'reason' => 'إجازة سنوية',
            'status' => 'pending',
        ]);

        LeaveRequest::create([
            'employee_id' => $employee5->id,
            'leave_type' => 'sick',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(3),
            'days_count' => 5,
            'reason' => 'إجازة مرضية',
            'status' => 'approved',
            'approved_by' => 1,
            'approved_at' => Carbon::now(),
        ]);

        // Create training programs
        TrainingProgram::create([
            'title' => 'دورة تطوير الويب المتقدمة',
            'description' => 'دورة شاملة في تطوير تطبيقات الويب باستخدام Laravel و Vue.js',
            'trainer' => 'م. محمد أحمد',
            'start_date' => Carbon::now()->addMonth(),
            'end_date' => Carbon::now()->addMonth()->addDays(5),
            'duration_hours' => 40,
            'location' => 'قاعة التدريب - الطابق الثالث',
            'cost' => 5000.00,
            'max_participants' => 20,
            'status' => 'scheduled',
        ]);

        TrainingProgram::create([
            'title' => 'إدارة المشاريع الاحترافية',
            'description' => 'دورة في إدارة المشاريع وفق منهجية Agile',
            'trainer' => 'د. سارة عبدالله',
            'start_date' => Carbon::now()->addMonths(2),
            'end_date' => Carbon::now()->addMonths(2)->addDays(3),
            'duration_hours' => 24,
            'location' => 'قاعة الاجتماعات الكبرى',
            'cost' => 3000.00,
            'max_participants' => 15,
            'status' => 'scheduled',
        ]);

        // Create performance reviews
        $user = User::first();
        if ($user) {
            PerformanceReview::create([
                'employee_id' => $employee1->id,
                'reviewer_id' => $user->id,
                'review_period' => 'Q4 2024',
                'review_date' => Carbon::now(),
                'performance_score' => 85,
                'attendance_score' => 90,
                'quality_score' => 88,
                'teamwork_score' => 92,
                'overall_rating' => 4,
                'strengths' => 'مهارات تقنية ممتازة، التزام بالمواعيد، عمل جماعي رائع',
                'areas_for_improvement' => 'تحسين مهارات التواصل مع العملاء',
                'goals' => 'قيادة مشروع كبير في الربع القادم',
                'comments' => 'أداء ممتاز بشكل عام',
            ]);
        }

        echo "✅ تم إنشاء بيانات تجريبية للموارد البشرية بنجاح!\n";
        echo "📊 تم إنشاء:\n";
        echo "   - 5 موظفين\n";
        echo "   - 25 سجل حضور\n";
        echo "   - 5 كشوف رواتب\n";
        echo "   - 2 طلب إجازة\n";
        echo "   - 2 برنامج تدريبي\n";
        echo "   - 1 تقييم أداء\n";
    }
}
