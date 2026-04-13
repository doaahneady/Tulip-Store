<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Selection - {{ config('app.name', 'Tulip Store') }}</title>
    <!-- fav icon -->
        <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">
    @vite(['resources/css/app.css'])
</head>
<body class="h-full font-sans antialiased bg-gray-50">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                            <i class="fas fa-store text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Tulip Store</span>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-600">
                            Welcome, <span class="font-semibold text-gray-900">{{ $employee->full_name }}</span>
                        </div>
                        <form action="{{ route('employee.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                        Welcome to Your Dashboard
                    </h1>
                    <p class="mt-4 text-xl text-gray-600">
                        Select the dashboard you'd like to access based on your role
                    </p>
                </div>

                <!-- Dashboard Cards -->
                @if(count($availableDashboards) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                        @foreach($availableDashboards as $dashboard)
                            @php
                                $color = $dashboard['color'] ?? 'gray';
                                $hoverTitleClass = match ($color) {
                                    'purple' => 'group-hover:text-purple-600',
                                    'blue' => 'group-hover:text-blue-600',
                                    'green' => 'group-hover:text-green-600',
                                    'emerald' => 'group-hover:text-emerald-600',
                                    'orange' => 'group-hover:text-orange-600',
                                    'indigo' => 'group-hover:text-indigo-600',
                                    default => 'group-hover:text-gray-600',
                                };
                                $hoverCircleBgClass = match ($color) {
                                    'purple' => 'group-hover:bg-purple-100',
                                    'blue' => 'group-hover:bg-blue-100',
                                    'green' => 'group-hover:bg-green-100',
                                    'emerald' => 'group-hover:bg-emerald-100',
                                    'orange' => 'group-hover:bg-orange-100',
                                    'indigo' => 'group-hover:bg-indigo-100',
                                    default => 'group-hover:bg-gray-100',
                                };
                                $hoverArrowClass = match ($color) {
                                    'purple' => 'group-hover:text-purple-600',
                                    'blue' => 'group-hover:text-blue-600',
                                    'green' => 'group-hover:text-green-600',
                                    'emerald' => 'group-hover:text-emerald-600',
                                    'orange' => 'group-hover:text-orange-600',
                                    'indigo' => 'group-hover:text-indigo-600',
                                    default => 'group-hover:text-gray-600',
                                };
                            @endphp
                            <a href="{{ route($dashboard['route']) }}" 
                               class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-200 overflow-hidden">
                                <!-- Color accent -->
                                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r 
                                    @if($dashboard['color'] === 'purple') from-purple-500 to-purple-600
                                    @elseif($dashboard['color'] === 'blue') from-blue-500 to-blue-600
                                    @elseif($dashboard['color'] === 'green') from-green-500 to-green-600
                                    @elseif($dashboard['color'] === 'emerald') from-emerald-500 to-emerald-600
                                    @elseif($dashboard['color'] === 'orange') from-orange-500 to-orange-600
                                    @elseif($dashboard['color'] === 'indigo') from-indigo-500 to-indigo-600
                                    @else from-gray-500 to-gray-600
                                    @endif"></div>
                                
                                <div class="p-8">
                                    <!-- Icon -->
                                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 rounded-2xl
                                        @if($dashboard['color'] === 'purple') bg-purple-100 text-purple-600
                                        @elseif($dashboard['color'] === 'blue') bg-blue-100 text-blue-600
                                        @elseif($dashboard['color'] === 'green') bg-green-100 text-green-600
                                        @elseif($dashboard['color'] === 'emerald') bg-emerald-100 text-emerald-600
                                        @elseif($dashboard['color'] === 'orange') bg-orange-100 text-orange-600
                                        @elseif($dashboard['color'] === 'indigo') bg-indigo-100 text-indigo-600
                                        @else bg-gray-100 text-gray-600
                                        @endif
                                        group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas {{ $dashboard['icon'] }} text-2xl"></i>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="text-center">
                                        <h3 class="text-xl font-bold text-gray-900 mb-3 {{ $hoverTitleClass }} transition-colors">
                                            {{ $dashboard['name'] }}
                                        </h3>
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ $dashboard['description'] }}
                                        </p>
                                    </div>
                                    
                                    <!-- Arrow -->
                                    <div class="flex justify-center mt-6">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 {{ $hoverCircleBgClass }} transition-colors">
                                            <i class="fas fa-arrow-right text-gray-400 {{ $hoverArrowClass }} transition-colors"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <!-- No Dashboards Available -->
                    <div class="text-center py-12">
                        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 mb-6">
                            <i class="fas fa-exclamation-triangle text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Dashboard Access</h3>
                        <p class="text-gray-600 mb-6">
                            You don't have access to any dashboards yet. Please contact your administrator to assign you appropriate roles.
                        </p>
                        <div class="flex justify-center gap-4">
                            <form action="{{ route('employee.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Employee Info -->
                <div class="mt-16 bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Employee Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Employee ID:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $employee->employee_code }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Department:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $employee->department ?? 'Not assigned' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Position:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $employee->position ?? 'Not assigned' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                @if($employee->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
