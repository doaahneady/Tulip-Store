@extends('dashboards.layouts.app', ['title' => 'Employee Management', 'subtitle' => 'Manage employees and their information'])

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Employee Management</h2>
        <p class="text-gray-600">Manage all employees and their information</p>
    </div>
    <button class="btn btn-primary">
        <i class="fas fa-plus text-sm mr-2"></i>
        Add Employee
    </button>
</div>

<!-- Employee Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Total Employees</p>
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $metrics['total_employees'] ?? 156 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">Active Employees</p>
                    <h3 class="text-2xl font-semibold text-success-600">{{ $metrics['active_employees'] ?? 142 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-success-50 text-success-600 flex items-center justify-center">
                    <i class="fas fa-user-check text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">New Hires (Month)</p>
                    <h3 class="text-2xl font-semibold text-primary-600">{{ $metrics['new_hires_month'] ?? 8 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-user-plus text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-2">On Leave</p>
                    <h3 class="text-2xl font-semibold text-warning-600">{{ $metrics['employees_on_leave'] ?? 6 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-warning-50 text-warning-600 flex items-center justify-center">
                    <i class="fas fa-calendar-times text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employee List -->
<div class="card">
    <div class="card-header">
        <div class="flex items-center justify-between">
            <h3 class="card-title">All Employees</h3>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" placeholder="Search employees..." class="form-input pl-10">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select class="form-select">
                    <option>All Departments</option>
                    <option>IT</option>
                    <option>HR</option>
                    <option>Finance</option>
                    <option>Operations</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Hire Date</th>
                        <th>Salary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $employees = [
                            ['name' => 'John Smith', 'email' => 'john@tulip.com', 'department' => 'IT', 'position' => 'Senior Developer', 'status' => 'active', 'hire_date' => '2023-01-15', 'salary' => '$85,000'],
                            ['name' => 'Sarah Johnson', 'email' => 'sarah@tulip.com', 'department' => 'HR', 'position' => 'HR Manager', 'status' => 'active', 'hire_date' => '2022-08-20', 'salary' => '$75,000'],
                            ['name' => 'Mike Chen', 'email' => 'mike@tulip.com', 'department' => 'Finance', 'position' => 'Accountant', 'status' => 'active', 'hire_date' => '2023-03-10', 'salary' => '$65,000'],
                            ['name' => 'Lisa Rodriguez', 'email' => 'lisa@tulip.com', 'department' => 'Operations', 'position' => 'Operations Manager', 'status' => 'on_leave', 'hire_date' => '2022-11-05', 'salary' => '$80,000'],
                            ['name' => 'David Kim', 'email' => 'david@tulip.com', 'department' => 'IT', 'position' => 'DevOps Engineer', 'status' => 'active', 'hire_date' => '2023-06-01', 'salary' => '$90,000'],
                        ];
                    @endphp
                    @foreach($employees as $employee)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-sm font-medium">
                                    {{ strtoupper(substr($employee['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $employee['name'] }}</div>
                                    <div class="text-gray-500 text-sm">{{ $employee['email'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-gray">{{ $employee['department'] }}</span>
                        </td>
                        <td class="font-medium">{{ $employee['position'] }}</td>
                        <td>
                            <span class="badge {{ $employee['status'] === 'active' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst(str_replace('_', ' ', $employee['status'])) }}
                            </span>
                        </td>
                        <td class="text-gray-600">{{ date('M j, Y', strtotime($employee['hire_date'])) }}</td>
                        <td class="font-medium">{{ $employee['salary'] }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="btn btn-sm btn-ghost text-error-600">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection