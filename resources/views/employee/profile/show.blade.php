@extends('dashboards.layouts.app', ['title' => 'My Profile'])

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    @if($employee->profile_photo)
                        <img src="{{ Storage::url($employee->profile_photo) }}" 
                             alt="{{ $employee->full_name }}" 
                             class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-white flex items-center justify-center">
                            <span class="text-2xl font-bold text-blue-600">
                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 text-white">
                    <h1 class="text-3xl font-bold">{{ $employee->full_name }}</h1>
                    <p class="text-blue-100 text-lg">{{ $employee->position }}</p>
                    <p class="text-blue-200">{{ $employee->department }}</p>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                            {{ $employee->employee_code }}
                        </span>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                            Security Level {{ $employee->security_level }}
                        </span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('employee.profile.edit') }}" 
                       class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-3"></i>
                    Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-900">{{ $employee->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                        <p class="text-gray-900">{{ $employee->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Date of Birth</label>
                        <p class="text-gray-900">{{ $employee->date_of_birth?->format('F j, Y') ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Gender</label>
                        <p class="text-gray-900">{{ ucfirst($employee->gender ?? 'Not specified') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                        <p class="text-gray-900">
                            {{ $employee->address ?? 'Not provided' }}
                            @if($employee->city || $employee->country)
                                <br>{{ $employee->city }}{{ $employee->city && $employee->country ? ', ' : '' }}{{ $employee->country }}
                            @endif
                        </p>
                    </div>
                </div>

                @if($employee->bio)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Bio</label>
                    <p class="text-gray-900">{{ $employee->bio }}</p>
                </div>
                @endif
            </div>

            <!-- Employment Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-briefcase text-blue-600 mr-3"></i>
                    Employment Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Employee Code</label>
                        <p class="text-gray-900 font-mono">{{ $employee->employee_code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Employment Type</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Hire Date</label>
                        <p class="text-gray-900">{{ $employee->hire_date?->format('F j, Y') ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($employee->status === 'active') bg-green-100 text-green-800
                            @elseif($employee->status === 'inactive') bg-gray-100 text-gray-800
                            @elseif($employee->status === 'suspended') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                    @if($employee->manager)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Manager</label>
                        <p class="text-gray-900">{{ $employee->manager->full_name }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Work Location</label>
                        <p class="text-gray-900">{{ $employee->work_location ?? 'Main Office' }}</p>
                    </div>
                </div>
            </div>

            <!-- HR & Finance Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-chart-pie text-blue-600 mr-3"></i>
                    Work Summary
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Attendance (This Month)</label>
                        <div class="text-sm text-gray-900">
                            Present: <span class="font-semibold">{{ $attendanceStats['present_month'] ?? 0 }}</span><br>
                            Late: <span class="font-semibold">{{ $attendanceStats['late_month'] ?? 0 }}</span><br>
                            Early Leave: <span class="font-semibold">{{ $attendanceStats['early_leave_month'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Leaves</label>
                        <div class="text-sm text-gray-900">
                            Pending: <span class="font-semibold">{{ $leaveSummary['pending'] ?? 0 }}</span><br>
                            Approved: <span class="font-semibold">{{ $leaveSummary['approved'] ?? 0 }}</span><br>
                            Rejected: <span class="font-semibold">{{ $leaveSummary['rejected'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Payroll</label>
                        <div class="text-sm text-gray-900">
                            Period: <span class="font-semibold">{{ $payrollSummary['last_period'] ?? '-' }}</span><br>
                            Net Pay: <span class="font-semibold">${{ number_format($payrollSummary['last_net_pay'] ?? 0, 2) }}</span><br>
                            Approved Count: <span class="font-semibold">{{ $payrollSummary['approved_count'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Upcoming Shifts</label>
                        <ul class="text-sm text-gray-900 space-y-1">
                            @forelse($upcomingShifts as $s)
                                <li>{{ $s->shift_date }} • {{ $s->start_time }}–{{ $s->end_time }}</li>
                            @empty
                                <li class="text-gray-500">No upcoming shifts</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Skills & Qualifications -->
            @if($employee->skills || $employee->qualifications || $employee->certifications || $employee->languages)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>
                    Skills & Qualifications
                </h2>
                
                @if($employee->skills)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-500 mb-3">Skills</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($employee->skills as $skill)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($employee->languages)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-500 mb-3">Languages</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($employee->languages as $language)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $language }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($employee->qualifications)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-500 mb-3">Qualifications</label>
                    <ul class="space-y-2">
                        @foreach($employee->qualifications as $qualification)
                            <li class="flex items-center text-gray-900">
                                <i class="fas fa-certificate text-yellow-500 mr-2"></i>
                                {{ $qualification }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($employee->certifications)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-3">Certifications</label>
                    <ul class="space-y-2">
                        @foreach($employee->certifications as $certification)
                            <li class="flex items-center text-gray-900">
                                <i class="fas fa-award text-purple-500 mr-2"></i>
                                {{ $certification }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Dashboard Access -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tachometer-alt text-blue-600 mr-3"></i>
                    Dashboard Access
                </h3>
                
                <div class="space-y-3">
                    @foreach($employee->available_dashboards as $dashboard)
                        <a href="{{ route($dashboard['route']) }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-{{ $dashboard['color'] }}-300 hover:bg-{{ $dashboard['color'] }}-50 transition-colors group">
                            <i class="fas {{ $dashboard['icon'] }} text-{{ $dashboard['color'] }}-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900 group-hover:text-{{ $dashboard['color'] }}-700">
                                    {{ $dashboard['name'] }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $dashboard['description'] }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shield-alt text-blue-600 mr-3"></i>
                    Roles & Permissions
                </h3>
                
                <div class="space-y-2">
                    @foreach($employee->assigned_roles as $role)
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-900">{{ $role }}</span>
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <div class="flex justify-between">
                            <span>Security Level:</span>
                            <span class="font-medium">{{ $employee->security_level_name }}</span>
                        </div>
                        @if($employee->approval_limit > 0)
                        <div class="flex justify-between mt-1">
                            <span>Approval Limit:</span>
                            <span class="font-medium">${{ number_format($employee->approval_limit, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            @if($employee->emergency_contact_name)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-phone text-red-600 mr-3"></i>
                    Emergency Contact
                </h3>
                
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-500">Name:</span>
                        <p class="font-medium text-gray-900">{{ $employee->emergency_contact_name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Phone:</span>
                        <p class="font-medium text-gray-900">{{ $employee->emergency_contact_phone }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Relation:</span>
                        <p class="font-medium text-gray-900">{{ ucfirst($employee->emergency_contact_relation) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Account Security -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-lock text-blue-600 mr-3"></i>
                    Account Security
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-900">Two-Factor Authentication</span>
                        <form action="{{ route('employee.profile.toggle-2fa') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm px-3 py-1 rounded-full font-medium
                                @if($employee->two_factor_enabled) 
                                    bg-green-100 text-green-800 hover:bg-green-200
                                @else 
                                    bg-gray-100 text-gray-800 hover:bg-gray-200
                                @endif">
                                {{ $employee->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                            </button>
                        </form>
                    </div>
                    
                    <div class="text-sm text-gray-500">
                        <div class="flex justify-between">
                            <span>Last Login:</span>
                            <span>{{ $employee->last_login_at?->diffForHumans() ?? 'Never' }}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span>Login Count:</span>
                            <span>{{ number_format($employee->login_count) }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button onclick="document.getElementById('password-modal').classList.remove('hidden')" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-key mr-2"></i>Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div id="password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
        
        <form action="{{ route('employee.profile.update-password') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="document.getElementById('password-modal').classList.add('hidden')"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
