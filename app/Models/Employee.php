<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'employee_id', 'employee_code', 'first_name', 'last_name', 'email', 'phone', 'password',
        'profile_photo', 'bio', 'employee_id_card',
        'national_id', 'date_of_birth', 'gender', 'marital_status', 'address',
        'city', 'country', 'department', 'position', 'work_location', 'manager_id',
        'employment_type', 'work_schedule', 'hire_date', 'contract_end_date',
        'salary', 'approval_limit', 'commission_rate', 'bank_name', 'bank_account',
        'iban', 'emergency_contact_name', 'emergency_contact_phone',
        'emergency_contact_relation', 'status', 'security_level', 'notes',
        'skills', 'qualifications', 'certifications', 'languages',
        'preferred_communication', 'performance_score', 'last_review_date',
        'next_review_date', 'two_factor_enabled', 'ip_restrictions',
        // Role fields
        'is_admin', 'is_it', 'is_hr', 'is_cs', 'is_finance', 'is_driver_supervisor', 'is_trader',
        'is_manager', 'is_team_lead', 'can_approve_expenses', 'can_manage_inventory',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'contract_end_date' => 'date',
        'last_review_date' => 'date',
        'next_review_date' => 'date',
        'last_login_at' => 'datetime',
        'salary' => 'decimal:2',
        'approval_limit' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'performance_score' => 'decimal:2',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'skills' => 'array',
        'qualifications' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'work_schedule' => 'array',
        'ip_restrictions' => 'array',
        'is_admin' => 'boolean',
        'is_it' => 'boolean',
        'is_hr' => 'boolean',
        'is_cs' => 'boolean',
        'is_finance' => 'boolean',
        'is_driver_supervisor' => 'boolean',
        'is_trader' => 'boolean',
        'is_manager' => 'boolean',
        'is_team_lead' => 'boolean',
        'can_approve_expenses' => 'boolean',
        'can_manage_inventory' => 'boolean',
        'two_factor_enabled' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($employee) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('employees', 'employee_id')) {
                if (empty($employee->employee_id)) {
                    $employee->employee_id = 'EMP-'.Str::upper(Str::random(10));
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function trainingEnrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function dashboardPermissions()
    {
        return $this->hasMany(EmployeeDashboardPermission::class);
    }

    public function skillsCatalog()
    {
        return $this->belongsToMany(Skill::class, 'employee_skill')->withTimestamps();
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the driver record associated with this employee (if they are a driver)
     */
    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id', 'user_id');
    }

    /**
     * Get the manager of this employee
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get employees managed by this employee
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /**
     * Get security level name
     */
    public function getSecurityLevelNameAttribute()
    {
        $levels = [
            '1' => 'Basic',
            '2' => 'Medium',
            '3' => 'Medium-High',
            '4' => 'High',
            '5' => 'Maximum',
        ];

        return $levels[$this->security_level] ?? 'Unknown';
    }

    /**
     * Get all assigned roles
     */
    public function getAssignedRolesAttribute()
    {
        $roles = [];

        if ($this->is_admin) {
            $roles[] = 'Super Admin';
        }
        if ($this->is_it) {
            $roles[] = 'IT/DevOps';
        }
        if ($this->is_hr) {
            $roles[] = 'Human Resources';
        }
        if ($this->is_finance) {
            $roles[] = 'Finance';
        }
        if ($this->is_driver_supervisor) {
            $roles[] = 'Driver Supervisor';
        }
        if ($this->is_trader) {
            $roles[] = 'Store Manager';
        }
        if ($this->is_manager) {
            $roles[] = 'Manager';
        }
        if ($this->is_team_lead) {
            $roles[] = 'Team Lead';
        }

        return $roles;
    }

    /**
     * Get role count
     */
    public function getRoleCountAttribute()
    {
        return count($this->assigned_roles);
    }

    /**
     * Check if employee has specific permission
     */
    public function hasPermission($permission)
    {
        switch ($permission) {
            case 'approve_expenses':
                return $this->can_approve_expenses || $this->is_admin || $this->is_finance;
            case 'manage_inventory':
                return $this->can_manage_inventory || $this->is_admin || $this->is_trader;
            case 'manage_employees':
                return $this->is_manager || $this->is_admin || $this->is_hr;
            case 'view_financial_data':
                return $this->is_finance || $this->is_admin;
            case 'system_administration':
                return $this->is_it || $this->is_admin;
            default:
                return false;
        }
    }

    /**
     * Get employee's dashboard access
     */
    public function getAvailableDashboardsAttribute()
    {
        $definitions = [
            'admin' => [
                'name' => 'Super Admin',
                'description' => 'Complete platform oversight and management',
                'icon' => 'fa-crown',
                'route' => 'dashboard.admin.index',
                'color' => 'purple',
            ],
            'it' => [
                'name' => 'IT/DevOps',
                'description' => 'System monitoring and infrastructure management',
                'icon' => 'fa-server',
                'route' => 'dashboard.it.index',
                'color' => 'blue',
            ],
            'hr' => [
                'name' => 'Human Resources',
                'description' => 'Employee management and HR operations',
                'icon' => 'fa-users-cog',
                'route' => 'dashboard.hr.index',
                'color' => 'green',
            ],
            'cs' => [
                'name' => 'Customer Support',
                'description' => 'Support ticket management and customer care',
                'icon' => 'fa-headset',
                'route' => 'dashboard.cs.index',
                'color' => 'amber',
            ],
            'finance' => [
                'name' => 'Finance',
                'description' => 'Financial operations and transaction management',
                'icon' => 'fa-chart-line',
                'route' => 'dashboard.finance.index',
                'color' => 'emerald',
            ],
            'supervisor' => [
                'name' => 'Driver Supervisor',
                'description' => 'Fleet management and delivery coordination',
                'icon' => 'fa-route',
                'route' => 'dashboard.supervisor.index',
                'color' => 'orange',
            ],
            'vendor' => [
                'name' => 'Store Management',
                'description' => 'Product and store management',
                'icon' => 'fa-store',
                'route' => 'dashboard.vendor.index',
                'color' => 'indigo',
            ],
        ];

        $dashboards = [];
        foreach ($this->getAllowedDashboardKeys() as $key) {
            if (isset($definitions[$key])) {
                $dashboards[] = $definitions[$key];
            }
        }

        return $dashboards;
    }

    public function getExplicitDashboardKeys(): array
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('employee_dashboard_permissions')) {
            return [];
        }

        return $this->dashboardPermissions()->pluck('dashboard_key')->all();
    }

    public function getAllowedDashboardKeys(): array
    {
        $explicit = $this->getExplicitDashboardKeys();
        if (! empty($explicit)) {
            if (in_array('__none__', $explicit, true)) {
                return [];
            }

            return array_values(array_unique($explicit));
        }

        if ($this->is_admin) {
            return ['admin', 'it', 'hr', 'cs', 'finance', 'supervisor', 'vendor'];
        }

        if ($this->is_it) {
            return ['it'];
        }

        $keys = [];
        if ($this->is_hr) {
            $keys[] = 'hr';
        }
        if ($this->is_cs) {
            $keys[] = 'cs';
        }
        if ($this->is_finance) {
            $keys[] = 'finance';
        }
        if ($this->is_driver_supervisor) {
            $keys[] = 'supervisor';
        }
        if ($this->is_trader) {
            $keys[] = 'vendor';
        }

        return $keys;
    }

    public function canAccessDashboard(string $dashboardKey): bool
    {
        if ((bool) ($this->is_admin ?? false)) {
            return true;
        }

        return in_array($dashboardKey, $this->getAllowedDashboardKeys(), true);
    }

    /**
     * Update last login information
     */
    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'login_count' => $this->login_count + 1,
        ]);
    }
}
