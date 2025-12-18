@php
    $user = auth()->user();
    $unreadCount = $unreadCount ?? 0;
    $notifications = $notifications ?? collect();
@endphp

<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Mobile menu button -->
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" onclick="toggleMobileSidebar()">
        <span class="sr-only">Open sidebar</span>
        <i class="fas fa-bars h-6 w-6"></i>
    </button>

    <!-- Separator -->
    <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <!-- Search -->
        <form class="relative flex flex-1" action="#" method="GET">
            <label for="search-field" class="sr-only">Search</label>
            <i class="fas fa-search pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400 pl-3 flex items-center"></i>
            <input id="search-field" class="block h-full w-full border-0 py-0 pl-10 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm bg-transparent" placeholder="Search..." type="search" name="search">
        </form>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <!-- Real-time status -->
            <div class="flex items-center gap-x-2 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse-slow"></div>
                Live
            </div>

            <!-- Notifications button -->
            <button type="button" class="relative -m-2.5 p-2.5 text-gray-400 hover:text-gray-500" onclick="toggleNotifications()">
                <span class="sr-only">View notifications</span>
                <i class="fas fa-bell h-6 w-6"></i>
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center text-xs font-medium text-white">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Separator -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200"></div>

            <!-- Profile dropdown -->
            <div class="relative">
                <button type="button" class="-m-1.5 flex items-center p-1.5 hover:bg-gray-50 rounded-lg transition-colors" onclick="toggleUserMenu()">
                    <span class="sr-only">Open user menu</span>
                    <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold text-sm">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900">{{ $user->name ?? 'User' }}</span>
                        <i class="fas fa-chevron-down ml-2 h-3 w-3 text-gray-400"></i>
                    </span>
                </button>

                <!-- User dropdown menu -->
                <div class="absolute right-0 z-10 mt-2.5 w-64 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none hidden" id="user-dropdown">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">{{ $user->name ?? 'User' }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email ?? '' }}</p>
                        <div class="mt-2 flex flex-wrap gap-1">
                            @php
                                $roles = [];
                                if ($user->is_admin ?? false) $roles[] = 'Admin';
                                if ($user->is_trader ?? false) $roles[] = 'Store Owner';
                                if ($user->is_hr ?? false) $roles[] = 'HR';
                                if ($user->is_finance ?? false) $roles[] = 'Finance';
                                if ($user->is_driver_supervisor ?? false) $roles[] = 'Supervisor';
                                if ($user->is_it ?? false) $roles[] = 'IT';
                            @endphp
                            @foreach($roles as $role)
                                <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700">{{ $role }}</span>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user w-4 mr-3"></i>Profile Settings
                    </a>
                    <a href="{{ route('notifications') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-bell w-4 mr-3"></i>Notifications
                    </a>
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-home w-4 mr-3"></i>Back to Store
                    </a>
                    <div class="border-t border-gray-100 mt-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt w-4 mr-3"></i>Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications dropdown -->
    <div class="absolute right-4 top-16 z-50 mt-2 w-80 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" id="notifications-dropdown">
        <div class="px-4 py-3 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                @if($unreadCount > 0)
                    <button onclick="markAllAsRead()" class="text-xs text-primary-600 hover:text-primary-500">
                        Mark all read
                    </button>
                @endif
            </div>
        </div>
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications->take(5) as $notification)
                <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-b-0" onclick="markAsRead({{ $notification->id }})">
                    <div class="flex items-start gap-3">
                        <div class="h-2 w-2 rounded-full {{ $notification->is_read ? 'bg-gray-300' : 'bg-primary-500' }} mt-2 flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm {{ $notification->is_read ? 'text-gray-600' : 'text-gray-900 font-medium' }}">
                                {{ $notification->title ?? $notification->message }}
                            </p>
                            @if($notification->title && $notification->message)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($notification->message, 60) }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <i class="fas fa-bell-slash text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-500">No notifications</p>
                </div>
            @endforelse
        </div>
        @if($notifications->count() > 5)
            <div class="px-4 py-3 border-t border-gray-100 text-center">
                <a href="{{ route('notifications') }}" class="text-sm text-primary-600 hover:text-primary-500">
                    View all notifications
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notifications-dropdown');
    const userDropdown = document.getElementById('user-dropdown');
    
    // Close user dropdown
    userDropdown.classList.add('hidden');
    
    // Toggle notifications
    dropdown.classList.toggle('hidden');
}

function toggleUserMenu() {
    const dropdown = document.getElementById('user-dropdown');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    // Close notifications dropdown
    notificationsDropdown.classList.add('hidden');
    
    // Toggle user menu
    dropdown.classList.toggle('hidden');
}

function markAsRead(notificationId) {
    fetch(`/api/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    }).then(() => {
        location.reload();
    });
}

function markAllAsRead() {
    fetch('/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    }).then(() => {
        location.reload();
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (!e.target.closest('[onclick="toggleNotifications()"]') && !e.target.closest('#notifications-dropdown')) {
        notificationsDropdown.classList.add('hidden');
    }
    if (!e.target.closest('[onclick="toggleUserMenu()"]') && !e.target.closest('#user-dropdown')) {
        userDropdown.classList.add('hidden');
    }
});
</script>