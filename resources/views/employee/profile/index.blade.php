@extends('dashboards.layouts.app', ['title' => 'Employee Management'])

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Employee Management</h1>
                <p class="text-gray-600 mt-1">Manage and view employee profiles and information</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="loadStats()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>View Stats
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('employee.profile.employees') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Name, email, or employee code..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                            {{ $department }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Employee List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                Employees ({{ $employees->total() }})
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Employee
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department & Position
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Roles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Performance
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($employee->profile_photo)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ Storage::url($employee->profile_photo) }}" 
                                                 alt="{{ $employee->full_name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $employee->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 font-mono">
                                            {{ $employee->employee_code }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $employee->department }}</div>
                                <div class="text-sm text-gray-500">{{ $employee->position }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $employee->email }}</div>
                                <div class="text-sm text-gray-500">{{ $employee->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($employee->status === 'active') bg-green-100 text-green-800
                                    @elseif($employee->status === 'inactive') bg-gray-100 text-gray-800
                                    @elseif($employee->status === 'suspended') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($employee->status) }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    Level {{ $employee->security_level }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($employee->assigned_roles as $role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($employee->performance_score)
                                    <div class="flex items-center">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= $employee->performance_score ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="ml-1 text-sm text-gray-600">{{ $employee->performance_score }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">No rating</span>
                                @endif
                                @if($employee->last_login_at)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Last login: {{ $employee->last_login_at->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('employee.profile.show-employee', $employee) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="editEmployee({{ $employee->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($employee->status !== 'terminated')
                                    <button onclick="toggleEmployeeStatus({{ $employee->id }})" 
                                            class="text-{{ $employee->status === 'active' ? 'yellow' : 'green' }}-600 hover:text-{{ $employee->status === 'active' ? 'yellow' : 'green' }}-900">
                                        <i class="fas fa-{{ $employee->status === 'active' ? 'pause' : 'play' }}"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No employees found</p>
                                    <p class="text-sm">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Stats Modal -->
<div id="stats-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Employee Statistics</h3>
            <button onclick="document.getElementById('stats-modal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="stats-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Stats will be loaded here -->
        </div>
    </div>
</div>

<script>
function loadStats() {
    document.getElementById('stats-modal').classList.remove('hidden');
    
    fetch('{{ route("employee.profile.stats") }}')
        .then(response => response.json())
        .then(data => {
            const statsContent = document.getElementById('stats-content');
            statsContent.innerHTML = `
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Total Employees</p>
                            <p class="text-2xl font-bold text-blue-900">${data.total_employees}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-green-600 font-medium">Active Employees</p>
                            <p class="text-2xl font-bold text-green-900">${data.active_employees}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-building text-purple-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Departments</p>
                            <p class="text-2xl font-bold text-purple-900">${data.departments}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-orange-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-crown text-orange-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-orange-600 font-medium">Managers</p>
                            <p class="text-2xl font-bold text-orange-900">${data.managers}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-user-plus text-indigo-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-indigo-600 font-medium">Recent Hires (30 days)</p>
                            <p class="text-2xl font-bold text-indigo-900">${data.recent_hires}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-red-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-red-600 font-medium">Pending Reviews</p>
                            <p class="text-2xl font-bold text-red-900">${data.pending_reviews}</p>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error loading stats:', error);
            document.getElementById('stats-content').innerHTML = '<p class="text-red-500">Error loading statistics</p>';
        });
}

function editEmployee(employeeId) {
    // Redirect to employee profile page where they can edit
    window.location.href = `/employee/profile/employees/${employeeId}`;
}

function toggleEmployeeStatus(employeeId) {
    if (confirm('Are you sure you want to change this employee\'s status?')) {
        // This would need to be implemented in the controller
        console.log('Toggle status for employee:', employeeId);
    }
}
</script>
@endsection